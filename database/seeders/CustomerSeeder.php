<?php

namespace Database\Seeders;

use App\Models\ClubSubScription;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->has(UserSubscription::factory())->count(100)->create([
            'type' => 'customer',
            'password' => Hash::make('password123'),
        ]);
    }
}
