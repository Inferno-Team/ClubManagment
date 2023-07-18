<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClubSubScriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'club_id' => 1,
            'subscription_id' => 1,
            'price' => $this->faker->numberBetween(10_000, 100_000),
        ];
    }
}
