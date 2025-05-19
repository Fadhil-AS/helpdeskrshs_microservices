<?php

namespace Database\Seeders\Ticketing;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class UserComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $plainPassword = 'abcdef';
        $hashedPassword = md5($plainPassword);

        DB::connection('mysql')->table('USER_COMPLAINT')->insert([
            'NO_REGISTER'    => '2104_00000200',
            'ID_BAGIAN' => 'A',
            'USERNAME'       => 'telkom',
            'PASSWORD'       => $hashedPassword,
            'NAME'           => 'Mhs telkom',
            'GROUPS'         => null,
            'SPESIAL_CODE'   => null,
            'TGL_INSROW'     => Carbon::createFromFormat('d/m/Y', '21/4/2021'),
            'NIP'            => '123456',
            'VALIDASI'       => 'Y',
            'NO_TLPN'        => $faker->numerify('08##########'),
            'TGL_UPDATE'     => Carbon::createFromFormat('d/m/Y', '15/10/2021'),
            'PASSWORD_REAL'  => $plainPassword,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }
}
