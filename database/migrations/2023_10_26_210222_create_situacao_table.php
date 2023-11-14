<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSituacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('situacao', function (Blueprint $table) {
            $table->id();
            $table->string('esta');
        });

        DB::table('situacao')->insert([
            ['esta' => 'em espera'],
            ['esta' => 'cursando'],
            ['esta' => 'cursando e aguardando transação'],
            ['esta' => 'trancado'],
            ['esta' => 'cursando e liberado para rematrícula'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('situacao');
    }
}
