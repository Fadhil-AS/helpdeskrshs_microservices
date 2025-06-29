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
use Illuminate\Support\Facades\Storage;
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

        $query = Laporan::with(['unitKerja', 'jenisMedia'])
        ->select(
            'data_complaint.*',
            DB::raw('TIMESTAMPDIFF(DAY, TGL_PENUGASAN, TGL_EVALUASI) as response_time')
        );

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
        $etikKlasifikasi = KlasifikasiPengaduan::where('KLASIFIKASI_PENGADUAN', 'Etik')->first();

        $gratifikasiId = $gratifikasiKlasifikasi ? $gratifikasiKlasifikasi->ID_KLASIFIKASI : null;
        $sponsorshipId = $sponsorshipKlasifikasi ? $sponsorshipKlasifikasi->ID_KLASIFIKASI : null;
        $etikId = $etikKlasifikasi ? $etikKlasifikasi->ID_KLASIFIKASI : null;

        $excludedIds = array_filter([$gratifikasiId, $sponsorshipId]);
        $excludedIdsString = implode(',', $excludedIds);
        $fileWajibIds = array_filter([$gratifikasiId, $sponsorshipId]);

        $rules = [
            'jenis_pelapor' => 'required|string|in:Pasien,Non-Pasien',
            'NO_TLPN' => 'required_unless:ID_KLASIFIKASI,' . $excludedIdsString . '|nullable|string|max:20',
            'NAME' => 'required_unless:ID_KLASIFIKASI,' . $excludedIdsString . '|nullable|string|max:150',
            'NO_MEDREC' => 'nullable|string|max:50',
            'ID_JENIS_MEDIA' => 'required|string|exists:jenis_media,ID_JENIS_MEDIA',
            'ID_KLASIFIKASI' => 'required|string|exists:klasifikasi_pengaduan,ID_KLASIFIKASI',
            'ISI_COMPLAINT' => 'required|string',
            'PERMASALAHAN' => 'nullable|string',
            'FILE_PENGADUAN'   => 'nullable|array',
            'FILE_PENGADUAN.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ];

        $messages = [
            'required' => 'Kolom :attribute wajib diisi.',
            'FILE_PENGADUAN.*.required' => 'File pengaduan wajib diunggah untuk klasifikasi Sponsorship atau Gratifikasi.',
            'FILE_PENGADUAN.*.mimes'    => 'Tipe file pengaduan tidak valid.',
            'FILE_PENGADUAN.*.max'      => 'Ukuran setiap file pengaduan tidak boleh lebih dari 2MB.',
            'in' => 'Kolom :attribute yang dipilih tidak valid.',
            'required_unless' => 'Kolom :attribute wajib diisi kecuali untuk klasifikasi Gratifikasi atau Sponsorship.',
            'string' => 'Kolom :attribute harus berupa teks.',
            'max' => 'Kolom :attribute tidak boleh lebih dari :max karakter/KB.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'file' => ':attribute harus berupa file.',
            'mimes' => ':attribute harus berformat: :values.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->sometimes('FILE_PENGADUAN', 'required', function ($input) use ($fileWajibIds) {
            return in_array($input->ID_KLASIFIKASI, $fileWajibIds);
        });

        if ($validator->fails()) {
            return redirect()->route('humas.pelaporan-humas')
                        ->withErrors($validator)
                        ->withInput()
                        ->with('showModal', '#modalTambahPengaduan');
        }

        DB::beginTransaction();
        try {
            $newIdComplaint = $this->generateComplaintId();
            $uploadedPaths = [];
            if ($request->hasFile('FILE_PENGADUAN')) {
                foreach ($request->file('FILE_PENGADUAN') as $key => $file) {
                    if ($file->isValid()) {
                        $fileName = $newIdComplaint . '_bukti_' . ($key + 1) . '_' . time() . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs('pengaduan_files', $fileName, 'public');
                        $uploadedPaths[] = $path;
                    }
                }
            }

            $jenisPelapor = $request->input('jenis_pelapor');
            $idJenisLaporanDefault = '';
            if ($jenisPelapor === 'Pasien') {
                $idJenisLaporanDefault = 'Pasien';
            } elseif ($jenisPelapor === 'Non-Pasien') {
                $idJenisLaporanDefault = 'Non-Pasien';
            }

            $dataToCreate = ([
                'ID_COMPLAINT' => $newIdComplaint,
                'JENIS_PELAPOR' => $jenisPelapor,
                'ID_JENIS_MEDIA' => $request->input('ID_JENIS_MEDIA'),
                'ID_KLASIFIKASI' => $request->input('ID_KLASIFIKASI'),
                'ISI_COMPLAINT' => $request->input('ISI_COMPLAINT'),
                'PERMASALAHAN' => $request->input('PERMASALAHAN'),
                'FILE_PENGADUAN' => !empty($uploadedPaths) ? json_encode($uploadedPaths) : null,
                'TGL_COMPLAINT' => Carbon::now(),
                'STATUS' => 'Open',
            ]);

            $selectedKlasifikasiId = $request->input('ID_KLASIFIKASI');
            $isExcluded = in_array($selectedKlasifikasiId, $excludedIds);

            // $klasifikasiText = KlasifikasiPengaduan::find($selectedKlasifikasiId)->KLASIFIKASI_PENGADUAN ?? 'Pengaduan';

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

            $processFiles = function ($fileData) {
                if (empty($fileData)) {
                    return [];
                }
                $decoded = json_decode($fileData, true);
                if (is_array($decoded)) {
                    return $decoded;
                }
                if (str_contains($fileData, ';')) {
                    return explode(';', $fileData);
                }
                return [$fileData];
            };
            $complaint->pengaduan_files = $processFiles($complaint->FILE_PENGADUAN);
            $complaint->klarifikasi_files = $processFiles($complaint->FILE_BUKTI_KLARIFIKASI);
            $complaint->tindak_lanjut_files = $processFiles($complaint->FILE_TINDAK_LANJUT_HUMAS);

            return response()->json($complaint);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage()], 500);
        }
    }

    public function updatePelaporanHumas(Request $request, $id_complaint)
    {
        // dd($request->all());
        $complaint = Laporan::with('jenisMedia')->findOrFail($id_complaint);
        $isFromWebsite = $complaint->jenisMedia && $complaint->jenisMedia->JENIS_MEDIA === 'Website Helpdesk';

        $gratifikasiId = KlasifikasiPengaduan::where('KLASIFIKASI_PENGADUAN', 'Gratifikasi')->first()->ID_KLASIFIKASI ?? null;
        $sponsorshipId = KlasifikasiPengaduan::where('KLASIFIKASI_PENGADUAN', 'Sponsorship')->first()->ID_KLASIFIKASI ?? null;

        $excludedIds = array_filter([$gratifikasiId, $sponsorshipId]);
        $excludedIdsString = implode(',', $excludedIds);

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
            'file_tindak_lanjut'    => 'nullable|array',
            'file_tindak_lanjut.*'  => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ];

        if (!$isFromWebsite) {
            $rules['NAME']            = 'required_unless:ID_KLASIFIKASI,' . $excludedIdsString . '|nullable|string|max:150';
            $rules['NO_TLPN']         = 'required_unless:ID_KLASIFIKASI,' . $excludedIdsString . '|nullable|string|max:20';
            $rules['NO_MEDREC']       = 'nullable|string|max:50';
            $rules['ID_KLASIFIKASI']  = 'required|string|exists:klasifikasi_pengaduan,ID_KLASIFIKASI';
            $rules['ISI_COMPLAINT']   = 'required|string';
            $rules['ID_JENIS_MEDIA']  = 'required|string|exists:jenis_media,ID_JENIS_MEDIA';
        }

        $messages = [
            'gradingOptions.required' => 'Grading wajib dipilih.',
            'gradingOptions.in' => 'Grading yang dipilih tidak valid.',
            'ID_BAGIAN' => 'Unit kerja tujuan wajib diisi',
            'required_unless'         => 'Kolom :attribute wajib diisi kecuali untuk klasifikasi Gratifikasi atau Sponsorship.',
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
            $updateData = $request->except(['_token', '_method', 'new_files', 'deleted_files_input', 'file_tindak_lanjut']);
            // $penyelesaianDiisi = $request->filled('ID_PENYELESAIAN');
            // $tindakLanjutDiisi = $request->filled('TINDAK_LANJUT_HUMAS');
            // $statusSekarang = $complaint->STATUS;

            if (!$isFromWebsite) {
                $currentPengaduanFiles = json_decode($complaint->FILE_PENGADUAN, true) ?? [];
                $remainingFiles = $currentPengaduanFiles;
                if ($request->has('deleted_files_input') && !empty($request->input('deleted_files_input'))) {
                    $filesToDelete = explode(',', $request->input('deleted_files_input'));
                    $filesToDelete = array_map('trim', $filesToDelete);

                    foreach ($filesToDelete as $filePathToDelete) {
                        if (!empty($filePathToDelete)) {
                            Storage::disk('public')->delete($filePathToDelete);
                        }
                    }

                    $remainingFiles = array_diff($currentPengaduanFiles, $filesToDelete);
                }

                $newPengaduanPaths = [];
                if ($request->hasFile('new_files')) {
                    foreach ($request->file('new_files') as $key => $file) {
                        if ($file->isValid()) {
                            $fileName = $complaint->ID_COMPLAINT . '_bukti_updated_' . time() . '_' . ($key + 1) . '.' . $file->getClientOriginalExtension();
                            $path = $file->storeAs('pengaduan_files', $fileName, 'public');
                            $newPengaduanPaths[] = $path;
                        }
                    }
                }

                $finalPengaduanFiles = array_merge($remainingFiles, $newPengaduanPaths);
                $updateData['FILE_PENGADUAN'] = !empty($finalPengaduanFiles) ? json_encode(array_values($finalPengaduanFiles)) : null;
            }

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
            if ($request->hasFile('file_tindak_lanjut')) {
                $existingFiles = json_decode($complaint->FILE_TINDAK_LANJUT_HUMAS, true) ?? [];

                $newUploadedPaths = [];
                foreach ($request->file('file_tindak_lanjut') as $file) {
                    $path = $file->store('tindak_lanjut_humas', 'public');
                    $newUploadedPaths[] = $path;
                }

                $allFiles = array_merge($existingFiles, $newUploadedPaths);

                $updateData['FILE_TINDAK_LANJUT_HUMAS'] = json_encode($allFiles);
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
