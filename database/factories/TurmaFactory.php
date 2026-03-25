<?php
namespace Database\Factories;

use App\Models\Dia;
use App\Models\Nucleo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

class TurmaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        do {
            $http = Http::get('https://random.dog/woof');
            $imageName = $http->body();
        } while (preg_match('/\.(mp4|webm)$/', $imageName));

        $vagasOfertadas = $this->faker->numberBetween(0, 50);

        return [
            'nome' => $this->faker->name(),
            'descricao' => implode("\n\n", $this->faker->paragraphs(3)),
            'imagem' => 'https://random.dog/' . $imageName,
            'vagas_ofertadas' => $vagasOfertadas,
            'horario' => $this->faker->time('H:i'),
            'disponivel' => $this->faker->boolean(),
            'zoom' => $this->faker->url(),
            'zoom_id' => $this->faker->uuid(),
            'zoom_senha' => $this->faker->password(),
            'whatsapp' => $this->faker->url(),
            'spotify' => $this->faker->url(),
            'nucleo_id' => Nucleo::inRandomOrder()->first()->id,
            'dia_id' => Dia::inRandomOrder()->first()->id
        ];
    }
}
