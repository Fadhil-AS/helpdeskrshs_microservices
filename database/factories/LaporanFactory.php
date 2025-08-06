<?php

namespace Database\Factories;

use App\Services\Ticketing\Models\Laporan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Services\Ticketing\Models\Laporan>
 */
class LaporanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Laporan::class;
    public function definition(): array
    {
        return [
            'JENIS_PELAPOR' => 'Pasien',
            'NAME' => $this->faker->name,
            'NO_TLPN' => $this->faker->phoneNumber,
            'ISI_COMPLAINT' => $this->faker->paragraph,
            'TGL_COMPLAINT' => now(),
            'STATUS' => 'Open',
            'TGL_INSROW' => now(),
        ];
    }
}
