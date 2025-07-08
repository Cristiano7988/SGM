<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FakerContentSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            NucleoSeeder::class,
            TurmaSeeder::class,
            PacoteSeeder::class,
            PeriodoSeeder::class,
            AlunoSeeder::class,
        ]);
    }
}
