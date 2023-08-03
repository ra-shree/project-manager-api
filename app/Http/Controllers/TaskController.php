<?php

namespace App\Http\Controllers;

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
        $user_id = auth()->id();
        $projects = Project::where('manager_id', '=', $user_id)->pluck('id');
        $tasks = Task::with('assigned')->whereIn('project_id', $projects)->get();
        return response()->json($tasks);
    }

    public function store(Request $request): Response | JsonResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255', 'min:3'],
            'description' => ['nullable', 'max:150'],
            'project_id' => ['required', Rule::exists('projects', 'id')],
            'user_id' => ['required', Rule::exists('users', 'id')],
        ]);

        Task::create([
            'title' => $request->title,
            'description' => ($request->description)? $request->description : '',
            'project_id' => $request->project_id,
            'user_id' => $request->user_id,
        ]);

        return response('Task Created', 200);
    }

    public function update(Request $request, int $task_id): Response
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255', 'min:3'],
            'description' => ['nullable', 'max:500'],
            'project_id' => ['required', Rule::exists('projects', 'id')],
            'user_id' => ['required', Rule::exists('users', 'id')],
        ]);

        $task = Task::find($task_id);
        if($task) {
            $task->title = $request->title;
            $task->description = ($request->description)? $request->description : '';
            $task->project_id = $request->project_id;
            $task->user_id = $request->user_id;
            $task->save();
            return response('Task Updated', 200);
        }
        return response('Task does not exist', 401);
    }

    public function completed(Request $request, int $task_id): JsonResponse
    {
        $task = Task::where('id', '=', $task_id)->first();
        $task->completed = !$task->completed;
        $task->save();
        return response()->json($task);
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
