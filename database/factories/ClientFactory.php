<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'rg' => $this->fake->buildingNumber(),
            'organ' => $this->fake->buildingNumber(),
            'cpf' => $this->fake->buildingNumber(),
            'phone' => $this->fake->phone(),
            'email' => $this->faker->unique()->safeEmail(),
            'zip_code' => $this->fake->postcode(),
            'address' => $this->fake->address(),
            'number' => $this->fake->buildingNumber(),
            'complement' => $this->fake->address(),
            'district' => $this->fake->city(),
            'city' => $this->fake->city(),
            'state' => $this->fake->stateAbbr(),
        ];
    }
}