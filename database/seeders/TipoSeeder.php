<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipos')->insert([
            ['nome' => 'acompanhante'],
            ['nome' => 'acompanhante reserva'],
            ['nome' => 'pagante'],
        ]);
    }
}
