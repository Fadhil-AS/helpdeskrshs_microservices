<?php

namespace App\Services\GantiPassword\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait NotifikasiGantiPassword {
    public function sendPasswordChangeSuccessNotification(string $targetPhoneNumber, string $userName): void
    {
        if (substr($targetPhoneNumber, 0, 1) === '0') {
            $targetPhoneNumber = '62' . substr($targetPhoneNumber, 1);
        }

        $apiKey = env('FONNTE_API_TOKEN');
        if (!$apiKey) {
            Log::error('Fonnte: FONNTE_API_TOKEN tidak ditemukan di file .env');
            return;
        }

        $message = "Halo, *{$userName}*! 👋\n\nPassword Anda telah berhasil diperbarui. Anda sekarang dapat login ke sistem helpdesk menggunakan password baru Anda.\n\nTerima kasih.\n\nPengirim\nRumah Sakit Hasan Sadikin";

        $response = Http::withHeaders([
            'Authorization' => $apiKey,
        ])->post('https://api.fonnte.com/send', [
            'target'  => $targetPhoneNumber,
            'message' => $message,
        ]);

        if ($response->failed()) {
            Log::error('Failed to send Fonnte notification.', [
                'target' => $targetPhoneNumber,
                'response' => $response->body()
            ]);
        }
    }
}
