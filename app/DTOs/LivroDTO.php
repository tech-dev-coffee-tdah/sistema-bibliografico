<?php

namespace App\DTOs;

use App\Http\Requests\LivroRequest;

class LivroDTO
{
    public function __construct(
        public readonly string $titulo,
        public readonly string $editora,
        public readonly int $edicao,
        public readonly string $anoPublicacao,
        public readonly float $valor,
        public readonly array $autoresIds,
        public readonly array $assuntosIds,
    ) {}

    public static function fromRequest(LivroRequest $request): self
    {
        return new self(
            titulo: $request->validated('titulo'),
            editora: $request->validated('editora'),
            edicao: (int) $request->validated('edicao'),
            anoPublicacao: $request->validated('ano_publicacao'),
            valor: (float) $request->validated('valor'),
            autoresIds: $request->validated('autors', []),
            assuntosIds: $request->validated('assuntos', []),
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
