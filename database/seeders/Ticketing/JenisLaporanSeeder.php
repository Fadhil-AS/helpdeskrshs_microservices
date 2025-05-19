<?php

namespace Database\Seeders\Ticketing;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JenisLaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tanggal = Carbon::now()->format('Ymd');
        DB::connection('mysql')->table('JENIS_LAPORAN')->insert([
            [
                'ID_JENIS_LAPORAN' => $tanggal . str_pad(1, 6, '0', STR_PAD_LEFT),
                'JENIS_LAPORAN' => 'Apresiasi',
                'STATUS' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ID_JENIS_LAPORAN' => $tanggal . str_pad(2, 6, '0', STR_PAD_LEFT),
                'JENIS_LAPORAN' => 'Keluhan',
                'STATUS' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ID_JENIS_LAPORAN' => $tanggal . str_pad(3, 6, '0', STR_PAD_LEFT),
                'JENIS_LAPORAN' => 'Informasi',
                'STATUS' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ID_JENIS_LAPORAN' => $tanggal . str_pad(4, 6, '0', STR_PAD_LEFT),
                'JENIS_LAPORAN' => 'Pertanyaan',
                'STATUS' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
