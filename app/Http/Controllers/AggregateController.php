<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AggregateController extends Controller
{
    public function summary(): JsonResponse
    {
        $developer_count = User::where('role', '=','developer')->count();
        $manager_count = User::where('role', '=','manager')->count();
        $completed_project_count = Project::where('status', '=', 'Completed')->count();
        $ongoing_project_count = Project::where('status', '=', 'In Progress')->count();
        $completed_task_count = Task::where('completed', '=', true )->count();
        $incomplete_task_count = Task::where('completed', '=', false )->count();
        return response()->json([
            'developer_count' => $developer_count,
            'manager_count' => $manager_count,
            'ongoing_project_count' => $ongoing_project_count,
            'completed_project_count' => $completed_project_count,
            'completed_task_count' => $completed_task_count,
            'incomplete_task_count' => $incomplete_task_count,
        ]);
    }

    public function project($keyword): JsonResponse | Response
    {
        if($keyword == 'new') {
            $projects = Project::with('manager')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            return response()->json($projects);
        }

        if($keyword == 'updated') {
            $projects = Project::with('manager')
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();
            return response()->json($projects);
        }
        return response()->noContent();
    }

    public function tasks($keyword): JsonResponse | Response
    {
        if($keyword == 'new') {
            $tasks = Task::with('assigned')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            return response()->json($tasks);
        }
        return response()->noContent();
    }

    public function userSummary(): JsonResponse
    {
        if(auth()->user()->role === 'developer') {
            $completed_task_count = Task::where('completed', '=', true )
                ->where('id', '=', auth()->id())
                ->count();
            $incomplete_task_count = Task::where('completed', '=', false )
                ->where('id', '=', auth()->id())
                ->count();
            return response()->json([
                'completed_task_count' => $completed_task_count,
                'incomplete_task_count' => $incomplete_task_count,
            ]);
        }

        $project_count = Project::where('manager_id', '=', auth()->id())->count();
        $developer_count = User::whereHas('projects', function ($query) {
            $query->where('manager_id', auth()->id());
        })->where('role', '=', 'developer')->count();

        $completed_task_count = Task::whereHas('project', function ($query) {
            $query->where('manager_id', auth()->id());
        })->where('completed', '=', true )->whereDate('updated_at', today())->count();

        return response()->json([
            'project_count' => $project_count,
            'developer_count' => $developer_count,
            'completed_task_count' => $completed_task_count,
        ]);
    }

    public function userProjects(): JsonResponse
    {
        if(auth()->user()->role === 'manager') {
            $projects = Project::with('manager')
                ->where('manager_id', '=', auth()->id())
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();
            return response()->json($projects);
        }

        $projects = User::with('projects')
            ->where('id', '=', auth()->id())
            ->take(5)
            ->firstOrFail();
        return response()->json($projects);
    }

    public function userTasks(): JsonResponse
    {
        if(auth()->user()->role === 'manager') {
            $tasks = Task::with('assigned')
                ->whereHas('project', function ($query) {
                    $query->where('manager_id', auth()->id());
                })
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();
            return response()->json($tasks);
        }

        $tasks = User::with('tasks')->orderByDesc('created_at')
            ->where('id', '=', auth()->id())
            ->take(5
            )->firstOrFail();
        return response()->json($tasks);
    }
}
