<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Endereco>
 */
class EnderecoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cep' => $this->faker->postcode(),
            'pais' => $this->faker->country(),
            'estado' => $this->faker->state(),
            'cidade' => $this->faker->city(),
            'bairro' => $this->faker->streetName(),
            'logradouro' => $this->faker->streetAddress(),
            'numero' => $this->faker->buildingNumber(),
            'complemento' => $this->faker->secondaryAddress()
        ];
    }
}
