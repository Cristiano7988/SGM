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
        Schema::create('aulas', function (Blueprint $table) {
            $table->id();
            $table->date('dia');
            $table->time('horario');
            $table->decimal('valor', 10, 2)->default(0);
            $table->decimal('taxa_de_deslocamento', 10, 2)->default(0);
            $table->integer('tempo_de_deslocamento')->default(0);
            $table->decimal('distancia', 10, 2)->default(0);
            $table->foreignId('turma_id')->constrained()->onDelete('cascade');
            $table->foreignId('pacote_id')->constrained()->onDelete('cascade');
            $table->foreignId('endereco_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
