<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\Ticketing\KlasifikasiPengaduanSeeder;
use Database\Seeders\Ticketing\JenisMediaSeeder;
use Database\Seeders\Ticketing\PenyelesaianPengaduanSeeder;
use Database\Seeders\Ticketing\JenisLaporanSeeder;
use Database\Seeders\Unit_kerja\UnitKerjaSeeder;
use Database\Seeders\Ticketing\UserComplaintSeeder;
use Database\Seeders\Ticketing\ComplaintDireksiSeeder;
use Database\Seeders\Ticketing\DataComplaintSeeder;
use Database\Seeders\Humas\HumasSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            KlasifikasiPengaduanSeeder::class,
            JenisMediaSeeder::class,
            PenyelesaianPengaduanSeeder::class,
            JenisLaporanSeeder::class,
            UnitKerjaSeeder::class,
            UserComplaintSeeder::class,
            ComplaintDireksiSeeder::class,
            DataComplaintSeeder::class,
            HumasSeeder::class,
        ]);
    }
}
