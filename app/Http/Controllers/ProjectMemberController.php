<?php

namespace App\Http\Controllers;

use App\Models\ProjectMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectMemberController extends Controller
{
    // developers that aren't already in the project
    public function getDeveloper($project_id)
    {
        $usersNotInProject = User::where('role', '=', 'developer')
            ->whereDoesntHave('projects', function ($query) use ($project_id) {
                $query->where('project_id', '=' ,$project_id);})
            ->get();
        return response()->json($usersNotInProject);
    }
//    public function getNonMembers(int $project_id)
//    {
//        $usersNotInProject = User::whereDoesntHave('projects', function ($query) use ($project_id) {
//            $query->where('project_id', $project_id);
//        })->get();
//        return response()->json($usersNotInProject);
//    }

    public function addDeveloper(Request $request)
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
}
