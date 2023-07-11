<?php

namespace App\Http\Controllers;

use App\Models\User;
use DeepCopy\Filter\ReplaceFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
//    public function register(Request $request) {
//        $fields = $request->validate([
//            'name' => ['required', 'string', 'max:255', 'min:5'],
//            'email' => ['required', 'email', 'unique:users,email'],
//            'password' => ['required', 'string', 'confirmed', 'max:255', 'min:8'],
//            'username' => ['required', 'string', 'unique:users,username', 'max:60', 'min:5'],
//            'phone' => ['required', 'string', 'unique:users,phone_number', 'size:10'],
//            'agreement' => ['accepted'],
//            'gender' => ['required', 'in:male,female,other']
//        ]);
//
//        $user = User::create([
//            'name' => $fields['name'],
//            'email' => $fields['email'],
//            'password' => bcrypt($fields['password']),
//            'username' => $fields['username'],
//            'phone_number' => $fields['phone'],
//            'gender' => $fields['gender']
//        ]);
//
//        $token = $user->createToken('myapptoken')->plainTextToken;
//        return response([
//            'user' => $user,
//            'token' => $token],
//            201);
//    }
//
//    public function login(Request $request) {
//        $fields = $request->validate([
//            'email' => ['required', 'email'],
//            'password' => ['required', 'string', 'max:255', 'min:8'],
//        ]);
//
//        $user = User::where('email', $fields['email'])->first();
//        if (!$user || !Hash::check($fields['password'], $user->password)) {
//            return response([
//                'message'=> 'Email or Password is incorrect'
//            ], 401);
//        }
//
//        $token = $user->createToken('myapptoken')->plainTextToken;
//        return response([
//            'user' => $user,
//            'token' => $token],
//            201);
//    }
//
//    public function logout(Request $request)
//    {
//        auth()->user()->tokens()->delete();
//        return response(['message' => 'User is logged out'], 200);
//    }
}
