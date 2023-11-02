<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCuponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medidas', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
        });

        DB::table('medidas')->insert([
            ['tipo' => '%'],
            ['tipo' => 'R$']
        ]);

        Schema::create('cupons', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->decimal('desconto');
            $table->foreignId('medida_id')->nullable();
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
        Schema::dropIfExists('cupons');
        Schema::dropIfExists('medidas');
    }
}
