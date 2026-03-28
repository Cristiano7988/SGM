<?php
namespace Database\Factories;

use App\Models\Turma;
use Illuminate\Database\Eloquent\Factories\Factory;

class PacoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nome' => $this->faker->name(),
            'valor' => $this->faker->numberBetween(0, 500),
            'ativo' => $this->faker->boolean(),
            'turma_id' => Turma::inRandomOrder()->first()->id,
        ];
    }
}
