<?php

namespace Database\Seeders;

use App\Models\Pacote;
use Illuminate\Database\Seeder;

class PacoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pacote::factory()->count(5)->create();
    }
}
