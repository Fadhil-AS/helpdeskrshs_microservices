<?php

namespace Database\Factories;

use App\Services\Ticketing\Models\JenisMedia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Services\Ticketing\Models\JenisMedia>
 */
class JenisMediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = JenisMedia::class;
    public function definition(): array
    {
        return [
            'ID_JENIS_MEDIA' => $this->faker->unique()->bothify('M##'),
            'JENIS_MEDIA' => $this->faker->word,
            'STATUS' => '1',
        ];
    }
}
