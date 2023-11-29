<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormaDePagamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('formas_de_pagamento')->insert([
            ['tipo' => 'transferência bancária (intercontas)'],
            ['tipo' => 'pix'],
            ['tipo' => 'paypal'],
            ['tipo' => 'agência bancária externa (lotérica internacional)'],
        ]);
    }
}
