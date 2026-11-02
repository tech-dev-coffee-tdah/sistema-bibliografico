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
        Schema::create('livro_autor', function (Blueprint $table) {
            $table->foreignId('livro_codl')->constrained('livros', 'Codl')->onDelete('cascade');
            $table->foreignId('autor_codau')->constrained('autors', 'CodAu')->onDelete('cascade');
            $table->primary(['livro_codl', 'autor_codau']);
            $table->timestamps();

            // Índices para otimização de performance nas consultas e JOINs da VIEW
            $table->index('livro_codl');
            $table->index('autor_codau');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livro_autor');
    }
};
