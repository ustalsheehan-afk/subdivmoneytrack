<?php

namespace Database\Factories;

use App\Models\Due;
use App\Models\Resident;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DueFactory extends Factory
{
    protected $model = Due::class;

    public function definition(): array
    {
        $dueDate = $this->faker->dateTimeBetween('now', '+2 months');
        $type = $this->faker->randomElement([
            Due::TYPE_MONTHLY_HOA,
            Due::TYPE_REGULAR_FEES,
            Due::TYPE_SPECIAL_ASSESSMENTS,
        ]);

        $frequency = $this->faker->randomElement([
            Due::FREQUENCY_MONTHLY,
            Due::FREQUENCY_ONE_TIME,
            Due::FREQUENCY_QUARTERLY,
        ]);

        return [
            'batch_id' => (string) Str::uuid(),
            'resident_id' => Resident::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->optional()->sentence(10),
            'amount' => $this->faker->randomFloat(2, 500, 2000),
            'type' => $type,
            'frequency' => $frequency,
            'month' => $dueDate->format('F'),
            'due_date' => $dueDate,
            'status' => Due::STATUS_UNPAID,
        ];
    }
}
