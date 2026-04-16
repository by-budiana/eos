<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->city(),
            'date' => $this->faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['Pending', 'On Progress', 'Done']),
            'priority' => $this->faker->randomElement(['Low', 'Medium', 'High']),
            'note' => $this->faker->optional()->sentence(),
            'deadline' => $this->faker->dateTimeBetween('now', '+2 months')->format('Y-m-d'),
        ];
    }
}
