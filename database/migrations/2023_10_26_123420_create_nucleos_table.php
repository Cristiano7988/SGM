<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNucleosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nucleos', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->string('imagem')->nullable();
            $table->longText('descricao')->nullable();
            $table->foreignId('idade_minima_id')->nullable();
            $table->foreignId('idade_maxima_id')->nullable();
            $table->date('inicio_rematricula');
            $table->date('fim_rematricula');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nucleos');
    }
}
