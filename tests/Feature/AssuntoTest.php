<?php

namespace Tests\Feature;

use App\Models\Assunto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Tests\TestCase;

class AssuntoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Desativa apenas a checagem de CSRF, mantendo a Sessão ativa para validar erros e mensagens
        $this->withoutMiddleware([ValidateCsrfToken::class]);
    }

    public function test_pode_listar_assuntos(): void
    {
        Assunto::create(['descricao' => 'Romance']);

        $response = $this->get(route('assuntos.index'));

        $response->assertStatus(200);
        $response->assertViewIs('assuntos.index');
        $response->assertSee('Romance');
    }

    public function test_pode_cadastrar_novo_assunto(): void
    {
        $payload = ['descricao' => 'Ficção Científica'];

        $response = $this->post(route('assuntos.store'), $payload);

        $response->assertRedirect(route('assuntos.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('assuntos', ['descricao' => 'Ficção Científica']);
    }

    public function test_validacao_campo_descricao_obrigatorio(): void
    {
        $response = $this->post(route('assuntos.store'), ['descricao' => '']);

        $response->assertSessionHasErrors(['descricao']);
    }

    public function test_validacao_limite_maximo_20_caracteres_na_descricao(): void
    {
        $descricaoExtensa = str_repeat('A', 21);

        $response = $this->post(route('assuntos.store'), ['descricao' => $descricaoExtensa]);

        $response->assertSessionHasErrors(['descricao']);
    }

    public function test_pode_atualizar_assunto(): void
    {
        $assunto = Assunto::create(['descricao' => 'Drama']);

        $response = $this->put(route('assuntos.update', $assunto), [
            'descricao' => 'Poesia',
        ]);

        $response->assertRedirect(route('assuntos.index'));
        $response->assertSessionHas('success');

        // Valida utilizando a PK customizada CodAs
        $this->assertDatabaseHas('assuntos', [
            'CodAs' => $assunto->CodAs,
            'descricao' => 'Poesia',
        ]);
    }

    public function test_pode_remover_assunto_com_exclusao_logica(): void
    {
        $assunto = Assunto::create(['descricao' => 'História']);

        $response = $this->delete(route('assuntos.destroy', $assunto));

        $response->assertRedirect(route('assuntos.index'));
        $response->assertSessionHas('success');

        // Valida exclusão lógica (Soft Delete) via PK customizada CodAs
        $this->assertSoftDeleted('assuntos', ['CodAs' => $assunto->CodAs]);
    }
}
