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
            $table->string('valor_pago');
            $table->date('data_de_pagamento');
            $table->string('obs')->nullable();
            
            // Informações de backup (caso haja algum aluno, usuário, pacote, forma de pagamento ou período deletado)
            $table->string('forma_de_pagamento')->nullable();
            $table->string('nome_do_aluno')->nullable();
            $table->string('nome_do_pacote')->nullable();
            $table->string('vigencia_do_pacote')->nullable();
            $table->decimal('valor_do_pacote')->nullable();
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
