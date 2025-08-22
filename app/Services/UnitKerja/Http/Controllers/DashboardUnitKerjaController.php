<?php

namespace App\Services\UnitKerja\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Ticketing\Models\UnitKerja;
use App\Services\Ticketing\Models\JenisLaporan;
use App\Services\Ticketing\Models\JenisMedia;
use App\Services\Ticketing\Models\KlasifikasiPengaduan;
use App\Services\Ticketing\Models\Laporan;
use App\Services\Ticketing\Models\PenyelesaianPengaduan;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
// use App\Services\UnitKerja\Traits\UnitKerjaNotifikasi;
use App\Services\Humas\Traits\NotifikasiWhatsApp;
class DashboardUnitKerjaController extends Controller {
    // use UnitKerjaNotifikasi;
    use NotifikasiWhatsApp;

    public function getDashboard (Request $request){
        $idBagianPengguna = session('user')->ID_BAGIAN ?? null;

        if (!$idBagianPengguna) {
            $dataComplaint = Laporan::whereRaw('1 = 0')->paginate(10);
            return view('services.unitKerja.dashboard.mainUnitKerja', ['dataComplaint' => $dataComplaint]);
        }

        $query = Laporan::with(['jenisMedia', 'unitKerja'])
            ->whereNotNull('GRANDING')
            ->where(function ($q) use ($idBagianPengguna) {
                $q->where('ID_BAGIAN', $idBagianPengguna)
                  ->orWhereJsonContains('ID_BAGIAN_LAINNYA', $idBagianPengguna);
            });


        $dataComplaint = $query->filter($request->only(['search', 'status']))
            ->orderBy('TGL_COMPLAINT', 'desc')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'table' => view('services.unitKerja.dashboard.partials.tableContent', ['dataComplaint' => $dataComplaint])->render(),
                'pagination' => $dataComplaint->links()->toHtml(),
            ]);
        }

        return view ('services.unitKerja.dashboard.mainUnitKerja', ['dataComplaint' => $dataComplaint]);
    }

    public function show($id_complaint)
    {
        try {
            $idBagianPengguna = session('user')->ID_BAGIAN ?? null;

            if (!$idBagianPengguna) {
                return response()->json(['message' => 'Sesi pengguna tidak valid.'], 403);
            }

            $complaint = Laporan::with(['jenisMedia', 'jenisLaporan', 'klasifikasiPengaduan', 'penyelesaianPengaduan'])
                ->where('ID_COMPLAINT', $id_complaint)
                ->where(function ($query) use ($idBagianPengguna) {
                    $query->where('ID_BAGIAN', $idBagianPengguna)
                          ->orWhereJsonContains('ID_BAGIAN_LAINNYA', $idBagianPengguna);
                })
                ->first();

            if (!$complaint) {
                return response()->json(['message' => 'Data pengaduan tidak ditemukan atau Anda tidak memiliki akses.'], 404);
            }

            $unitKerjaMap = $complaint->unit_kerja_list->pluck('NAMA_BAGIAN', 'ID_BAGIAN');
            $klarifikasiList = $complaint->klarifikasi_list;
            $augmentedKlarifikasi = array_map(function ($item) use ($unitKerjaMap) {
                $item['nama_bagian'] = $unitKerjaMap[$item['id_bagian']] ?? 'Unit Kerja Tidak Dikenal';
                return $item;
            }, $klarifikasiList);

            $dataAsArray = $complaint->toArray();
            $dataAsArray['klarifikasi_list'] = $augmentedKlarifikasi;
            $dataAsArray['id_bagian_pengguna'] = $idBagianPengguna;

            $flatKlarifikasiFiles = [];
            if (isset($dataAsArray['klarifikasi_files']) && is_array($dataAsArray['klarifikasi_files'])) {
                $filesPerUnit = array_column($dataAsArray['klarifikasi_files'], 'files');
                if (!empty($filesPerUnit)) {
                     $flatKlarifikasiFiles = array_merge(...$filesPerUnit);
                }
            }
            $dataAsArray['klarifikasi_files'] = $flatKlarifikasiFiles;
            array_walk_recursive($dataAsArray, function (&$item, $key) {
                if (is_string($item)) {
                    $item = mb_convert_encoding($item, 'UTF-8', 'UTF-8');
                }
            });

            return response()->json($dataAsArray);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id_complaint)
    {
        $idBagianPengguna = session('user')->ID_BAGIAN ?? null;

        $complaint = Laporan::where('ID_COMPLAINT', $id_complaint)
        ->where(function ($query) use ($idBagianPengguna) {
            $query->where('ID_BAGIAN', $idBagianPengguna)
                  ->orWhereJsonContains('ID_BAGIAN_LAINNYA', $idBagianPengguna);
        })
        ->firstOrFail();


        $minEvaluationDate = $complaint->TGL_PENUGASAN ? Carbon::parse($complaint->TGL_PENUGASAN)->format('Y-m-d') : null;

        $rules = [
            'klarifikasi_unit'   => 'required|string|max:5000',
            'file_bukti'         => 'nullable|array',
            'file_bukti.*'       => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'PETUGAS_EVALUASI'   => 'required|string|max:150',
            'TGL_EVALUASI'       => ['required', 'date', 'before_or_equal:today'],
        ];

        if ($minEvaluationDate) {
            $rules['TGL_EVALUASI'][] = 'after_or_equal:' . $minEvaluationDate;
        }
         $messages = [
            'klarifikasi_unit.required' => 'Kolom Klarifikasi Unit wajib diisi.',
            'TGL_EVALUASI.required'     => 'Kolom Tanggal Evaluasi wajib diisi.',
            'TGL_EVALUASI.after_or_equal' => 'Tanggal evaluasi tidak boleh sebelum tanggal penugasan (' . ($minEvaluationDate ? Carbon::parse($minEvaluationDate)->format('d M Y') : '') . ').',
            'TGL_EVALUASI.before_or_equal'=> 'Tanggal evaluasi tidak boleh melebihi tanggal hari ini.',
            'file_bukti.*.mimes'        => 'Tipe file bukti tidak valid. Hanya boleh: jpg, jpeg, png, pdf, doc, docx.',
            'file_bukti.*.max'          => 'Ukuran setiap file bukti tidak boleh lebih dari 2MB.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error_complaint_id', $id_complaint);
        }

        $validatedData = $validator->validated();

        try {
            DB::transaction(function () use ($request, $complaint, $validatedData, $idBagianPengguna) {

                $klarifikasiList = $complaint->klarifikasi_list;

                $klarifikasiBaru = [
                    'id_bagian'   => $idBagianPengguna,
                    'klarifikasi' => $validatedData['klarifikasi_unit'],
                    'petugas'     => $validatedData['PETUGAS_EVALUASI'],
                    'tanggal'     => $validatedData['TGL_EVALUASI'],
                ];

                $index = array_search($idBagianPengguna, array_column($klarifikasiList, 'id_bagian'));

                if ($index !== false) {
                    $klarifikasiList[$index] = $klarifikasiBaru;
                } else {
                    $klarifikasiList[] = $klarifikasiBaru;
                }

                $updateData = [
                    'EVALUASI_COMPLAINT' => json_encode(array_values($klarifikasiList)),
                    'TGL_EVALUASI'       => $validatedData['TGL_EVALUASI'],
                    'PETUGAS_EVALUASI'   => $validatedData['PETUGAS_EVALUASI'],
                ];

                $semuaSudahKlarifikasi = count($klarifikasiList) >= $complaint->unit_kerja_list->count();
                if ($semuaSudahKlarifikasi) {
                    $updateData['STATUS'] = 'On Progress';
                } else {
                    $updateData['STATUS'] = 'On Progress';
                }

                if ($request->hasFile('file_bukti')) {
                    $idBagianPengguna = session('user')->ID_BAGIAN;
                    $allFilesData = json_decode($complaint->FILE_BUKTI_KLARIFIKASI, true) ?? [];

                    $otherUnitsFilesData = [];
                    foreach ($allFilesData as $unitData) {
                        if (isset($unitData['id_bagian']) && $unitData['id_bagian'] == $idBagianPengguna) {
                            foreach ($unitData['files'] as $oldFile) {
                                Storage::disk('public')->delete($oldFile);
                            }
                        } else {
                            $otherUnitsFilesData[] = $unitData;
                        }
                    }

                    $newUploadedPaths = [];
                    foreach ($request->file('file_bukti') as $file) {
                        $path = $file->store('bukti_klarifikasi', 'public');
                        $newUploadedPaths[] = $path;
                    }

                    if (!empty($newUploadedPaths)) {
                        $otherUnitsFilesData[] = [
                            'id_bagian' => $idBagianPengguna,
                            'files'     => $newUploadedPaths
                        ];
                    }

                    $updateData['FILE_BUKTI_KLARIFIKASI'] = json_encode(array_values($otherUnitsFilesData));
                }

                $complaint->update($updateData);
            });

            $updatedComplaint = Laporan::with('unitKerja')->find($id_complaint);

            $this->kirimNotifikasiKePelapor($updatedComplaint);
            $unitKerjaPengirim = UnitKerja::find($idBagianPengguna);
            $namaUnitPengirim = $unitKerjaPengirim ? $unitKerjaPengirim->NAMA_BAGIAN : 'Unit Kerja';
            $this->kirimNotifikasiKeHumas($updatedComplaint, 'klarifikasi_unit', ['nama_unit' => $namaUnitPengirim]);

            return redirect()->route('unitKerja.dashboard')
                             ->with('success', 'Klarifikasi untuk ID ' . $id_complaint . ' berhasil disimpan.');

        } catch (\Exception $e) {
            report($e);
            return redirect()->back()
                             ->with('error', 'Terjadi kesalahan sistem saat menyimpan data: ' . $e->getMessage())
                             ->withInput();
        }
    }
}
