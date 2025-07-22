<?php

namespace app\Services\Humas\Traits;

use App\Services\Ticketing\Models\Laporan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait NotifikasiWhatsappPelapor
{
    public function kirimNotifikasiStatusKePelapor(Laporan $laporan)
    {
        // dd('FUNGSI NOTIFIKASI PENGADUAN TERPANGGIL', $laporan->toArray());
        try {
            $penerima = $laporan->NO_TLPN;

            if (empty($penerima)) {
                Log::warning("Tidak ada nomor telepon untuk laporan ID: " . $laporan->ID_COMPLAINT);
                return;
            }

            $pesan = $this->buatPesanStatus($laporan);

            if ($pesan) {
                $this->kirimPesanWA($penerima, $pesan);
            }

        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi WA ke pelapor: ' . $e->getMessage());
        }
    }

    private function buatPesanStatus(Laporan $laporan): ?string
    {
        $namaPelapor = $laporan->NAME;
        $idLaporan = $laporan->ID_COMPLAINT;
        $judulLaporan = $laporan->JUDUL_COMPLAINT;
        $urlLacak = route('ticketing.lacak', ['id_complaint' => $idLaporan]);
        $pesanHeader = "Yth.\nBapak/Ibu *" . $namaPelapor . "*,\n\n";
        $pesanFooter = "\n\nUntuk melacak status laporan Anda, silakan kunjungi link berikut:\n" . $urlLacak ."\n\nTerima kasih atas kepercayaan Anda kepada layanan kami"."\n\nPengirim\nRumah Sakit Hasan Sadikin Bandung ";
        $pesanBody = "";

        switch ($laporan->STATUS) {
            case 'Open':
                $pesanBody = "Terima kasih, laporan dengan nomor tiket *" . $idLaporan . "* telah kami terima dan akan segera kami proses.";
                break;
            case 'On Progress':
                $pesanBody = "Update Laporan [" . $idLaporan . "]: Laporan Anda terkait *" . $judulLaporan . "* saat ini sedang dalam proses penanganan oleh tim kami.";
                break;
            case 'Menunggu Konfirmasi':
                $pesanBody = "Update Laporan [" . $idLaporan . "]: Tindak lanjut untuk laporan Anda terkait *" . $judulLaporan . "* telah selesai kami lakukan. Mohon berikan konfirmasi Anda agar laporan dapat kami tutup.";
                break;
            case 'Close':
                $pesanBody = "Update Laporan [" . $idLaporan . "]: Laporan Anda terkait *" . $judulLaporan . "* telah selesai ditangani dan kami tutup. Kami sangat menghargai masukan yang Anda berikan.";
                break;
            case 'Banding':
                $pesanBody = "Update Laporan [" . $idLaporan . "]: Pengajuan banding Anda untuk laporan *" . $judulLaporan . "* telah kami terima dan akan segera kami tinjau kembali.";
                break;
            default:
                return null;
        }

        return $pesanHeader . $pesanBody . $pesanFooter;
    }

    private function kirimPesanWA($target, $message)
    {
        $token = env('FONNTE_API_TOKEN');
        $apiUrl = env('FONNTE_API_URL');

        if (!$token || !$apiUrl) {
            Log::error('Konfigurasi API WhatsApp tidak ditemukan di .env');
            return;
        }

        Http::withHeaders([
            'Authorization' => $token
        ])->post($apiUrl, [
            'target' => $target,
            'message' => $message,
            'countryCode' => '62',
        ]);

        Log::info('Notifikasi status terkirim ke pelapor: ' . $target);
    }
}
