<?php

namespace App\Dtos;

use App\Http\Requests\LivroRequest;

class LivroDTO
{
    public function __construct(
        public readonly string $titulo,
        public readonly string $editora,
        public readonly int $edicao,
        public readonly string $anoPublicacao,
        public readonly float $valor,
        public readonly array $autores = [],
        public readonly array $assuntos = []
    ) {}

    public static function fromRequest(LivroRequest $request): self
    {
        return new self(
            titulo: $request->input('titulo'),
            editora: $request->input('editora'),
            edicao: (int) $request->input('edicao'),
            anoPublicacao: $request->input('ano_publicacao'),

            // Converte diretamente para float, pois o Request já sanitizou para "11.11"
            valor: (float) $request->input('valor'),

            autores: $request->input('autores', []),
            assuntos: $request->input('assuntos', [])
        );
    }

    public function toArray(): array
    {
        return [
            'titulo' => $this->titulo,
            'editora' => $this->editora,
            'edicao' => $this->edicao,
            'ano_publicacao' => $this->anoPublicacao,
            'valor' => $this->valor,
        ];
    }
}
