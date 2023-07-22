<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'min:3'],
            'last_name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['developer', 'manager', 'Developer', 'Manager'])],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        event(new Registered($user));
        return response('User Created', 201);
    }

    public function index(): JsonResponse
    {
        $user =  User::where('id', '<>', '1')->get();
        return response()->json($user);
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
