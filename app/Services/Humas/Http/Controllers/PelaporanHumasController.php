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
        $gratifikasiKlasifikasi = KlasifikasiPengaduan::where('KLASIFIKASI_PENGADUAN', 'Gratifikasi')->first();
        $sponsorshipKlasifikasi = KlasifikasiPengaduan::where('KLASIFIKASI_PENGADUAN', 'Sponsorship')->first();

        $gratifikasiId = $gratifikasiKlasifikasi ? $gratifikasiKlasifikasi->ID_KLASIFIKASI : null;
        $sponsorshipId = $sponsorshipKlasifikasi ? $sponsorshipKlasifikasi->ID_KLASIFIKASI : null;

        $excludedIds = array_filter([$gratifikasiId, $sponsorshipId]);
        $excludedIdsString = implode(',', $excludedIds);

        $rules = [
            'JUDUL_COMPLAINT' => 'required|string|max:255',
            'NO_TLPN' => 'required_unless:ID_KLASIFIKASI,' . $excludedIdsString . '|nullable|string|max:20',
            'NAME' => 'required_unless:ID_KLASIFIKASI,' . $excludedIdsString . '|nullable|string|max:150',
            'ID_BAGIAN' => 'required|string|exists:unit_kerja,ID_BAGIAN',
            'NO_MEDREC' => 'nullable|string|max:50',
            'ID_JENIS_LAPORAN' => 'required|string|exists:jenis_laporan,ID_JENIS_LAPORAN',
            'ID_JENIS_MEDIA' => 'required|string|exists:jenis_media,ID_JENIS_MEDIA',
            'ID_KLASIFIKASI' => 'required|string|exists:klasifikasi_pengaduan,ID_KLASIFIKASI',
            'ISI_COMPLAINT' => 'required|string',
            'PERMASALAHAN' => 'nullable|string',
            'FILE_PENGADUAN' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ];

        $messages = [
            'required' => 'Kolom :attribute wajib diisi.',
            'required_unless' => 'Kolom :attribute wajib diisi kecuali untuk klasifikasi Gratifikasi atau Sponsorship.',
            'string' => 'Kolom :attribute harus berupa teks.',
            'max' => 'Kolom :attribute tidak boleh lebih dari :max karakter/KB.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'file' => ':attribute harus berupa file.',
            'mimes' => ':attribute harus berformat: :values.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

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

            $dataToCreate = ([
                'ID_COMPLAINT' => $newIdComplaint,
                'JUDUL_COMPLAINT' => $request->input('JUDUL_COMPLAINT'),
                'ID_BAGIAN' => $request->input('ID_BAGIAN'),
                'ID_JENIS_LAPORAN' => $request->input('ID_JENIS_LAPORAN'),
                'ID_JENIS_MEDIA' => $request->input('ID_JENIS_MEDIA'),
                'ID_KLASIFIKASI' => $request->input('ID_KLASIFIKASI'),
                'ISI_COMPLAINT' => $request->input('ISI_COMPLAINT'),
                'PERMASALAHAN' => $request->input('PERMASALAHAN'),
                'FILE_PENGADUAN' => $filePath,
                'TGL_COMPLAINT' => Carbon::now(),
                'STATUS' => 'Open',
            ]);

            $selectedKlasifikasiId = $request->input('ID_KLASIFIKASI');
            $isExcluded = in_array($selectedKlasifikasiId, $excludedIds);

            if ($isExcluded) {
                $submittedName = $request->input('NAME');
                $dataToCreate['NAME'] = empty($submittedName) ? 'Anonimus' : $submittedName;
                $dataToCreate['NO_TLPN'] = null;
                $dataToCreate['NO_MEDREC'] = null;
            } else {
                $dataToCreate['NAME'] = $request->input('NAME');
                $dataToCreate['NO_TLPN'] = $request->input('NO_TLPN');
                $dataToCreate['NO_MEDREC'] = $request->input('NO_MEDREC');
            }

            $laporanBaru = Laporan::create($dataToCreate);
            $isExcluded = in_array($selectedKlasifikasiId, $excludedIds);
            if (!$isExcluded) {
                $this->kirimNotifikasiStatusKePelapor($laporanBaru);
            }

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
        $complaint = Laporan::with('jenisMedia')->findOrFail($id_complaint);
        $isFromWebsite = $complaint->jenisMedia && $complaint->jenisMedia->JENIS_MEDIA === 'Website Helpdesk';

        $rules = [
            'JUDUL_COMPLAINT'       => 'required|string|max:255',
            'PETUGAS_PELAPOR'       => 'nullable|string|max:150',
            'ID_BAGIAN'             => 'required|string|exists:unit_kerja,ID_BAGIAN',
            'ID_JENIS_LAPORAN'      => 'required|string|exists:jenis_laporan,ID_JENIS_LAPORAN',
            'PERMASALAHAN'          => 'nullable|string',
            'STATUS'                => 'sometimes|in:Open,On Progress,Menunggu Konfirmasi,Close,Banding',
            'gradingOptions'        => 'required|in:Hijau,Kuning,Merah',
            'ID_PENYELESAIAN'       => 'nullable|string|exists:penyelesaian_pengaduan,ID_PENYELESAIAN',
            'TINDAK_LANJUT_HUMAS'   => 'nullable|string|max:4000',
        ];

        if (!$isFromWebsite) {
            $rules['NAME']            = 'required|string|max:150';
            $rules['NO_TLPN']         = 'required|string|max:20';
            $rules['NO_MEDREC']       = 'nullable|string|max:50';
            $rules['ID_KLASIFIKASI']  = 'required|string|exists:klasifikasi_pengaduan,ID_KLASIFIKASI';
            $rules['ISI_COMPLAINT']   = 'required|string';
            $rules['ID_JENIS_MEDIA']  = 'required|string|exists:jenis_media,ID_JENIS_MEDIA';
        }

        $messages = [
            'gradingOptions.required' => 'Grading wajib dipilih.',
            'gradingOptions.in' => 'Grading yang dipilih tidak valid.',
            'ID_BAGIAN' => 'Unit kerja tujuan wajib diisi'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput()
                        ->with('showModal', '#editModal');
        }

        DB::beginTransaction();
        try {
            $updateData = $request->except(['_token', '_method']);
            // $penyelesaianDiisi = $request->filled('ID_PENYELESAIAN');
            // $tindakLanjutDiisi = $request->filled('TINDAK_LANJUT_HUMAS');
            // $statusSekarang = $complaint->STATUS;

            $newStatus = $complaint->STATUS;
            if ($request->filled('ID_PENYELESAIAN') && $request->filled('TINDAK_LANJUT_HUMAS')) {
                $newStatus = 'Menunggu Konfirmasi';
            } else if ($complaint->STATUS === 'Open' && $request->filled('gradingOptions')) {
                $newStatus = 'On Progress';
            }
            $updateData['STATUS'] = $newStatus;

            if ($newStatus === 'On Progress' && is_null($complaint->TGL_PENUGASAN)) {
                $updateData['TGL_PENUGASAN'] = Carbon::now();
            }

             if ($request->filled('ID_PENYELESAIAN') && $request->filled('TINDAK_LANJUT_HUMAS')) {
                // Untuk mencegah TGL_SELESAI ter-update lagi jika diedit di kemudian hari
                if (is_null($complaint->TGL_SELESAI)) {
                    $updateData['TGL_SELESAI'] = Carbon::now();
                }
            }

            $updateData['GRANDING'] = $request->input('gradingOptions');

            if ($request->filled('ID_PENYELESAIAN')) {
                $penyelesaian = DB::table('penyelesaian_pengaduan')
                    ->where('ID_PENYELESAIAN', $request->input('ID_PENYELESAIAN'))
                    ->first();
                if ($penyelesaian) {
                    $updateData['DISPOSISI'] = $penyelesaian->PENYELESAIAN_PENGADUAN;
                }
            } else {
                $updateData['DISPOSISI'] = null;
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
