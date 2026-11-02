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
            $table->foreignId('livro_codl')->constrained('livros', 'Codl')->onDelete('cascade');
            $table->foreignId('assunto_codas')->constrained('assuntos', 'CodAs')->onDelete('cascade');
            $table->primary(['livro_codl', 'assunto_codas']);
            $table->timestamps();

            // Índices para otimização de performance nas consultas e JOINs da VIEW
            $table->index('livro_codl');
            $table->index('assunto_codas');
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
