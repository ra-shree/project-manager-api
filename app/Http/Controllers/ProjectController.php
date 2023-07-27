<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Project::with('manager')->get();
        if($projects) {
            return response()->json($projects);
        }
        return response()->json();
    }

    public function findMembers(int $project_id): JsonResponse
    {
        $members = User::whereHas('projects', function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        })->get();
        return response()->json($members);
    }

    public function show($project_id): JsonResponse | Response
    {
        $project = Project::with('members')->find($project_id);
        if($project->manager_id == auth()->id()) {
            return response()->json($project);
        }
       return response('Not Authorized', 401);
    }

    public function projectViaManager(): JsonResponse
    {
        $projects = Project::where('manager_id', '=', auth()->id())->get();
        return response()->json($projects);
    }

    public function store(Request $request): Response
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255', 'min:3'],
            'description' => ['string', 'max:500'],
            'manager_id' => ['integer', Rule::exists('users', 'id')],
        ]);

        Project::create([
            'title' => $request->title,
            'description' => ($request->description)? $request->description : '',
            'status' => 'Draft',
            'manager_id' => ($request->manager_id)? $request->manager_id : '',
        ]);

        return response('Project Created', 200);
    }

    public function destroy(int $id): Response
    {
        $project = Project::find($id);
        if($project) {
            $project->delete();
            return response('Project Deleted', 200);
        }
        return response('Project does not exist', 401);
    }
}
