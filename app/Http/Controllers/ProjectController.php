<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
//        display all projects if admin
        if(auth()->user()->role === 'admin') {
            $projects = Project::with('manager')->orderByDesc('updated_at')->get();
            return response()->json($projects);
        }

//        display projects that are assigned to the manager
        if(auth()->user()->role === 'manager') {
            $projects = Project::where('manager_id', '=', auth()->id())->get();
            return response()->json($projects);
        }

//        display projects that has the developer as a member
        $projects = Project::whereHas('members', function ($query) {
            $query->where('user_id', '=', auth()->id());
        })->get();

        return response()->json($projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request): Response
    {
        Project::create($request->validated());
        return response('Project Created', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): JsonResponse
    {
        $project = $project->load('members', 'tasks', 'tasks.assigned');
        return response()->json($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, Project $project): JsonResponse | Response
    {
        $project->update($request->validated());
        if ($request->isMethod('PATCH')) {
            return response()->json(['status' => $project->status]);
        }
        return response('Project Updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return response('Project Deleted', 200);
    }

    /**
     * Display all the members of a project
     */
    public function findMembers(Project $project): JsonResponse
    {
        $members = $project->members;
        return response()->json($members);
    }

    public function paginateIndex(Request $request)
    {
        $size = $request->query('size') ?? 5;
        $page = $request->query('page') ?? 1;
        $search = $request->query('search');
        if($request->query('search')) {
            return new ProjectCollection(
                Project::where('title', 'ilike', "%{$search}%")
                ->orWhere('description', 'ilike', "%{$search}%")
                ->limit($size)
                ->offset(($page - 1) * $size)->get());
        }
        return new ProjectCollection(Project::limit($size)->offset(($page - 1) * $size)->get());
    }
}
