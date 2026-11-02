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
        Schema::create('autors', function (Blueprint $table) {
            $table->id('CodAu');
            $table->string('nome', 40);
            $table->timestamps();
            $table->softDeletes();  // Adiciona a coluna deleted_at para soft deletes

            // Índices de Alta Performance
            $table->index('deleted_at'); // Acelera o filtro automático do SoftDeletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autors');
    }
};
