<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // Sintaxe compatível com SQLite (Ambiente de Testes / PHPUnit)
            DB::statement("DROP VIEW IF EXISTS vw_relatorio_livros;");
            DB::statement("
                CREATE VIEW vw_relatorio_livros AS
                SELECT
                    l.Codl AS livro_id,
                    l.titulo,
                    l.editora,
                    l.edicao,
                    l.ano_publicacao,
                    l.valor,
                    COALESCE(group_concat(a.nome, ', '), 'Nenhum Autor') AS autores,
                    COALESCE(group_concat(s.descricao, ', '), 'Nenhum Assunto') AS assuntos
                FROM livros l
                LEFT JOIN livro_autor la ON l.Codl = la.livro_codl
                LEFT JOIN autors a ON la.autor_codau = a.CodAu
                LEFT JOIN livro_assunto ls ON l.Codl = ls.livro_codl
                LEFT JOIN assuntos s ON ls.assunto_codas = s.CodAs
                WHERE l.deleted_at IS NULL
                GROUP BY l.Codl, l.titulo, l.editora, l.edicao, l.ano_publicacao, l.valor
                ORDER BY l.titulo ASC;
            ");
        } else {
            // Sintaxe compatível com MySQL (Desenvolvimento / Produção)
            DB::statement("
                CREATE OR REPLACE VIEW vw_relatorio_livros AS
                SELECT
                    l.Codl AS livro_id,
                    l.titulo,
                    l.editora,
                    l.edicao,
                    l.ano_publicacao,
                    l.valor,
                    COALESCE(GROUP_CONCAT(DISTINCT a.nome ORDER BY a.nome ASC SEPARATOR ', '), 'Nenhum Autor') AS autores,
                    COALESCE(GROUP_CONCAT(DISTINCT s.descricao ORDER BY s.descricao ASC SEPARATOR ', '), 'Nenhum Assunto') AS assuntos
                FROM livros l
                LEFT JOIN livro_autor la ON l.Codl = la.livro_codl
                LEFT JOIN autors a ON la.autor_codau = a.CodAu
                LEFT JOIN livro_assunto ls ON l.Codl = ls.livro_codl
                LEFT JOIN assuntos s ON ls.assunto_codas = s.CodAs
                WHERE l.deleted_at IS NULL
                GROUP BY l.Codl, l.titulo, l.editora, l.edicao, l.ano_publicacao, l.valor
                ORDER BY l.titulo ASC;
            ");
        }
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_relatorio_livros;");
    }
};
