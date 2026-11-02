<?php

namespace Tests\Unit\Services;

use App\Dtos\LivroDTO;
use App\Models\Livro;
use App\Models\Autor;
use App\Models\Assunto;
use App\Services\LivroService;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class LivroServiceTest extends TestCase
{
    use RefreshDatabase; // Permite testar operações com Eloquent/Transactions no SQLite em memória

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Teste de criação de livro e sincronização de relacionamentos.
     */
    public function test_deve_criar_livro_e_sincronizar_autores_e_assuntos(): void
    {
        // Cria os registros de dependência (Autores e Assuntos) no banco SQLite em memória
        $autor1 = Autor::create(['nome' => 'Erich Gamma']);
        $autor2 = Autor::create(['nome' => 'Richard Helm']);
        $assunto = Assunto::create(['descricao' => 'Engenharia de Software']);

        $dto = new LivroDTO(
            'Design Patterns',
            'Bookman',
            1,
            '2000',
            120.50,
            [$autor1->CodAu, $autor2->CodAu], // IDs reais gerados no banco
            [$assunto->CodAs]                // ID real gerado no banco
        );

        $service = new LivroService();

        $livro = $service->criarLivro($dto);

        $this->assertInstanceOf(Livro::class, $livro);
        $this->assertEquals('Design Patterns', $livro->titulo);

        // Valida a persistência na tabela de livros
        $this->assertDatabaseHas('livros', [
            'Codl' => $livro->Codl,
            'titulo' => 'Design Patterns',
        ]);

        // Valida a sincronização das tabelas pivô
        $this->assertDatabaseHas('livro_autor', [
            'livro_codl' => $livro->Codl,
            'autor_codau' => $autor1->CodAu,
        ]);

        $this->assertDatabaseHas('livro_assunto', [
            'livro_codl' => $livro->Codl,
            'assunto_codas' => $assunto->CodAs,
        ]);
    }

    /**
     * Teste de atualização de livro utilizando Mocks tipados com BelongsToMany.
     */
    public function test_deve_atualizar_livro_e_resincronizar_relacionamentos(): void
    {
        $dto = new LivroDTO(
            'Refactoring 2nd Ed',
            'Novatec',
            2,
            '2018',
            140.00,
            [1],
            [2, 4]
        );

        $livroMock = Mockery::mock(Livro::class);
        $livroMock->shouldReceive('update')
            ->once()
            ->with([
                'titulo' => 'Refactoring 2nd Ed',
                'editora' => 'Novatec',
                'edicao' => 2,
                'ano_publicacao' => '2018',
                'valor' => 140.00,
            ])
            ->andReturn(true);

        // Especifica o tipo BelongsToMany::class para respeitar o type hint do modelo Livro
        $autorsRelationMock = Mockery::mock(BelongsToMany::class);
        $autorsRelationMock->shouldReceive('sync')->once()->with([1])->andReturn([]);
        $livroMock->shouldReceive('autors')->once()->andReturn($autorsRelationMock);

        $assuntosRelationMock = Mockery::mock(BelongsToMany::class);
        $assuntosRelationMock->shouldReceive('sync')->once()->with([2, 4])->andReturn([]);
        $livroMock->shouldReceive('assuntos')->once()->andReturn($assuntosRelationMock);

        $service = new LivroService();

        $service->atualizarLivro($livroMock, $dto);

        $this->assertTrue(true);
    }

    /**
     * Teste de deleção lógica do livro.
     */
    public function test_deve_deletar_livro(): void
    {
        $livroMock = Mockery::mock(Livro::class);
        $livroMock->shouldReceive('delete')->once()->andReturn(true);

        $service = new LivroService();

        $service->deletarLivro($livroMock);

        $this->assertTrue(true);
    }
}
