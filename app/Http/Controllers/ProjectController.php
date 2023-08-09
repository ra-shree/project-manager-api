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
        $projects = Project::with('manager')->orderByDesc('updated_at')->get();
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
        $project = Project::with('members', 'tasks', 'tasks.assigned')->find($project_id);
        if($project->manager_id == auth()->id() || auth()->user()->role == 'admin') {
            return response()->json($project);
        }
        if($project->members->contains(auth()->id())) {
            return response()->json($project);
        }
       return response('Not Authorized', 401);
    }

    public function updateStatus(Request $request, int $project_id): JsonResponse | Response
    {
        $request->validate([
            'status' => ['required', Rule::in(['Draft', 'In Progress', 'Completed', 'On Hold'])],
        ]);

        $project = Project::find($project_id);
        if($project) {
            $project->status = $request->status;
            $project->save();
            return response()->json(['status' => $project->status]);
        }
        return response('Project does not exist', 401);
    }

    public function projectViaRole(): JsonResponse
    {
        if(auth()->user()->role == 'manager') {
            $projects = Project::where('manager_id', '=', auth()->id())->get();
            return response()->json($projects);
        }

        $projects = Project::whereHas('members', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();

        return response()->json($projects);
    }

    public function store(Request $request): Response
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255', 'min:3'],
            'description' => ['string', 'max:500'],
            'manager_id' => ['integer', 'required', Rule::exists('users', 'id')],
            'status' => ['required', 'string', Rule::in(['Draft', 'In Progress', 'Completed', 'On Hold'])]
        ]);

        Project::create([
            'title' => $request->title,
            'description' => ($request->description)? $request->description : '',
            'manager_id' => ($request->manager_id)? $request->manager_id : '',
            'status' => $request->status,
        ]);

        return response('Project Created', 200);
    }

    public function update(Request $request, int $project_id): Response
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255', 'min:3'],
            'description' => ['string', 'max:500'],
            'manager_id' => ['integer', Rule::exists('users', 'id')],
            'status' => ['required', 'string', Rule::in(['Draft', 'In Progress', 'Completed', 'On Hold'])]
        ]);

        $project = Project::find($project_id);
        if($project) {
            $project->title = $request->title;
            $project->description = $request->description;
            $project->manager_id = $request->manager_id;
            $project->status = $request->status;
            $project->save();
            return response('Project Updated', 200);
        }
        return response('Project does not exist', 401);
    }

    public function destroy(int $project_id): Response
    {
        $project = Project::find($project_id);
        if($project) {
            $project->delete();
            return response('Project Deleted', 200);
        }
        return response('Project does not exist', 401);
    }
}
