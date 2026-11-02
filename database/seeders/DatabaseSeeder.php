<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $autors = Autor::factory(10)->create();
        $assuntos = Assunto::factory(20)->create();

        Livro::factory(50)->create()->each(function ($livro) use ($autors, $assuntos) {
            // Relaciona cada livro com 1 a 3 autores e 1 a 2 assuntos (N:N)
            $livro->autors()->attach($autors->random(rand(1, 3))->pluck('CodAu'));
            $livro->assuntos()->attach($assuntos->random(rand(1, 2))->pluck('CodAs'));
        });
    }
}
