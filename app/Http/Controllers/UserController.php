<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $user =  User::where('id', '<>', '1')->orderByDesc('created_at')->get();
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

    public function update(Request $request, int $user_id): Response
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'min:3'],
            'last_name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user_id)],
            'password' => ['sometimes', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['developer', 'manager'])],
        ]);

        $user = User::find($user_id);
        if($user) {
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => $request->role,
            ]);
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
