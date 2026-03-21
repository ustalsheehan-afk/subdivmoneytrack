<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Homeowner;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RequestModel>
 */
class RequestModelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'homeowner_id' => Homeowner::factory(),
            'subject' => $this->faker->sentence(3),
            'message' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'resolved']),
            'date_sent' => $this->faker->dateTimeBetween('-2 weeks', 'now'),
        ];
    }
}
