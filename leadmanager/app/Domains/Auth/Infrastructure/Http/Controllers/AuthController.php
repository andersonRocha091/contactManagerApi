<?php

namespace App\Domains\Auth\Infrastructure\Http\Controllers;

use App\Domains\Auth\Domain\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Domains\Auth\Services\UserService;

class AuthController extends Controller
{
    protected $userService;
    protected $jwtAuth;
    protected $auth;

    public function __construct(UserService $userService, JWTAuth $jwtAuth, Auth $auth)
    {
        $this->userService = $userService;
        $this->jwtAuth = $jwtAuth;
        $this->auth = $auth;
    }

    public function register(Request $request)
    {
        $validator = $this->validateRegister($request);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = $this->userService->createUser($request->all());
        $token = $this->jwtAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = $this->auth::guard('api')->attempt($credentials)) {
                return response()->json(['error' => 'Invalid Credentials'], 401);
            }

            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $this->auth::guard('api')->factory()->getTTL() * 60
            ]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }

    public function getUser()
    {
        try {
            if (! $user = $this->jwtAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        return response()->json(compact('user'));
    }

    public function logout()
    {
        $this->jwtAuth::invalidate($this->jwtAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function validateRegister(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
    }
}