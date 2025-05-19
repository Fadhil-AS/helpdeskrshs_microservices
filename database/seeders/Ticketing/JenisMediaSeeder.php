<?php

namespace Database\Seeders\Ticketing;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JenisMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tanggal = Carbon::now()->format('Ymd');
        $listMedia = [
            'SMS Hotline',
            'Kotak Saran',
            'Email',
            'Instagram',
            'Humas',
            'Facebook',
            'Kode Qr',
            'Customer Service',
            'Kontak Center',
            'Website',
            'Twitter',
            'P3JKN',
            'WhatsApp',
            'Media Massa',
            'Lain_lain',
            'Website Helpdesk',
        ];

        foreach ($listMedia as $index => $media) {
            DB::connection('mysql')->table('JENIS_MEDIA')->insert([
                'ID_JENIS_MEDIA' => $tanggal . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                'JENIS_MEDIA' => $media,
                'STATUS' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
