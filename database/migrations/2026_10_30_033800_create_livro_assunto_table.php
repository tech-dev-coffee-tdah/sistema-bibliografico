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
        Schema::create('livro_assunto', function (Blueprint $table) {
            $table->foreignId('livro_id')->constrained('livros')->cascadeOnDelete();
            $table->foreignId('assunto_id')->constrained('assuntos')->cascadeOnDelete();
            $table->primary(['livro_id', 'assunto_id']);

            // Índices para otimização de performance nas consultas e JOINs da VIEW
            $table->index('livro_id');
            $table->index('assunto_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livro_assunto');
    }
};
