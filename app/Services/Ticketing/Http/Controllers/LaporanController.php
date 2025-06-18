<?php

namespace App\Services\Ticketing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\Ticketing\Models\Laporan;
use App\Services\Ticketing\Models\KlasifikasiPengaduan;
use Illuminate\Validation\ValidationException;
use App\Services\Ticketing\Traits\NotifikasiWhatsappPelapor;

class LaporanController extends Controller
{
    use NotifikasiWhatsappPelapor;

    public function getBuatLaporan()
    {
        $klasifikasiPengaduan = KlasifikasiPengaduan::where('STATUS', '1')->get();
        $idComplaintReferensi = request()->query('ref');
        $laporanReferensi = null;

        if ($idComplaintReferensi) {
            $laporanReferensi = Laporan::find($idComplaintReferensi);
        }
        return view('Services.Ticketing.buatLaporan.mainBuatLaporan', compact('klasifikasiPengaduan', 'idComplaintReferensi', 'laporanReferensi'));
    }

    // Membuat ID format YYYYMM_0000001
    private function generateCustomComplaintId(): string
    {
        $prefix = now()->format('Ym');
        $lastLaporan = Laporan::where('ID_COMPLAINT', 'like', $prefix . '_%')->orderBy('ID_COMPLAINT', 'desc')->first();
        $nextNumber = 1;
        if ($lastLaporan) {
            $lastNumber = (int) substr($lastLaporan->ID_COMPLAINT, -7);
            $nextNumber = $lastNumber + 1;
        }
        $number = str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
        return $prefix . '_' . $number;
    }

    public function uploadFile(Request $request)
    {
        Log::info('Request to uploadFile - Data:', $request->all());
        try {
            $validatedData = $request->validate([
                'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'upload_id' => 'required|string',
            ]);

            $uploadId = $validatedData['upload_id'];
            $file = $validatedData['file'];

            $path = $file->store('temp/' . $uploadId, 'local');
            Log::info('File stored to temporary path:', ['path' => $path]);

            return response()->json(['success' => true, 'path' => $path]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in uploadFile:', ['errors' => $e->errors(), 'request_data' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Validasi file gagal.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error in uploadFile:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Gagal mengunggah file: ' . $e->getMessage()], 500);
        }
    }

    private function validateAndPrepareLaporanData(Request $request, Laporan $laporan): array
    {
        $klasifikasi = KlasifikasiPengaduan::find($request->ID_KLASIFIKASI);
        $klasifikasiText = $klasifikasi ? strtolower($klasifikasi->KLASIFIKASI_PENGADUAN) : '';

        $rules = [
            'ID_KLASIFIKASI' => 'required|string|exists:klasifikasi_pengaduan,ID_KLASIFIKASI',
            'ISI_COMPLAINT' => 'required|string|max:4000',
            'upload_id' => 'required|string',
            'uploaded_files' => 'nullable|array',
            'uploaded_files.*' => 'string',
            'ID_COMPLAINT_REFERENSI' => 'nullable|string|exists:data_complaint,ID_COMPLAINT',
            'NO_MEDREC' => 'nullable|string|max:10',
        ];

        if ($klasifikasiText === 'gratifikasi') {
            $rules['NAME'] = 'nullable|string|max:100';
            $rules['NO_TLPN'] = 'nullable|string|max:15';
            $rules['uploaded_files'] = 'required|array|min:1';
        } else {
            $rules['NAME'] = 'required|string|max:100';
            $rules['NO_TLPN'] = 'required|string|max:15|regex:/^08\d{8,13}$/';
            if ($klasifikasiText === 'sponsorship') {
                 $rules['uploaded_files'] = 'required|array|min:1';
            }
        }

        $validatedData = $request->validate($rules, [
            'uploaded_files.required' => 'Bukti pendukung wajib diisi untuk klasifikasi ini.',
            'NO_TLPN.regex' => 'Format nomor telepon tidak valid.'
        ]);

        $laporan->ID_COMPLAINT = $this->generateCustomComplaintId();
        // Log::info('Generated Laporan ID:', ['id' => $laporan->ID_COMPLAINT]);
        $laporan->TGL_COMPLAINT = Carbon::now()->toDateString();
        $laporan->TGL_INSROW = Carbon::now()->toDateString();
        $laporan->STATUS = 'Open';
        $laporan->ID_JENIS_MEDIA = '20250613000016';
        $laporan->ID_KLASIFIKASI = $validatedData['ID_KLASIFIKASI'];
        $laporan->ISI_COMPLAINT = $validatedData['ISI_COMPLAINT'];
        $laporan->NAME = $validatedData['NAME'] ?? null;
        $laporan->NO_TLPN = $validatedData['NO_TLPN'] ?? null;
        $laporan->NO_MEDREC = $validatedData['NO_MEDREC'] ?? null;
        $laporan->ID_COMPLAINT_REFERENSI = $validatedData['ID_COMPLAINT_REFERENSI'] ?? null;

        return $validatedData;
    }

    private function processAndStoreFiles(Laporan $laporan, ?array $tempFilePaths): ?string
    {
        if (empty($tempFilePaths)) {
            return null;
        }

        $allFinalFilePaths = [];
        $fileCounter = 1;
        foreach ($tempFilePaths as $tempPath) {
            Log::info('Processing temporary file path:', ['tempPath' => $tempPath]);
            if (Storage::disk('local')->exists($tempPath)) {
                $fileContents = Storage::disk('local')->get($tempPath);
                $originalExtension = pathinfo($tempPath, PATHINFO_EXTENSION);

                $newFilename = $laporan->ID_COMPLAINT . '_bukti_'. $fileCounter . '.' . $originalExtension;
                $finalPath = 'pengaduan_files/' . $newFilename;

                Storage::disk('public')->put($finalPath, $fileContents);
                Log::info('File moved to public storage:', ['finalPath' => $finalPath]);

                $allFinalFilePaths[] = $finalPath;
                $fileCounter++;
            } else {
                Log::warning('Temporary file not found:', ['tempPath' => $tempPath]);
            }
        }

        return !empty($allFinalFilePaths) ? implode(';', $allFinalFilePaths) : null;
    }

    public function storeLaporan(Request $request)
    {
        Log::info('Request to storeLaporan - Data:', $request->all());
        $uploadIdForCleanup = $request->input('upload_id');

        try {
            $laporan = new Laporan();

            $validatedData = $this->validateAndPrepareLaporanData($request, $laporan);
            if(isset($validatedData['upload_id'])) {
                $uploadIdForCleanup = $validatedData['upload_id'];
            }


            DB::beginTransaction();

            $mergedFilePaths = $this->processAndStoreFiles($laporan, $validatedData['uploaded_files'] ?? null);
            if ($mergedFilePaths) {
                $laporan->FILE_PENGADUAN = $mergedFilePaths;
            }

            $laporan->save();
            DB::commit();
            Log::info('Laporan saved successfully:', ['id' => $laporan->ID_COMPLAINT]);

            $this->kirimNotifikasiStatusKePelapor($laporan);

            if ($uploadIdForCleanup) {
                Storage::disk('local')->deleteDirectory('temp/' . $uploadIdForCleanup);
                Log::info('Temporary directory deleted successfully after commit:', ['upload_id' => $uploadIdForCleanup]);
            }

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Laporan berhasil dikirim dengan ID Tiket: ' . $laporan->ID_COMPLAINT, 'tiket_id' => $laporan->ID_COMPLAINT]);
            }

            return redirect()->route('ticketing.buat-laporan')->with('success', 'Laporan berhasil dikirim dengan ID Tiket: ' . $laporan->ID_COMPLAINT);

        } catch (ValidationException $e) {
            Log::error('Validation error in storeLaporan:', ['errors' => $e->errors(), 'request_data' => $request->all()]);
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Data tidak valid.', 'errors' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in storeLaporan:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            if ($uploadIdForCleanup) {
                Storage::disk('local')->deleteDirectory('temp/' . $uploadIdForCleanup);
                Log::info('Temporary directory deleted due to exception:', ['upload_id' => $uploadIdForCleanup]);
            }
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem saat menyimpan laporan.'], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat menyimpan laporan.')->withInput();
        }
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Laporan berhasil dikirim dengan ID Tiket: ' . $laporan->ID_COMPLAINT, 'tiket_id' => $laporan->ID_COMPLAINT]);
        }
        return redirect()->route('ticketing.buat-laporan')->with('success', 'Laporan berhasil dikirim dengan ID Tiket: ' . $laporan->ID_COMPLAINT);
    }
}
