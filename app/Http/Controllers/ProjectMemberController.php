<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectMemberRequest;
use App\Models\ProjectMember;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectMemberRequest $request): Response
    {
        ProjectMember::create($request->validated());
        return response('Developer Added To Project', 200);
    }

    /**
     * Display all the developers not in a project
     */
    public function show(int $project_id): JsonResponse
    {
        $usersNotInProject = User::where('role', '=', 'developer')
            ->whereDoesntHave('projects', function ($query) use ($project_id) {
                $query->where('project_id', '=' , $project_id);})
            ->get();
        return response()->json($usersNotInProject);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectMember $projectMember)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectMember $projectMember)
    {
        //
    }

    public function removeDeveloper(int $project_id, int $user_id): Response
    {
        $member = ProjectMember::where('project_id', '=', $project_id)->where('user_id', '=', $user_id)->firstorFail();
        if($member) {
            $member->delete();
            return response('Member Removed', 200);
        }
        return response('Remove Member Failed', 400);
    }
}
