<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        if ($request->has('role')) {
            $role = $request->query('role');
            $users = User::where('id', '!=', 1)->where('role', '=', $role)->get();

            return response()->json($users);
        }
        $users =  User::where('id', '!=', 1)->orderByDesc('created_at')->get();

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): Response
    {
        $user = User::create($request->validated());
        event(new Registered($user));
        return response('User Created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user): Response
    {
        $user->update($request->validated());
        return response('User Updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): Response
    {
        $user->delete();
        return response('User Deleted', 200);
    }
}
