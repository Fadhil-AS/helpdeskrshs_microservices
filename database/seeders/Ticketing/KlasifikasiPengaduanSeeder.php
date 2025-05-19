<?php

namespace Database\Seeders\Ticketing;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KlasifikasiPengaduanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tanggal = Carbon::now()->format('Ymd');

        DB::connection('mysql')->table('KLASIFIKASI_PENGADUAN')->insert([
            [
                'ID_KLASIFIKASI' => $tanggal . str_pad(1, 6, '0', STR_PAD_LEFT),
                'KLASIFIKASI_PENGADUAN' => 'Etik',
                'STATUS' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ID_KLASIFIKASI' => $tanggal . str_pad(2, 6, '0', STR_PAD_LEFT),
                'KLASIFIKASI_PENGADUAN' => 'Sponsorship',
                'STATUS' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ID_KLASIFIKASI' => $tanggal . str_pad(3, 6, '0', STR_PAD_LEFT),
                'KLASIFIKASI_PENGADUAN' => 'Gratifikasi',
                'STATUS' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
