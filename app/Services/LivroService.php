<?php

namespace App\Services;

use App\DTOs\LivroDTO;
use App\Models\Livro;
use Illuminate\Support\Facades\DB;

class LivroService
{
    public function criarLivro(LivroDTO $dto): Livro
    {
        return DB::transaction(function () use ($dto) {
            $livro = Livro::create($dto->toArray());

            // Sincroniza relacionamentos pivô N:N
            $livro->autors()->sync($dto->autoresIds);
            $livro->assuntos()->sync($dto->assuntosIds);

            return $livro;
        });
    }

    public function atualizarLivro(Livro $livro, LivroDTO $dto): Livro
    {
        return DB::transaction(function () use ($livro, $dto) {
            $livro->update($dto->toArray());

            $livro->autors()->sync($dto->autoresIds);
            $livro->assuntos()->sync($dto->assuntosIds);

            return $livro;
        });
    }

    public function deletarLivro(Livro $livro): bool
    {
        return DB::transaction(fn() => $livro->delete());
    }
}
