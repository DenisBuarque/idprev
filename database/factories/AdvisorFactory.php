<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Advisor>
 */
class AdvisorFactory extends Factory
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
            'oab' => $this->faker->name(),
            'phone' => $this->fake->buildingNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'zip_code' => $this->fake->postcode(),
            'address' => $this->fake->address(),
            'number' => $this->fake->buildingNumber(),
            'district' => $this->fake->country(),
            'city' => $this->fake->city(),
            'state' => $this->fake->stateAbbr(),
            'complement' => $this->fake->streetAddress(),
        ];
    }
}
