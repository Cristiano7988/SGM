<?php
namespace Database\Factories;

use App\Models\Pacote;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeriodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $inicio = $this->faker->dateTimeBetween('-1 year', 'now');
        $fim = $this->faker->dateTimeBetween($inicio, '+1 year');

        return [
            'inicio' => $inicio,
            'fim' => $fim,
            'pacote_id' => Pacote::inRandomOrder()->first()->id,
        ];
    }
}
