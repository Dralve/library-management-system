<?php

namespace App\Http\Controllers\Auth\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , [
            'except' => [
                'login',
                'register'
            ]
        ]);
    }
    
     /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        if (Auth::check() && Auth::user()->role === 'admin') {
            return response()->json([
                'status' => 'success',
                'user' => $user,
                'message' => 'User registered successfully'
            ], 201);
        }

        $token = Auth::login($user);

        return $this->responseWithToken($token, $user);
    }

    public function login(LoginRequest $request)
    {
        $token = Auth::attempt($request->validated());

        if ($token){
            return $this->responseWithToken($token, Auth::user());
        }else{
            return response()->json([
                'message' => 'invalid credentials'
            ], 401);
        }
    }

    public function responseWithToken($token, $user)
    {
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'token' => $token,
            'type' => 'bearer'
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'user logged out successfully'
        ], 200);
    }
}
