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
<<<<<<< HEAD
        $manager = User::where('role', 'manager')->inRandomOrder()->first();
        $statuses = ['In Progress', 'Completed', 'Draft', 'On Hold'];
        return [
            'project_name' => fake()->name(),
            'description' => fake()->text(300),
            'manager_id' => $manager->id,
            'status' => fake()->randomElement($statuses),
=======
        return [
            'name' => fake()->sentence('3', false),
            'owner_id' => User::class,
>>>>>>> 6d6431f97a949d2530fbb7b3789e4dfb91324ccf
        ];
    }
}
