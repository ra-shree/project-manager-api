<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\EmailMatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class ApiAuthenticationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', new EmailMatch],
            'password' => ['required', 'min:8', 'max:255', Rules\Password::defaults()],
        ]);

        $user = User::where('email', 'ilike', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($request->email)->plainTextToken;
        return response()->json(['message'=> 'Authenticated', 'token' => $token]);
    }

    public function destroy(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();
        return response('User Logged out', 200);
    }
}
