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
        $project = Project::select('id')->inRandomOrder()->first();
        $projectId = $project->id;
        $userInProject = User::where('role', '=', 'developer')
            ->whereHas('projects', function ($query) use ($projectId) {
                $query->where('project_id', '=' , $projectId);})
            ->inRandomOrder()->first();

        return [
            'title' => fake()->sentence(5, false),
            'description' => fake()->sentence(6),
            'completed' => fake()->randomElement([true, false]),
            'user_id' => $userInProject,
            'project_id' => $projectId,
        ];
    }
}
