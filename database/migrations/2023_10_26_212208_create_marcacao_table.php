<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMarcacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marcacao', function (Blueprint $table) {
            $table->id();
            $table->string("observacao");
            $table->string("cor");
            $table->integer("key_code");
        });

        DB::table('marcacao')->insert([
            [
                "observacao" => "vai continuar, mas não pagou!",
                "cor" => "brown",
                "key_code" => 109
            ],
            [
                "observacao" => "aluno novo",
                "cor" => "red",
                "key_code" => 118
            ],
            [
                "observacao" => "revisar matrícula",
                "cor" => "black",
                "key_code" => 112
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marcacao');
    }
}
