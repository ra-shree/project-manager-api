<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $user =  User::where('id', '<>', '1')->orderByDesc('created_at')->get();
        return response()->json($user);
    }

    public function findByRole(string $role): JsonResponse
    {
        $users = [];
        if($role == 'developer' || $role == 'manager') {
            $users = User::where('role', '=', $role)->get();
        }
        return response()->json($users);
    }

    public function find(int $id): JsonResponse
    {
        $user = User::find($id)->with('projects')->get();
        return response()->json($user);
    }

    public function destroy(int $id): Response
    {
        $user = User::find($id);
        if($user) {
            $user->delete();
            return response('User Deleted', 200);
        }
        return response('User does not exist', 401);
    }
}
