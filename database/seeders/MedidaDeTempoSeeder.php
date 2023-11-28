<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedidaDeTempoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('medida_de_tempo')->insert([
            ['tipo' => 'meses'],
            ['tipo' => 'anos']
        ]);
    }
}
