<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DiaSeeder::class,
            FormaDePagamentoSeeder::class,
            MarcacaoSeeder::class,
            MedidaSeeder::class,
            SituacaoSeeder::class,
            TipoDeAulaSeeder::class,
            TipoSeeder::class,
            UserSeeder::class,
        ]);
    }
}
