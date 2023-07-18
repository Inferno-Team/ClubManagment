<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClubFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->streetName(),
            'location' => $this->faker->address(),
            'lat' => $this->faker->latitude(),
            'lng' => $this->faker->longitude(),
            'manager_id' => 1,
            'image' => '4.png',
        ];
    }
}
