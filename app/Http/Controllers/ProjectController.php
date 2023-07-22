<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Project::with('user')->get();
        return response()->json($projects);
    }

    public function store(Request $request): Response
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'description' => ['required', 'string', 'max:500'],
            'manager_id' => ['required', 'integer', Rule::exists('users', 'manager_id')],
        ]);

        Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'Draft',
            'manager_id' => $request->manager_id,
        ]);

        return response('Project Created', 200);
    }
}
