<?php

namespace App\Services\Humas\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Humas\Models\Direksi;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class DireksiHumasController extends Controller {
    public function getDireksiHumas(){
        $allDireksi = Direksi::orderBy('ID_DIREKSI', 'asc')->paginate(10);
        return view('Services.Humas.Direksi.mainDireksi', compact('allDireksi'));
    }

    private function sendWhatsappNotification($target, $message)
    {
        if (substr($target, 0, 1) === '0') {
            $target = '62' . substr($target, 1);
        }

        $token = env('FONNTE_API_TOKEN');

        if (!$token) {
            return;
        }

        try {
            Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => 'ID',
            ]);
        } catch (\Exception $e) {
            // Log::error('Gagal mengirim WhatsApp: ' . $e->getMessage());
        }
    }

    public function storeDireksiHumas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'no_tlpn' => 'required|string|unique:complaint_direksi,NO_TLPN|max:20',
            'ket' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $latestDireksi = Direksi::orderBy('ID_DIREKSI', 'desc')->first();
            $newId = $latestDireksi ? $latestDireksi->ID_DIREKSI + 1 : 1;

            $direksi = Direksi::create([
                'ID_DIREKSI' => $newId,
                'NAMA' => $request->nama,
                'NO_TLPN' => $request->no_tlpn,
                'KET' => $request->ket,
            ]);

            $message = "Data Direksi Baru Telah Ditambahkan:\n\n" .
                       "Nama: " . $direksi->NAMA . "\n" .
                       "Jabatan: " . $direksi->KET . "\n" .
                       "Nomor Telepon: " . $direksi->NO_TLPN . "\n\n" .
                       "Terima kasih.";

            $this->sendWhatsappNotification($direksi->NO_TLPN, $message);

            return redirect()->route('humas.direksi-humas')->with('success', 'Data direksi berhasil ditambahkan dan notifikasi telah dikirim.');

        } catch (\Exception $e) {
            return redirect()->route('humas.direksi-humas')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateDireksiHumas(Request $request, Direksi $direksi)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'no_tlpn' => 'required|string|max:20|unique:complaint_direksi,NO_TLPN,' . $direksi->ID_DIREKSI . ',ID_DIREKSI',
            'ket' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $direksi->update([
                'NAMA' => $request->nama,
                'NO_TLPN' => $request->no_tlpn,
                'KET' => $request->ket,
            ]);

            $message = "Data Direksi Telah Diperbarui:\n\n" .
                       "Nama: " . $direksi->NAMA . "\n" .
                       "Jabatan: " . $direksi->KET . "\n" .
                       "Nomor Telepon: " . $direksi->NO_TLPN . "\n\n" .
                       "Terima kasih.";

            $this->sendWhatsappNotification($direksi->NO_TLPN, $message);

            return redirect()->route('humas.direksi-humas')->with('success', 'Data direksi berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->route('humas.direksi-humas')->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroyDireksiHumas(Direksi $direksi)
    {
        try {
            $namaDihapus = $direksi->NAMA;
            $noTlpDihapus = $direksi->NO_TLPN;

            $direksi->delete();

            $message = "PEMBERITAHUAN:\n\n" .
                       "Data Direksi berikut telah dihapus dari sistem:\n" .
                       "Nama: " . $namaDihapus;

            $this->sendWhatsappNotification($noTlpDihapus, "PEMBERITAHUAN: Data Anda sebagai direksi dengan nama " . $namaDihapus . " telah dihapus dari sistem.");


            return redirect()->route('humas.direksi-humas')->with('success', 'Data direksi berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('humas.direksi-humas')->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
