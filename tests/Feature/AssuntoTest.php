<?php

namespace Tests\Feature;

use App\Models\Assunto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssuntoTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_listar_assuntos(): void
    {
        Assunto::create(['descricao' => 'Romance']);

        $response = $this->get(route('assuntos.index'));

        $response->assertStatus(200);
        $response->assertSee('Romance');
    }

    public function test_pode_cadastrar_novo_assunto(): void
    {
        $payload = ['descricao' => 'Ficção Científica'];

        $response = $this->post(route('assuntos.store'), $payload);

        $this->assertDatabaseHas('assuntos', ['descricao' => 'Ficção Científica']);
        $response->assertSessionHas('success');
        $response->assertRedirect(route('assuntos.index'));
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

        $this->assertDatabaseHas('assuntos', [
            'CodAs' => $assunto->CodAs,
            'descricao' => 'Poesia',
        ]);
        $response->assertSessionHas('success');
        $response->assertRedirect(route('assuntos.index'));
    }

    public function test_pode_remover_assunto_com_exclusao_logica(): void
    {
        $assunto = Assunto::create(['descricao' => 'História']);

        $response = $this->delete(route('assuntos.destroy', $assunto));

        $this->assertSoftDeleted('assuntos', ['CodAs' => $assunto->CodAs]);
        $response->assertSessionHas('success');
        $response->assertRedirect(route('assuntos.index'));
    }
}
