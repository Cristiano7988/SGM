<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarcacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('marcacoes')->insert([
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
}
