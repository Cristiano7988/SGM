<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoDeAulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipos_de_aula')->insert([
            ['tipo' => 'coletiva'],
            ['tipo' => 'naipes'],
            ['tipo' => 'individual']
        ]);
    }
}
