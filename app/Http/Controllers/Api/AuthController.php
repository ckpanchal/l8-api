<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Role;
use JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) 
    {
        $user = User::create(array_merge(
                    $request->input(),
                    ['password' => bcrypt($request->password)]
                ));
        $role = Role::findByName('User');
        if ($role) {
            $user->assignRole($role);
        }
        return response()->json([
            'message' => 'User successfully registered.',
            'user' => $user
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['Auth error' => 'Unauthorized'], 401);
        }
        return $this->generateToken($token);
    }

    public function logout() 
    {
        auth()->logout();
        return response()->json(['message' => 'User logout successfully.']);
    }

    public function refresh() 
    {
        return $this->generateToken(auth()->refresh());
    }

    public function user() 
    {
        return response()->json(JWTAuth::user());
    }

    protected function generateToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => JWTAuth::user()
        ]);
    }
}
