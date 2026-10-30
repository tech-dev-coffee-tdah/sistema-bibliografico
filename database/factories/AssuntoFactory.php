<?php

namespace Database\Factories;

use App\Models\Assunto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Assunto>
 */
class AssuntoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'descricao' => substr(fake()->unique()->word(), 0, 20),
        ];
    }
}
