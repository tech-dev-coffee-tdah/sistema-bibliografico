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
            $table->foreignId('livro_id')->constrained('livros')->cascadeOnDelete();
            $table->foreignId('autor_id')->constrained('autors')->cascadeOnDelete();
            $table->primary(['livro_id', 'autor_id']);

            // Índices para otimização de performance nas consultas e JOINs da VIEW
            $table->index('livro_id');
            $table->index('autor_id');
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
