<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dias')->insert([
            ['nome' => 'domingo'],
            ['nome' => 'segunda'],
            ['nome' => 'terça'],
            ['nome' => 'quarta'],
            ['nome' => 'quinta'],
            ['nome' => 'sexta'],
            ['nome' => 'sábado']
        ]);
    }
}
