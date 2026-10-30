<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW vw_relatorio_livros AS
            SELECT
                l.id AS livro_id,
                l.titulo,
                l.editora,
                l.edicao,
                l.ano_publicacao,
                l.valor,
                COALESCE(GROUP_CONCAT(DISTINCT a.nome ORDER BY a.nome ASC SEPARATOR ', '), 'Nenhum Autor') AS autores,
                COALESCE(GROUP_CONCAT(DISTINCT s.descricao ORDER BY s.descricao ASC SEPARATOR ', '), 'Nenhum Assunto') AS assuntos
            FROM livros l
            LEFT JOIN livro_autor la ON l.id = la.livro_id
            LEFT JOIN autors a ON la.autor_id = a.id
            LEFT JOIN livro_assunto ls ON l.id = ls.livro_id
            LEFT JOIN assuntos s ON ls.assunto_id = s.id
            GROUP BY l.id, l.titulo, l.editora, l.edicao, l.ano_publicacao, l.valor
            ORDER BY l.titulo ASC;
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_relatorio_livros;");
    }
};
