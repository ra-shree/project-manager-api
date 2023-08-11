<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
//        Display all the tasks that belong to projects assigned to a manager
        if(auth()->user()->role === 'manager') {
            $tasks = Task::with('assigned')->whereIn('project_id', function () {
                return Project::select('id')->where('manager_id', '=', auth()->id())->get();
            })->get();
            return response()->json($tasks);
        }
//        Display tasks assigned to a developer
        $tasks = Task::with('assigned')->where('user_id', '=', auth()->id())->get();
        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request): Response
    {
        Task::create($request->validated());
        return response('Task Created', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): JsonResponse
    {
        return response()->json($task->load('assigned'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task): JsonResponse | Response
    {
        if ($request->isMethod('PATCH')) {
            $task->update([
                'completed' => !$task->completed
            ]);
            return response()->json(['status' => $task->completed]);
        }
        $task->update($request->validated());
        return response('Project Updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): Response
    {
        $task->delete();
        return response('Task Deleted', 200);
    }
}
