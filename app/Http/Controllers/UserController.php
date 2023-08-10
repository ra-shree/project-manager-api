<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $user =  User::where('id', '!=', '1')->orderByDesc('created_at')->get();
        return response()->json($user);
    }

    public function findByRole(string $user_role): JsonResponse
    {
        $users = [];
        if($user_role == 'developer' || $user_role == 'manager') {
            $users = User::where('role', '=', $user_role)->get();
        }
        return response()->json($users);
    }

    public function find(int $user_id): JsonResponse
    {
        $user = User::find($user_id)->with('projects')->get();
        return response()->json($user);
    }

    public function update(UserRequest $request, int $user_id): Response
    {
        $user = User::find($user_id);
        if($user) {
            $user->update($request->validated());
            return response('User Updated', 200);
        }
        return response('User does not exist', 401);
    }

    public function destroy(int $user_id): Response
    {
        $user = User::find($user_id);
        if($user) {
            $user->delete();
            return response('User Deleted', 200);
        }
        return response('User does not exist', 401);
    }
}
