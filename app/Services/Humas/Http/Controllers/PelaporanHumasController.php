<?php

namespace App\Services\Humas\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Ticketing\Models\UnitKerja;
use App\Services\Ticketing\Models\JenisLaporan;
use App\Services\Ticketing\Models\JenisMedia;
use App\Services\Ticketing\Models\KlasifikasiPengaduan;
use App\Services\Ticketing\Models\Laporan;
use App\Services\Ticketing\Models\PenyelesaianPengaduan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\Humas\Traits\NotifikasiWhatsappPelapor;

class PelaporanHumasController extends Controller {

    use NotifikasiWhatsappPelapor;

    public function getPelaporanHumas(Request $request){
        // dd($request->all());

        $unitKerja = UnitKerja::where('STATUS', '1')->get();
        $JenisLaporan = JenisLaporan::where('STATUS', '1')->get();
        $JenisMedia = JenisMedia::where('STATUS', '1')->get();
        $klasifikasiPengaduan = KlasifikasiPengaduan::where('STATUS', '1')->get();
        $penyelesaianPengaduan = PenyelesaianPengaduan::where('STATUS', '1')->get();

        $query = Laporan::with(['unitKerja', 'jenisMedia']);
        if ($request->filled('status')) {
            $query->where('STATUS', $request->status);
        }

        $dataComplaint = $query->orderBy('ID_COMPLAINT', 'asc')
                                ->paginate(10)
                                ->withQueryString();


        return view('Services.Humas.Pelaporan.mainPelaporan', compact(
            'unitKerja',
            'JenisLaporan',
            'JenisMedia',
            'klasifikasiPengaduan',
            'dataComplaint',
            'penyelesaianPengaduan'
        ));
    }

    private function generateComplaintId()
    {
        $prefix = Carbon::now()->format('Ym') . '_';
        $lastComplaint = Laporan::where('ID_COMPLAINT', 'LIKE', $prefix . '%')
                                ->orderBy('ID_COMPLAINT', 'desc')
                                ->first();

        $nextNumber = 1;
        if ($lastComplaint) {
            $lastNumberStr = substr($lastComplaint->ID_COMPLAINT, strlen($prefix));
            if (is_numeric($lastNumberStr)) {
                $nextNumber = (int)$lastNumberStr + 1;
            }
        }

        return $prefix . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    }

    public function storePelaporanHumas(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'JUDUL_COMPLAINT' => 'required|string|max:255',
            'NO_TLPN' => 'required|string|max:20',
            'NAME' => 'required|string|max:150',
            'ID_BAGIAN' => 'required|string|exists:unit_kerja,ID_BAGIAN',
            'NO_MEDREC' => 'nullable|string|max:50',
            'ID_JENIS_LAPORAN' => 'required|string|exists:jenis_laporan,ID_JENIS_LAPORAN',
            'ID_JENIS_MEDIA' => 'required|string|exists:jenis_media,ID_JENIS_MEDIA',
            'ID_KLASIFIKASI' => 'required|string|exists:klasifikasi_pengaduan,ID_KLASIFIKASI',
            'ISI_COMPLAINT' => 'required|string',
            'PERMASALAHAN' => 'nullable|string',
            'FILE_PENGADUAN' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ], [
            'required' => 'Kolom :attribute wajib diisi.',
            'string' => 'Kolom :attribute harus berupa teks.',
            'max' => 'Kolom :attribute tidak boleh lebih dari :max karakter/KB.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'file' => ':attribute harus berupa file.',
            'mimes' => ':attribute harus berformat: :values.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('humas.pelaporan-humas')
                        ->withErrors($validator)
                        ->withInput()
                        ->with('showModal', '#modalTambahPengaduan');
        }

        DB::beginTransaction();
        try {
            $newIdComplaint = $this->generateComplaintId();
            $filePath = null;

            if ($request->hasFile('FILE_PENGADUAN') && $request->file('FILE_PENGADUAN')->isValid()) {
                $file = $request->file('FILE_PENGADUAN');
                $fileName = $newIdComplaint . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('pengaduan_files', $fileName, 'public');
            }

            $laporanBaru = Laporan::create([
                'ID_COMPLAINT' => $newIdComplaint,
                'JUDUL_COMPLAINT' => $request->input('JUDUL_COMPLAINT'),
                'NO_TLPN' => $request->input('NO_TLPN'),
                'NAME' => $request->input('NAME'),
                'ID_BAGIAN' => $request->input('ID_BAGIAN'),
                'NO_MEDREC' => $request->input('NO_MEDREC'),
                'ID_JENIS_LAPORAN' => $request->input('ID_JENIS_LAPORAN'),
                'ID_JENIS_MEDIA' => $request->input('ID_JENIS_MEDIA'),
                'ID_KLASIFIKASI' => $request->input('ID_KLASIFIKASI'),
                'ISI_COMPLAINT' => $request->input('ISI_COMPLAINT'),
                'PERMASALAHAN' => $request->input('PERMASALAHAN'),
                'FILE_PENGADUAN' => $filePath,
                'TGL_COMPLAINT' => Carbon::now(),
                'STATUS' => 'Open',
            ]);

            $this->kirimNotifikasiStatusKePelapor($laporanBaru);

            DB::commit();

            return redirect()->route('humas.pelaporan-humas')->with('success', 'Pengaduan berhasil ditambahkan dengan ID: ' . $newIdComplaint);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('humas.pelaporan-humas')
                        ->with('error', 'Gagal menambahkan pengaduan: ' . $e->getMessage())
                        ->withInput()
                        ->with('showModal', '#modalTambahPengaduan');
        }
    }

    public function showPelaporanDetail($id_complaint)
    {
        try {
            $complaint = Laporan::with([
                'unitKerja',
                'jenisMedia',
                'jenisLaporan',
                'klasifikasiPengaduan',
                'penyelesaianPengaduan'
            ])->where('ID_COMPLAINT', $id_complaint)
              ->first();

            if (!$complaint) {
                return response()->json(['error' => 'Data pengaduan tidak ditemukan.'], 404);
            }
            return response()->json($complaint);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage()], 500);
        }
    }

    public function updatePelaporanHumas(Request $request, $id_complaint)
    {
        $validator = Validator::make($request->all(), [
            'JUDUL_COMPLAINT'       => 'required|string|max:255',
            'NAME'                  => 'required|string|max:150',
            'NO_TLPN'               => 'required|string|max:20',
            'NO_MEDREC'             => 'nullable|string|max:50',
            'PETUGAS_PELAPOR'       => 'nullable|string|max:150',
            'ID_BAGIAN'             => 'required|string|exists:unit_kerja,ID_BAGIAN',
            'ID_JENIS_LAPORAN'      => 'required|string|exists:jenis_laporan,ID_JENIS_LAPORAN',
            'ID_JENIS_MEDIA'        => 'required|string|exists:jenis_media,ID_JENIS_MEDIA',
            'ID_KLASIFIKASI'        => 'required|string|exists:klasifikasi_pengaduan,ID_KLASIFIKASI',
            'ISI_COMPLAINT'         => 'required|string',
            'PERMASALAHAN'          => 'nullable|string',
            'STATUS'                => 'required|in:Open,On Progress,Menunggu Konfirmasi,Close,Banding',
            'gradingOptions'        => 'nullable|in:Hijau,Kuning,Merah',
            'ID_PENYELESAIAN'       => 'nullable|string|exists:penyelesaian_pengaduan,ID_PENYELESAIAN',
            'TINDAK_LANJUT_HUMAS'   => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput()
                        ->with('showModal', '#editModal');
        }

        DB::beginTransaction();
        try {
            $complaint = Laporan::findOrFail($id_complaint);

            $updateData = $request->except(['_token', '_method']);

            if ($request->has('gradingOptions')) {
                $updateData['GRANDING'] = $request->input('gradingOptions');
                unset($updateData['gradingOptions']);
            }

            if($request->input('STATUS') == 'On Progress' && is_null($complaint->TGL_EVALUASI)) {
                $updateData['TGL_EVALUASI'] = Carbon::now();
            }

            if(in_array($request->input('STATUS'), ['Menunggu Konfirmasi', 'Close']) && is_null($complaint->TGL_SELESAI)) {
                $updateData['TGL_SELESAI'] = Carbon::now();
            }

            $complaint->update($updateData);
            if ($complaint->wasChanged('STATUS')) {
                $this->kirimNotifikasiStatusKePelapor($complaint->fresh());
            }

            DB::commit();

            return redirect()->route('humas.pelaporan-humas')->with('success', 'Pengaduan ID ' . $id_complaint . ' berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui pengaduan: ' . $e->getMessage())->withInput();
        }
    }
}
