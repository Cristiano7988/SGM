<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AulaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'dia' => $this->faker->date('Y-m-d'),
            'horario' => $this->faker->time('H:i'),
            'pacote_id' => Pacote::inRandomOrder()->first()->id
        ];
    }
}
