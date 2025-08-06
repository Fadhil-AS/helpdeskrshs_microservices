<?php

namespace Database\Factories;

use App\Services\SSD\Models\KategoriSSD;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Services\SSD\Models\KategoriSSD>
 */
class KategoriSSDFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = KategoriSSD::class;
    private static int $idCounter = 1;

    public function definition(): array
    {
        return [
            'ID_KATEGORI_SSD' => self::$idCounter++,
            'nama_kategori' => $this->faker->words(3, true),
            'deskripsi' => $this->faker->sentence,
        ];
    }
}
