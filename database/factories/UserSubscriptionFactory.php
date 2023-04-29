<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserSubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start_at = $this->faker->dateTimeBetween('-12 months','+12 months');
        return [
            'customer_id' => 1,
            'subscription_id' => 1,
            'price' => $this->faker->numberBetween(10_000, 100_000),
            'start_at' => $start_at,
            'end_at' => $this->faker->dateTimeBetween($start_at,'+12 months'),
        ];
    }
}
