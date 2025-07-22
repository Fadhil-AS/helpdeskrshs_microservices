<?php

namespace Database\Seeders\Humas;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class HumasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $plainPassword = 'SuHuTest12345';
        $hashedPassword = md5($plainPassword);

        DB::connection('mysql')->table('HUMAS')->insert([
            'USERNAME'       => 'SuHu',
            'PASSWORD'       => $hashedPassword,
            'NIP'            => '1234567890',
            'NAMA'           => 'RSHS SuHu',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }
}
