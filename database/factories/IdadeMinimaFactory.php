<?php

namespace Database\Factories;

use App\Models\Medida;
use Illuminate\Database\Eloquent\Factories\Factory;

class IdadeMinimaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'idade' => $this->faker->numberBetween(1, 12),
            'medida_de_tempo_id' => Medida::inRandomOrder()->first()->id
        ];
    }

    public function withMedida($medidaId)
    {
        return $this->state([
            'medida_de_tempo_id' => $medidaId,
        ]);
    }
}
