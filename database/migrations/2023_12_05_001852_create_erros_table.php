<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateErrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('rota')->nullable();
            $table->string('metodo')->nullable();
            $table->string('acessado_via')->nullable();
            $table->string('corpo_da_requisicao')->nullable();
            $table->longText('mensagem')->nullable();
            $table->string('arquivo')->nullable();
            $table->string('linha')->nullable();
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
        Schema::dropIfExists('erros');
    }
}
