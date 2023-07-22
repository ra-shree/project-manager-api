<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
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
            'title' => fake()->sentence(5, false),
            'description' => fake()->paragraph(2),
            'completed' => fake()->randomElement([true, false]),
            'assigned_to_id' => User::class,
            'creator_id' => User::class,
            'project_id' => Project::class,
        ];
    }
}
