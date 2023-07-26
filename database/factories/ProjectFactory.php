<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['Completed', 'In Progress', 'Draft', 'On Hold']);
        $manager = User::where('role', 'manager')->inRandomOrder()->first();

        return [
            'title' => fake()->sentence(8),
            'description' => fake()->sentence(80),
            'status' => $status,
            'manager_id' => $manager,
        ];
    }
}
