<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nucleo;

class NucleoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Nucleo::factory()->count(2)->create();
    }
}
