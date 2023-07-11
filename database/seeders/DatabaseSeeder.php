<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Project;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        SubTask::truncate();
        Task::truncate();
        Project::truncate();
        UserDetail::truncate();
        User::truncate();

        User::factory(20)->create()->each(function ($user) {
            UserDetail::factory(1)->create([
                'username' => $user->username,
            ]);

            Project::factory(2)->create([
                'owner_id' => $user->id,
            ])->each(function ($project) {
                Task::factory(4)->create([
                    'project_id' => $project->id,
                    'creator_id' => $project->owner_id,
                    'assigned_to_id' => $project->owner_id,
                ])->each(function ($task) {
                    SubTask::factory(2)->create([
                        'task_id' => $task->id,
                    ]);
                });

            });
        });
    }
}
