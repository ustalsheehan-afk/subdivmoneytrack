<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AuditLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'action' => $this->faker->sentence(),
            'user_id' => 1,
            'description' => $this->faker->paragraph(),
        ];
    }
}
