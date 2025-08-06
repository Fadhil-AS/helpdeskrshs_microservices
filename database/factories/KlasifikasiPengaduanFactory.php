<?php

namespace Database\Factories;

use App\Services\Ticketing\Models\KlasifikasiPengaduan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Services\Ticketing\Models\KlasifikasiPengaduan>
 */
class KlasifikasiPengaduanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = KlasifikasiPengaduan::class;
    public function definition(): array
    {
        return [
            'ID_KLASIFIKASI' => $this->faker->unique()->bothify('K##'),
            'KLASIFIKASI_PENGADUAN' => $this->faker->words(2, true),
            'STATUS' => '1',
        ];
    }
}
