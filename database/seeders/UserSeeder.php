<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\ClubSubScription;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->has(
            Club::factory()->count(1)->has(
                ClubSubScription::factory()->count(2)->has(
                    UserSubscription::factory()->has(User::factory()->count(1)
                        ->state(function (array $attributes, User $user) {
                            return ['type' => 'customer'];
                        }), 'customer')->count(30),
                    'user_subscriptions'
                ),
                'subs'
            )
        )
            ->count(3)->create([
                'type' => 'manager',
                'password' => Hash::make('password123'),
            ]);
    }
}
