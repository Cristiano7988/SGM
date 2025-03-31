<?php
namespace Database\Factories;

use App\Models\Dia;
use App\Models\Nucleo;
use App\Models\TipoDeAula;
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

        return [
            'nome' => $this->faker->name(),
            'descricao' => $this->faker->sentence(),
            'imagem' => 'https://random.dog/' . $imageName,
            'vagas_preenchidas' => $this->faker->numberBetween(0, 100),
            'vagas_fora_do_site' => $this->faker->numberBetween(0, 50),
            'vagas_ofertadas' => $this->faker->numberBetween(10, 100),
            'horario' => $this->faker->time(),
            'disponivel' => $this->faker->boolean(),
            'zoom' => $this->faker->url(),
            'zoom_id' => $this->faker->uuid(),
            'zoom_senha' => $this->faker->password(),
            'whatsapp' => $this->faker->phoneNumber(),
            'spotify' => $this->faker->url(),
            'nucleo_id' => Nucleo::inRandomOrder()->first()->id,
            'dia_id' => Dia::inRandomOrder()->first()->id,
            'tipo_de_aula_id' => TipoDeAula::inRandomOrder()->first()->id,
        ];
    }
}
