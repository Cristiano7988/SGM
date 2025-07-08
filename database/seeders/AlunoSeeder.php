<?php

namespace Database\Seeders;

use App\Models\Aluno;
use App\Models\User;
use Illuminate\Database\Seeder;

class AlunoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Aluno::factory()->count(10)->create()->each(function ($aluno) {
            User::inRandomOrder()->first()->alunos()->attach($aluno->id);
        });
    }
}
