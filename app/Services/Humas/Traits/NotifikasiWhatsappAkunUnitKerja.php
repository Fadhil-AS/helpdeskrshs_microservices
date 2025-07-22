<?php

namespace App\Services\Humas\Traits;

use App\Services\Ticketing\Models\UserComplaint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait NotifikasiWhatsappAkunUnitKerja{
    public function sendNewUserNotification(UserComplaint $user): void
    {
        $message = "Yth. {$user->NAME},\n\n";
        $message .= "Akun admin Anda telah berhasil dibuat.\n\n";
        $message .= "Berikut adalah detail login Anda:\n";
        $message .= "Username: *{$user->USERNAME}*\n";
        $message .= "Password: *{$user->PASSWORD_REAL}*\n\n";
        $message .= "Harap segera ganti password Anda setelah login untuk keamanan.\n Terima kasih.\n\n";
        $message .= "Pengirim\nRumah Sakit Hasan Sadikin Bandung";

        $this->sendWhatsApp($user->NO_TLPN, $message);
    }

    public function sendProfileUpdateNotification(UserComplaint $user): void
    {
        $message = "Yth. {$user->NAME},\n\n";
        $message .= "Data pada akun '{$user->USERNAME}' Anda telah berhasil diperbarui.\n\n";
        $message .= "Jika Anda merasa tidak melakukan perubahan ini, harap segera hubungi administrator sistem.\n Terima kasih.\n\n";
        $message .= "Pengirim\nRumah Sakit Hasan Sadikin Bandung";

        $this->sendWhatsApp($user->NO_TLPN, $message);
    }

    public function sendAccountDeletionNotification(UserComplaint $user): void
    {
        $message = "Yth. {$user->NAME},\n\n";
        $message .= "Akun '{$user->USERNAME}' Anda telah dihapus dari sistem.\n\n";
        $message .= "Anda tidak akan dapat login kembali menggunakan akun ini.\n Terima kasih atas kontribusi Anda.\n\n";
        $message .= "Pengirim\nRumah Sakit Hasan Sadikin Bandung";

        $this->sendWhatsApp($user->NO_TLPN, $message);
    }

    public function sendPasswordResetNotification(UserComplaint $user, string $newPassword): void
    {
        $message = "Yth. {$user->NAME},\n\n";
        $message .= "Password untuk akun '{$user->USERNAME}' Anda telah berhasil direset.\n\n";
        $message .= "Password baru Anda adalah: *{$newPassword}*\n\n";
        $message .= "Harap segera ganti password Anda setelah login untuk keamanan.\n Terima kasih.\n\n";
        $message .= "Pengirim\nRumah Sakit Hasan Sadikin Bandung";

        $this->sendWhatsApp($user->NO_TLPN, $message);
    }

    private function sendWhatsApp(string $target, string $message): void
    {
        $token = env('FONNTE_API_TOKEN');

        if (!$token) {
            Log::error('Fonnte: FONNTE_API_TOKEN tidak ditemukan di file .env');
            return;
        }

        $formattedTarget = $this->formatPhoneNumber($target);
        if (!$formattedTarget) {
            Log::warning("Fonnte: Nomor telepon tidak valid -> {$target}");
            return;
        }

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->post('https://api.fonnte.com/send', [
            'target' => $formattedTarget,
            'message' => $message,
            'countryCode' => '62',
        ]);

        if ($response->failed()) {
            Log::error('Fonnte: Gagal mengirim pesan.', [
                'target' => $formattedTarget,
                'response' => $response->body()
            ]);
        }
    }

    private function formatPhoneNumber(string $number): ?string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $number);
        if (substr($cleaned, 0, 1) == '0') {
            return '62' . substr($cleaned, 1);
        }

        if (substr($cleaned, 0, 2) == '62') {
            return $cleaned;
        }
        return null;
    }

}
