<?php

namespace App\Services;

use App\DTOs\LivroDTO;
use App\Models\Livro;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LivroService
{
    public function criarLivro(LivroDTO $dto): Livro
    {
        return DB::transaction(function () use ($dto) {
            $livro = Livro::create($dto->toArray());

            if (!empty($dto->autores)) {
                $livro->autors()->sync($dto->autores);
            }

            if (!empty($dto->assuntos)) {
                $livro->assuntos()->sync($dto->assuntos);
            }

            return $livro;
        });
    }

    public function atualizarLivro(Livro $livro, LivroDTO $dto): Livro
    {
        return DB::transaction(function () use ($livro, $dto) {
            $livro->update($dto->toArray());

            $livro->autors()->sync($dto->autores);
            $livro->assuntos()->sync($dto->assuntos);

            return $livro;
        });
    }

    public function deletarLivro(Livro $livro): void
    {
        try {
            DB::transaction(function () use ($livro) {
                $livro->delete();
            });
        } catch (QueryException $e) {
            Log::error("Falha ao realizar soft delete no livro #{$livro->Codl}: " . $e->getMessage());
            throw $e;
        }
    }
}
