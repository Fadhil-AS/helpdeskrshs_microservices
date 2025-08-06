<?php

namespace Database\Factories;

use App\Services\Chatbot\Models\Chatbot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Services\Chatbot\Models\Chatbot>
 */
class ChatbotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Chatbot::class;

    public function definition(): array
    {
        return [
            'data' => $this->faker->paragraph,
            'nama_file' => $this->faker->word . '.xlsx',
        ];
    }
}
