<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        if(auth()->user()->role == 'manager') {
            $projects = Project::where('manager_id', '=', auth()->id())->pluck('id');
            $tasks = Task::with('assigned')->whereIn('project_id', $projects)->get();
            return response()->json($tasks);
        }
        $tasks = Task::with('assigned')->where('user_id', '=', auth()->id())->get();
        return response()->json($tasks);
    }

    public function store(TaskRequest $request): Response | JsonResponse
    {
        Task::create($request->validated());
        return response('Task Created', 200);
    }

    public function update(TaskRequest $request, int $task_id): Response
    {
        $task = Task::find($task_id);
        if($task) {
            $task->update($request->validated());
            return response('Task Updated', 200);
        }
        return response('Task does not exist', 401);
    }

    public function completed(int $task_id): JsonResponse | Response
    {
        $task = Task::where('id', '=', $task_id)->first();
        if($task) {
            $task->completed = !$task->completed;
            $task->save();
            return response()->json($task);
        }
        return response('Task does not exist', 401);
    }

    public function destroy(int $task_id): Response
    {
        $task = Task::find($task_id);
        if($task) {
            $task->delete();
            return response('Task Deleted', 200);
        }
        return response('Task does not exist', 401);
    }
}
