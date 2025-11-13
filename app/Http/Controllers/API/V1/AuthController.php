<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\APIController; 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends APIController
{

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email'=> 'required|email|unique:users,email',
            'password'=> 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,manager,user',
            'phone' => 'nullable|string|max:15',
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>$request->role,
            'phone'=>$request->phone ?? null,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        
        return $this->successResponse([
            'user'=>$user,
            'access_token'=>$token,
            'token_type'=>'Bearer',
        ], 'User registered successfully', 201);
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email'=> ['The credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'user'=>$user,
            'access_token'=>$token,
            'token_type'=>'Bearer',
        ], 'User logged in successfully');
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse(null, 'Logged out successfully');
    }

    // Get authenticated user
    public function me(Request $request)
    {
        return $this->successResponse($request->user(), 'Authenticated user data');
    }
}