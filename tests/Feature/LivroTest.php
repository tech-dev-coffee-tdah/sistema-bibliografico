<?php

namespace Tests\Feature;

use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivroTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function deve_listar_livros_na_pagina_inicial()
    {
        $response = $this->get('/livros');

        $response->assertStatus(200);
        $response->assertViewIs('livros.index');
    }

    /** @test */
    public function deve_cadastrar_um_livro_com_sucesso()
    {
        $autor = Autor::factory()->create(['nome' => 'Autor Teste']);
        $assunto = Assunto::factory()->create(['descricao' => 'Assunto Teste']);

        $payload = [
            'titulo' => 'Livro de Teste Automatizado',
            'editora' => 'Editora Teste',
            'edicao' => 1,
            'ano_publicacao' => '2026',
            'valor' => '150,50',
            'autores' => [$autor->id],
            'assuntos' => [$assunto->id],
        ];

        $response = $this->post('/livros', $payload);

        $response->assertRedirect('/livros');
        $this->assertDatabaseHas('livros', [
            'titulo' => 'Livro de Teste Automatizado',
            'valor' => 150.50,
        ]);
    }

    /** @test */
    public function nao_deve_cadastrar_livro_com_dados_invalidos()
    {
        $response = $this->post('/livros', [], ['Accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['titulo', 'editora', 'edicao', 'ano_publicacao', 'valor']);
    }

    /** @test */
    public function deve_remover_um_livro_cadastrado()
    {
        $livro = Livro::factory()->create();

        $response = $this->delete("/livros/{$livro->id}");

        $response->assertRedirect('/livros');
        $this->assertDatabaseMissing('livros', ['id' => $livro->id]);
    }

    /** @test */
    public function deve_gerar_o_relatorio_pdf_com_sucesso()
    {
        $response = $this->get('/relatorio/pdf');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
