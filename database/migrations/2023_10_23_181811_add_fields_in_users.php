<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email_nf')->nullable();
            $table->string('cpf')->unique()->nullable();
            $table->string('cnpj')->unique()->nullable();
            $table->string('vinculo')->nullable();
            $table->string('whatsapp')->unique()->nullable();
            $table->string('instagram')->unique()->nullable();
            $table->string('cep')->nullable();
            $table->string('pais')->nullable();
            $table->string('estado')->nullable();
            $table->string('cidade')->nullable();
            $table->string('bairro')->nullable();
            $table->string('logradouro')->nullable();
            $table->integer('numero')->nullable();
            $table->string('complemento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_nf',
                'cpf',
                'cnpj',
                'vinculo',
                'whatsapp',
                'instagram',
                'cep',
                'pais',
                'estado',
                'cidade',
                'bairro',
                'logradouro',
                'numero',
                'complemento'
            ]);
        });
    }
}
