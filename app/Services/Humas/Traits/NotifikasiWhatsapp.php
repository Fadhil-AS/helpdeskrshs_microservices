<?php

namespace App\Services\Humas\Traits;

use App\Services\Ticketing\Models\Laporan;
use App\Services\Humas\Models\Humas;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait NotifikasiWhatsApp
{
    private function kirimPesanWA(string $target, string $message): void
    {
        $token = env('FONNTE_API_TOKEN');
        if (!$token) {
            Log::error('Fonnte API token (FONNTE_API_TOKEN) tidak ditemukan di .env');
            return;
        }

        try {
            $response = Http::withHeaders(['Authorization' => $token])
                ->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $message,
                    'countryCode' => '62',
                ]);

            if ($response->successful()) {
                Log::info('Notifikasi WhatsApp berhasil dikirim ke: ' . $target);
            } else {
                Log::error('Gagal mengirim notifikasi WhatsApp ke: ' . $target, [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception saat mengirim notifikasi Fonnte: ' . $e->getMessage());
        }
    }

    public function kirimNotifikasiKePelapor(Laporan $laporan): void
    {
        if (empty($laporan->NO_TLPN)) {
            Log::warning("Notifikasi dibatalkan: Tidak ada nomor telepon pelapor untuk Laporan ID: " . $laporan->ID_COMPLAINT);
            return;
        }

        $pesan = $this->buatPesanUntukPelapor($laporan);
        if ($pesan) {
            $this->kirimPesanWA($laporan->NO_TLPN, $pesan);
        }
    }

    public function kirimNotifikasiKeHumas(Laporan $laporan, string $konteks, array $detail = []): void
    {
        $humas = Humas::first();
        if (!$humas || empty($humas->no_tlpn_humas)) {
            Log::error("Notifikasi ke Humas gagal: Nomor telepon Humas tidak ditemukan.");
            return;
        }

        $pesan = $this->buatPesanUntukHumas($laporan, $konteks, $detail);
        if ($pesan) {
            $this->kirimPesanWA($humas->no_tlpn_humas, $pesan);
        }
    }

    public function kirimNotifikasiStatusKeHumas(Laporan $laporan): void
    {
        $humas = Humas::first();
        if (!$humas || empty($humas->no_tlpn_humas)) {
            Log::error("Notifikasi Status ke Humas gagal: Nomor telepon Humas tidak ditemukan.");
            return;
        }

        $pesan = $this->buatPesanStatusUntukHumas($laporan);
        if ($pesan) {
            $this->kirimPesanWA($humas->no_tlpn_humas, $pesan);
        }
    }


    private function buatPesanUntukPelapor(Laporan $laporan): ?string
    {
        $namaPelapor = $laporan->NAME;
        $idLaporan = $laporan->ID_COMPLAINT;
        $judulLaporan = $laporan->JUDUL_COMPLAINT ?? substr($laporan->ISI_COMPLAINT, 0, 30) . ' ';
        $urlLacak = route('ticketing.lacak', ['id_complaint' => $idLaporan]);
        $pesanHeader = "Yth.\nBapak/Ibu *" . $namaPelapor . "*,\n\n";
        $pesanFooter = "\n\nUntuk melacak status laporan Anda, silakan kunjungi link berikut:\n" . $urlLacak ."\n\nTerima kasih atas kepercayaan Anda.\n\n*RSUP Hasan Sadikin Bandung*";
        $pesanBody = "";

        switch ($laporan->STATUS) {
            case 'Open':
                if (!empty($laporan->ID_COMPLAINT_REFERENSI)) {
                    $pesanBody = "Terima kasih, laporan banding Anda terkait tiket sebelumnya (*" . $laporan->ID_COMPLAINT_REFERENSI . "*) telah kami terima dengan nomor tiket baru *" . $idLaporan . "*. Kami akan segera meninjaunya kembali.";
                } else {
                    $pesanBody = "Terima kasih, laporan Anda terkait *" . $judulLaporan . "* dengan nomor tiket *" . $idLaporan . "* telah kami terima dan akan segera kami proses.";
                }
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

    private function buatPesanUntukHumas(Laporan $laporan, string $konteks, array $detail = []): ?string
    {
        $idLaporan = $laporan->ID_COMPLAINT;
        $pesanHeader = "*Notifikasi Sistem Helpdesk* 🔔\n\n";
        $pesanBody = "";

        switch ($konteks) {
            case 'laporan_baru':
                $klasifikasi = $laporan->klasifikasiPengaduan->KLASIFIKASI_PENGADUAN ?? 'Tidak diketahui';
                $pesanBody = "Laporan baru telah masuk dengan ID: *" . $idLaporan . "*.\n\n" .
                             "Klasifikasi: *" . $klasifikasi . "*\n" .
                             "Oleh: " . $laporan->NAME . "\n\n" .
                             "Silakan periksa dan proses laporan ini di dashboard Humas.";
                break;
            case 'klarifikasi_unit':
                $namaUnit = $detail['nama_unit'] ?? 'Unit Kerja';
                $pesanBody = "Unit Kerja *" . $namaUnit . "* telah memberikan klarifikasi untuk laporan ID: *" . $idLaporan . "*.\n\n" .
                             "Mohon periksa tindak lanjut yang diperlukan di dashboard Humas.";
                break;
            default:
                return null;
        }
        return $pesanHeader . $pesanBody;
    }

    private function buatPesanStatusUntukHumas(Laporan $laporan): ?string
    {
        $idLaporan = $laporan->ID_COMPLAINT;
        $status = $laporan->STATUS;
        $judulLaporan = $laporan->JUDUL_COMPLAINT ?? substr($laporan->ISI_COMPLAINT, 0, 30) . ' ';
        $urlDashboard = route('humas.pelaporan-humas');

        $pesanHeader = "*Update Status Laporan Internal* ⚙️\n\n";
        $pesanFooter = "\n\nLihat detail di dashboard:\n" . $urlDashboard;
        $pesanBody = "";

        switch ($status) {
            case 'On Progress':
                $pesanBody = "Laporan ID *{$idLaporan}* terkait *'{$judulLaporan}'* telah diubah statusnya menjadi *On Progress*.";
                break;
            case 'Menunggu Konfirmasi':
                $pesanBody = "Laporan ID *{$idLaporan}* telah selesai ditindaklanjuti dan statusnya diubah menjadi *Menunggu Konfirmasi* dari pelapor.";
                break;
            case 'Close':
                $pesanBody = "Laporan ID *{$idLaporan}* telah ditutup.";
                break;
            case 'Banding':
                $pesanBody = "Pelapor mengajukan banding untuk laporan ID *{$idLaporan}*. Status diubah menjadi *Banding* dan laporan dibuka kembali untuk peninjauan.";
                break;
            default:
                return null;
        }
        return $pesanHeader . $pesanBody . $pesanFooter;
    }
}
