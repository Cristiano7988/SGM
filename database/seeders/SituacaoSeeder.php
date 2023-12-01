<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SituacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('situacoes')->insert([
            ['esta' => 'em espera'],
            ['esta' => 'cursando'],
            ['esta' => 'cursando e aguardando transação'],
            ['esta' => 'trancado'],
            ['esta' => 'cursando e liberado para rematrícula'],
        ]);
    }
}
