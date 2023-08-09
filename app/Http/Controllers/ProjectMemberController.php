<?php

namespace App\Http\Controllers;

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

    public function addDeveloper(Request $request): Response
    {
        $request->validate([
            'project_id' => ['required', Rule::exists('projects', 'id')],
            'user_id' => ['required', Rule::exists('users', 'id')],
        ]);

        ProjectMember::create([
            'user_id' => $request->user_id,
            'project_id' => $request->project_id,
        ]);

        return response('Developer added to Project', 200);
    }

    public function removeDeveloper($project_id, $user_id): Response
    {
        $user = ProjectMember::where('project_id', '=', $project_id)->where('user_id', '=', $user_id)->first();
        if($user->exists) {
            $user->delete();
            return response('Member Removed', 200);
        }
        return response('Remove Member Failed', 400);
    }
}
