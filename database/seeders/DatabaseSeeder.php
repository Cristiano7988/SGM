<?php

namespace Database\Seeders;

use App\Models\FormaDePagamento;
use App\Models\MedidaDeTempo;
use App\Models\TipoDeAula;
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
            MedidaDeTempoSeeder::class,
            MedidaSeeder::class,
            SituacaoSeeder::class,
            TipoDeAulaSeeder::class,
            TipoSeeder::class,
            UserSeeder::class,
        ]);
    }
}
