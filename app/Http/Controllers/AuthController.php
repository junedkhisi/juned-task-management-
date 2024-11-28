<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        if (empty($olduser)) {
            return response()->json(["status" => false, "message" => "email allready exist"]);
        }
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        return response()->json([
            "status" => true,
            'message' => 'User Registered Successfully',
            'user' => $user,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->accessToken;
            return response()->json([
                "status" => true,
                'message' => 'Login Success',
                'token' => $token
            ]);
        }

        return response()->json([
            "status" => false,
            'message' => 'Invalid Credentials'
        ], 401);
    }
}
