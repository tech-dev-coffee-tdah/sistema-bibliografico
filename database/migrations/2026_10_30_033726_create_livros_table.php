<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('livros', function (Blueprint $table) {
            $table->id('Codl');
            $table->string('titulo', 40);
            $table->string('editora', 40);
            $table->integer('edicao');
            $table->string('ano_publicacao', 4);
            $table->decimal('valor', 10, 2);
            $table->timestamps();
            $table->softDeletes();  // Adiciona a coluna deleted_at para soft deletes

            // Índices de Alta Performance
            $table->index('deleted_at'); // Acelera o filtro automático do SoftDeletes
            $table->index('titulo');     // Acelera a ordenação e busca por título
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livros');
    }
};
