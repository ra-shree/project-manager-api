<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'name' => ['required', 'string', 'max:255', 'min:5'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'username' => ['required', 'string', 'unique:users,username', 'max:60', 'min:5'],
            'phone' => ['required', 'string', 'unique:users,phone_number', 'size:10'],
            'agreement' => ['accepted'],
            'gender' => ['required', 'in:male,female,other']
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'username' => $request->username,
            'phone_number' => $request->phone,
            'gender' => $request->gender
        ]);

        event(new Registered($user));

        Auth::login($user);

        return response()->noContent();
    }
}
