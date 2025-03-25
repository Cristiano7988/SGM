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

        $meses = 12;
        $anos = 60;
        $limite = $meses * $anos;
        $idadeMinima = $this->faker->numberBetween(1, $limite);
        $idadeMaxima = $this->faker->numberBetween($idadeMinima, $limite);

        return [
            'nome' => $this->faker->name(),
            'imagem' => 'https://random.dog/' . $imageName,
            'descricao' => implode("\n\n", $this->faker->paragraphs(3)),
            'idade_minima' => $idadeMinima,
            'idade_maxima' => $idadeMaxima,
            'inicio_rematricula' => $inicio_rematricula,
            'fim_rematricula' => $fim_rematricula,
        ];
    }
}
