<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $dia = $this->faker->dateTimeBetween('-1 year', 'now');

        return [
            'dia' => $dia,
        ];
    }
}
