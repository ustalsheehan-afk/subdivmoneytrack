<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Homeowner;
use App\Models\Due;

class PenaltyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'homeowner_id' => Homeowner::factory(),
            'due_id' => Due::factory(),
            'amount' => $this->faker->randomFloat(2, 50, 200),
            'reason' => 'Late payment',
            'date_assigned' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
