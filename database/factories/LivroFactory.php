<?php

namespace Database\Factories;

use App\Models\Livro;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Livro>
 */
class LivroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => substr(fake()->sentence(3), 0, 40),
            'editora' => substr(fake()->company(), 0, 40),
            'edicao' => fake()->numberBetween(1, 10),
            'ano_publicacao' => (string) fake()->numberBetween(1990, 2024),
            'valor' => fake()->randomFloat(2, 25, 250),
        ];
    }
}
