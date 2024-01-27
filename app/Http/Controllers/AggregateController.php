<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AggregateController extends Controller
{
    public function summary(): JsonResponse
    {
        $user_count_query = User::select('role', DB::raw('COUNT(*) as count'))->groupBy('role')->get();
        $user_count = $user_count_query->reduce(function($prev, $user) {
            return [
                ...collect($prev)->toArray(),
                data_get($user, 'role', 'others').'s' => data_get($user, 'count', 0)
            ];
        }, []);

        $project_count_query = Project::select('status', DB::raw('COUNT(*) as count'))->groupBy('status')->get();
        $project_count = $project_count_query->reduce(function($prev, $project) {
            return [
                ...collect($prev)->toArray(),
                data_get($project, 'status', 'others') => data_get($project, 'count', 0)
            ];
        }, []);

        $completed_project_count = $project_count['Completed']?? 0;
        $ongoing_project_count = $project_count['In Progress']?? 0;

        $task_count = Task::select('completed', DB::raw('COUNT(*) as count'))->groupBy('completed')->get()->toArray();
        $completed_task_count = data_get($task_count, '0.count', 0);
        $incomplete_task_count = data_get($task_count, '1.count', 0);

        return response()->json([
            ...$user_count,
            'ongoing_project_count' => $ongoing_project_count,
            'completed_project_count' => $completed_project_count,
            'completed_task_count' => $completed_task_count,
            'incomplete_task_count' => $incomplete_task_count,
        ]);
    }

    public function project(Request $request): JsonResponse | Response
    {
        $keyword = $request->query('project');

        if($keyword === 'new') {
            $projects = Project::orderBy('created_at', 'desc')->take(4)->get();

            return response()->json($projects);
        }

        if($keyword === 'updated') {
            $projects = Project::orderBy('updated_at', 'desc')->take(4)->get();

            return response()->json($projects);
        }
        return response()->noContent();
    }

    public function tasks(Request $request): JsonResponse | Response
    {
        $keyword = $request->get('task');

        if($keyword === 'new') {
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
            $task_count_query = Task::select('completed', DB::raw('COUNT(*) as count'))
                ->where('user_id', '=', auth()->id())
                ->groupBy('completed')->get();

            $task_count = $task_count_query->reduce(function($prev, $task) {
                return [
                    ...collect($prev)->toArray(),
                    data_get($task, 'completed', 'others') => data_get($task, 'count', 0)
                ];
            }, []);

            $completed_task_count = $task_count[1]?? 0;
            $incomplete_task_count = $task_count[0]?? 0;

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
