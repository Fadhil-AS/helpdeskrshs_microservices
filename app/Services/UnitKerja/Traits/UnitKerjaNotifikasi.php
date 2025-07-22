<?php

namespace App\Services\UnitKerja\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait UnitKerjaNotifikasi
{
    public function sendWhatsappNotification(string $target, string $message): void
    {
        $token = env('FONNTE_API_TOKEN');

        if (!$token) {
            Log::error('Fonnte API token tidak ditemukan di file .env');
            return;
        }

        try {
            Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
            ]);

            Log::info('Notifikasi WhatsApp berhasil dikirim ke: ' . $target);

        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi WhatsApp Fonnte: ' . $e->getMessage());
        }
    }
}
