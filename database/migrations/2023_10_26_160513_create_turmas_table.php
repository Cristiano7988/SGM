<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turmas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->longText('descricao')->nullable();
            $table->string('imagem')->nullable();
            $table->integer('vagas_preenchidas')->default(0);
            $table->integer('vagas_fora_do_site')->default(0);
            $table->integer('vagas_ofertadas')->default(6);
            $table->time('horario')->nullable();
            $table->boolean('disponivel')->default(false);
            $table->string('zoom')->nullable();
            $table->string('zoom_id')->nullable();
            $table->string('zoom_senha')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('spotify')->nullable();
            $table->foreignId('nucleo_id')->nullable();
            $table->foreignId('dia_id')->nullable();
            $table->foreignId('tipo_de_aula_id')->nullable();
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
        Schema::dropIfExists('dias');
        Schema::dropIfExists('tipos_de_aula');
        Schema::dropIfExists('turmas');
    }
}
