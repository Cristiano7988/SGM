<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

class NucleoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $inicio_rematricula = $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d');
        $fim_rematricula = $this->faker->dateTimeBetween($inicio_rematricula, '+1 year')->format('Y-m-d');

        do {
            $http = Http::get('https://random.dog/woof');
            $imageName = $http->body();
        } while (preg_match('/\.(mp4|webm)$/', $imageName));

        return [
            'nome' => $this->faker->name(),
            'imagem' => 'https://random.dog/' . $imageName,
            'descricao' => $this->faker->paragraph(),
            'idade_minima_id' => $this->faker->numberBetween(1, 18),
            'idade_maxima_id' => $this->faker->numberBetween(19, 100),
            'inicio_rematricula' => $inicio_rematricula,
            'fim_rematricula' => $fim_rematricula,
        ];
    }
}
