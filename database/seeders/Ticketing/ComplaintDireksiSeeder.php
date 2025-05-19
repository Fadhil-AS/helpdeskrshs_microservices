<?php

namespace Database\Seeders\Ticketing;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ComplaintDireksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $dataDireksi = [
            [
                'NAMA'    => 'Direktur Utama',
                'NO_TLPN' => $faker->numerify('08##########'),
                'KET'     => 'dirut',
            ],
            [
                'NAMA'    => 'Direktur Medik dan Keperawatan',
                'NO_TLPN' => $faker->numerify('08##########'),
                'KET'     => 'dirmed',
            ],
            [
                'NAMA'    => 'Direktur SDM, Pendidikan, dan Penelitian',
                'NO_TLPN' => $faker->numerify('08##########'),
                'KET'     => 'dirsdm',
            ],
            [
                'NAMA'    => 'Direktur Perencanaan dan Keuangan',
                'NO_TLPN' => $faker->numerify('08##########'),
                'KET'     => 'dirkeu',
            ],
            [
                'NAMA'    => 'Direktur Layanan Operasional',
                'NO_TLPN' => $faker->numerify('08##########'),
                'KET'     => 'dirum',
            ],
        ];

        foreach ($dataDireksi as $index => $row) {
            DB::connection('mysql')->table('COMPLAINT_DIREKSI')->insert([
                'ID_DIREKSI' => (string)($index + 1),
                'NAMA'       => $row['NAMA'],
                'NO_TLPN'    => $row['NO_TLPN'],
                'KET'        => $row['KET'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
