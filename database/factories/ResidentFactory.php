<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResidentFactory extends Factory
{
    protected $model = \App\Models\Resident::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->state(['role' => 'resident']),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'contact' => $this->faker->phoneNumber(),
            'block' => $this->faker->numberBetween(1, 10),
            'lot' => $this->faker->numberBetween(1, 50),
            'move_in_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'password' => Hash::make('password'),
        ];
    }
}
