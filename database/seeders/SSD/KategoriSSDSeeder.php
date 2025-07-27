<?php

namespace Database\Seeders\SSD;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class KATEGORISSDSeeder extends Seeder{
    public function run(){
        // set koneksi ssd
        $connection = 'ssd';

        // menonaktifkan pengecekan foreign key untuk proses truncate
        DB::connection($connection)->statement('SET FOREIGN_KEY_CHECKS=0;');

        // Mengosongkan tabel anak (`ssd`) terlebih dahulu
        DB::connection($connection)->table('ssd')->truncate();

        // Mengosongkan tabel induk (`kategori_ssd`)
        DB::connection($connection)->table('kategori_ssd')->truncate();

        // mengaktifkan kembali pengecekan foreign key
        DB::connection($connection)->statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::now();
        $kategori = [
            [
                'ID_KATEGORI_SSD' => 1,
                'NAMA_KATEGORI' => 'Pelacakan dan Status Laporan',
                'DESKRIPSI' => 'Anda dapat memantau perkembangan laporan Anda secara langsung melalui sistem.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ID_KATEGORI_SSD' => 2,
                'NAMA_KATEGORI' => 'Proses dan Waktu Penanganan',
                'DESKRIPSI' => 'Di sini Anda dapat mengetahui estimasi waktu penanganan laporan Anda serta berapa lama waktu yang dibutuhkan hingga masalah diselesaikan.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ID_KATEGORI_SSD' => 3,
                'NAMA_KATEGORI' => 'Perubahan dan Tindak Lanjut Laporan',
                'DESKRIPSI' => 'Jika Anda merasa penyelesaian yang diberikan belum sesuai harapan, kami menyediakan informasi mengenai cara mengajukan keluhan lanjutan.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ID_KATEGORI_SSD' => 4,
                'NAMA_KATEGORI' => 'Keamanan dan Privasi',
                'DESKRIPSI' => 'Privasi dan keamanan data Anda adalah prioritas kami. Kategori ini menjelaskan bagaimana data Anda disimpan dan dilindungi saat menggunakan layanan helpdesk.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::connection('ssd')->table('kategori_ssd')->insert($kategori);
    }
}
