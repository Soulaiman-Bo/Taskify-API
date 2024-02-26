<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json([
                'user' => $user,
                'authorization' => [
                    'token' => $request->user()->createToken('ApiToken')->plainTextToken,
                    'type' => 'bearer',
                ]
            ]);
        }
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'access_token' => $request->user()->createToken('api')->plainTextToken,
        ]);
    }

}
