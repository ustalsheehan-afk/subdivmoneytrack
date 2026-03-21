<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Resident;
use App\Models\Due;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'resident_id' => Resident::factory(),
            'due_id' => Due::factory(),
            'amount' => $this->faker->randomFloat(2, 500, 1500),
            'date_paid' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'proof' => null,
            'payment_method' => $this->faker->randomElement(['Cash', 'Bank Transfer', 'GCash']),
        ];
    }
}
