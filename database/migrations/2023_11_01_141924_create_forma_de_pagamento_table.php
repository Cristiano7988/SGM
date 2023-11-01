<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateFormaDePagamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forma_de_pagamento', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
        });

        DB::table('forma_de_pagamento')->insert([
            ['tipo' => 'transferência bancária (intercontas)'],
            ['tipo' => 'pix'],
            ['tipo' => 'paypal'],
            ['tipo' => 'agência bancária externa (lotérica internacional)'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forma_de_pagamento');
    }
}
