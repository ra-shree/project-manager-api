<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectMemberRequest;
use App\Models\ProjectMember;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class ProjectMemberController extends Controller
{
    // developers that aren't already in the project
    public function getDeveloper($project_id): JsonResponse
    {
        $usersNotInProject = User::where('role', '=', 'developer')
            ->whereDoesntHave('projects', function ($query) use ($project_id) {
                $query->where('project_id', '=' ,$project_id);})
            ->get();
        return response()->json($usersNotInProject);
    }

    public function addDeveloper(ProjectMemberRequest $request): Response
    {
        ProjectMember::create($request->validated());
        return response('Developer Added To Project', 200);
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
