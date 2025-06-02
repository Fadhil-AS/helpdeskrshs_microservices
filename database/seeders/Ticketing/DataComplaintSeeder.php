<?php

namespace Database\Seeders\Ticketing;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DataComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $idPrefix = Carbon::now()->format('Ym'); // YYYYMM
        $tanggal = Carbon::now()->format('Ymd');
        $idNumber = str_pad(1, 7, '0', STR_PAD_LEFT);

        DB::connection('mysql')->table('data_complaint')->insert([
            'ID_COMPLAINT'          => $idPrefix . '_' . $idNumber,
            'ID_COMPLAINT_REFERENSI'=> $idPrefix . '_' . $idNumber,
            'ID_BAGIAN'             => 'B01',
            'ID_KLASIFIKASI'        => $tanggal . str_pad(1, 6, '0', STR_PAD_LEFT),
            'ID_JENIS_MEDIA'        => $tanggal . str_pad(1, 6, '0', STR_PAD_LEFT),
            'ID_PENYELESAIAN'       => $tanggal . str_pad(1, 6, '0', STR_PAD_LEFT),
            'ID_JENIS_LAPORAN'      => $tanggal . str_pad(1, 6, '0', STR_PAD_LEFT),
            'TGL_COMPLAINT'         => Carbon::createFromFormat('d-m-Y', '01-05-2024'),
            'NAME'                  => $faker->name,
            'NO_TLPN'               => $faker->e164PhoneNumber,
            'TGL_INSROW'            => now(),
            'STATUS'                => 'Open',
            'EVALUASI_COMPLAINT'    => 'Masalah telah diklarifikasi oleh unit.',
            'JUDUL_COMPLAINT'       => 'Layanan medis tidak sesuai prosedur',
            'TGL_EVALUASI'          => Carbon::createFromFormat('d-m-Y', '02-05-2024'),
            'GRANDING'              => 'Merah',
            'PETUGAS_PELAPOR'       => $faker->name,
            'NO_MEDREC'             => 'RM12345678',
            'PENANGGUNG_JAWAB'      => 'dr. Andi',
            'TGL_SELESAI'           => now(),
            'DATA_PENGADUAN'        => 'Pasien menyampaikan keluhan mengenai keterlambatan layanan di IGD.',
            'SMS_DIREKSI'           => 'dirkeu',
            'FILE_PENGADUAN'        => 'pengaduan_20240501.pdf',
            'TINDAK_LANJUT_HUMAS'   => 'Sudah ditindaklanjuti dan mendapat klarifikasi.',
            'DISPOSISI'             => 'Minta Arahan',
            'INFO_DIREKSI'          => 'Akan ditindaklanjuti dalam rapat koordinasi.',
            'PERMASALAHAN'          => 'Keterlambatan layanan medis di IGD.',
            'KD_PENGADUAN'          => 'KDMED001',
            'RATING_LAPORAN'        => 'Masalah Belum Terselesaikan',
            'FEEDBACK_PELAPOR'      => 'Saya merasa tidak puas atas klarifikasinya',
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);
    }
}
