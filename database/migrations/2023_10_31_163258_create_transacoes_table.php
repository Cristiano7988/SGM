<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('matricula_id')->nullable();
            $table->foreignId('cupom_id')->nullable();
            $table->foreignId('forma_de_pagamento_id')->nullable();
            $table->string('comprovante')->nullable();
            $table->boolean('enviada_para_contadora')->default(false);
            $table->string('valor_pago');
            $table->date('data_de_pagamento');
            $table->string('obs')->nullable();
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
        Schema::dropIfExists('transacoes');
    }
}
