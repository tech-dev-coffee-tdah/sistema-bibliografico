<?php

namespace Tests\Feature;

use App\Models\Autor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Tests\TestCase;

class AutorTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_listar_autores(): void
    {
        Autor::create(['nome' => 'Machado de Assis']);

        $response = $this->get(route('autores.index'));

        $response->assertStatus(200);
        $response->assertViewIs('autores.index');
        $response->assertSee('Machado de Assis');
    }

    public function test_pode_cadastrar_novo_autor(): void
    {
        $payload = ['nome' => 'Clarice Lispector'];

        $response = $this->post(route('autores.store'), $payload);

        $response->assertRedirect(route('autores.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('autors', ['nome' => 'Clarice Lispector']);
    }

    public function test_validacao_campo_nome_obrigatorio(): void
    {
        $response = $this->post(route('autores.store'), ['nome' => '']);

        $response->assertSessionHasErrors(['nome']);
    }

    public function test_validacao_limite_maximo_40_caracteres_no_nome(): void
    {
        $nomeExtenso = str_repeat('A', 41);

        $response = $this->post(route('autores.store'), ['nome' => $nomeExtenso]);

        $response->assertSessionHasErrors(['nome']);
    }

    public function test_pode_atualizar_autor(): void
    {
        $autor = Autor::create(['nome' => 'Nome Antigo']);

        $response = $this->put(route('autores.update', $autor), [
            'nome' => 'Nome Atualizado',
        ]);

        $response->assertRedirect(route('autores.index'));
        $response->assertSessionHas('success');

        // Valida utilizando a PK customizada CodAu
        $this->assertDatabaseHas('autors', [
            'CodAu' => $autor->CodAu,
            'nome' => 'Nome Atualizado',
        ]);
    }

    public function test_pode_remover_autor_com_exclusao_logica(): void
    {
        $autor = Autor::create(['nome' => 'Autor a Remover']);

        $response = $this->delete(route('autores.destroy', $autor));

        $response->assertRedirect(route('autores.index'));
        $response->assertSessionHas('success');

        // Valida exclusão lógica (Soft Delete) via PK customizada CodAu
        $this->assertSoftDeleted('autors', ['CodAu' => $autor->CodAu]);
    }
}
