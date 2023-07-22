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
        $manager = User::where('role', 'manager')->inRandomOrder()->first();
        $statuses = ['In Progress', 'Completed', 'Draft', 'On Hold'];
        return [
            'project_name' => fake()->name(),
            'description' => fake()->text(300),
            'manager_id' => $manager->id,
            'status' => fake()->randomElement($statuses),
        ];
    }
}
