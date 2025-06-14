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
use App\Services\UnitKerja\Traits\UnitKerjaNotifikasi;

class DashboardUnitKerjaController extends Controller {
    use UnitKerjaNotifikasi;

    public function getDashboard (Request $request){
        $dataComplaint = Laporan::with(['jenisMedia', 'unitKerja'])
        ->filter($request->only(['search', 'status']))
        ->orderBy('TGL_COMPLAINT', 'asc')
        ->paginate(10)
        ->withQueryString();

        return view ('services.unitKerja.dashboard.mainUnitKerja', ['dataComplaint' => $dataComplaint]);
    }

    public function show($id_complaint)
    {
        $complaint = Laporan::with([
            'unitKerja',
            'jenisMedia',
            'jenisLaporan',
            'klasifikasiPengaduan',
            'penyelesaianPengaduan'
        ])->where('ID_COMPLAINT', $id_complaint)
        ->first();

        if (!$complaint) {
            return response()->json(['message' => 'Data pengaduan tidak ditemukan.'], 404);
        }

        return response()->json($complaint);
    }

    public function update(Request $request, $id_complaint)
    {
        $validator = Validator::make($request->all(), [
            'JUDUL_COMPLAINT'    => 'required|string|max:255',
            'klarifikasi_unit'   => 'required|string|max:5000',
            'file_bukti'         => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'PETUGAS_EVALUASI'   => 'required|string|max:150',
            'TGL_EVALUASI'       => 'required|date',
        ], [
            'klarifikasi_unit.required' => 'Kolom Klarifikasi Unit wajib diisi.',
            'TGL_EVALUASI.required' => 'Kolom Tanggal Evaluasi wajib diisi.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('show_modal_on_error', '#editModal');
        }

        try {
            DB::transaction(function () use ($request, $id_complaint) {

                $complaint = Laporan::findOrFail($id_complaint);

                $updateData = [
                    'JUDUL_COMPLAINT'    => $request->input('JUDUL_COMPLAINT'),
                    'EVALUASI_COMPLAINT' => $request->input('klarifikasi_unit'),
                    'PETUGAS_EVALUASI'   => $request->input('PETUGAS_EVALUASI'),
                    'TGL_EVALUASI'       => $request->input('TGL_EVALUASI'),
                    'STATUS'             => 'Menunggu Konfirmasi',
                    'TGL_SELESAI'        => Carbon::now(),
                ];

                if ($request->hasFile('file_bukti')) {
                    if ($complaint->FILE_PENGADUAN) {
                        Storage::disk('public')->delete($complaint->FILE_PENGADUAN);
                    }
                    $newFilePath = $request->file('file_bukti')->store('bukti_klarifikasi', 'public');
                    $updateData['FILE_PENGADUAN'] = $newFilePath;
                }

                $complaint->update($updateData);
            });

            $updatedComplaint = Laporan::find($id_complaint);

            if ($updatedComplaint && $updatedComplaint->NO_TLPN) {
                $message = "Yth. Bpk/Ibu {$updatedComplaint->NAME},\n\n" .
                           "Laporan Anda dengan ID *{$updatedComplaint->ID_COMPLAINT}* telah diperbarui.\n\n" .
                           "Status saat ini: *{$updatedComplaint->STATUS}*.\n" .
                           "Klarifikasi dari unit kami: '{$updatedComplaint->EVALUASI_COMPLAINT}'\n\n" .
                           "Mohon Konfirmasi pada fitur Lacak Ticketing\n\n" .
                           "Terima kasih atas perhatian Anda.";

                $this->sendWhatsappNotification($updatedComplaint->NO_TLPN, $message);
            }

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
