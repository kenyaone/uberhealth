<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'     => 'required|string|min:3|max:50|unique:users,username',
            'display_name' => 'required|string|max:100',
            'password'     => 'required|string|min:6',
            'email'        => 'nullable|email|unique:users,email',
            'phone'        => 'nullable|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $role = in_array($request->role, ['user', 'professional']) ? $request->role : 'user';

        $user = User::create([
            'username'          => $request->username,
            'display_name'      => $request->display_name,
            'password'          => $request->password, // hashed by cast
            'email'             => $request->email,
            'phone'             => $request->phone,
            'role'              => $role,
            'is_anonymous_mode' => true,
        ]);

        $token = JWTAuth::fromUser($user);
        $refreshToken = JWTAuth::fromUser($user, ['type' => 'refresh']);

        return response()->json([
            'message' => 'Account created successfully',
            'user'    => $user,
            'access'  => $token,
            'refresh' => $refreshToken,
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        try {
            if (!$token = auth('api')->attempt($credentials)) {
                return response()->json(['error' => 'Invalid username or password'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        $user = auth('api')->user();

        return response()->json([
            'message' => 'Login successful',
            'user'    => $user,
            'access'  => $token,
            'refresh' => $token, // same token; refresh via /auth/refresh
        ]);
    }

    public function logout(Request $request)
    {
        try {
            auth('api')->logout();
            return response()->json(['message' => 'Logged out successfully']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout'], 500);
        }
    }

    public function me(Request $request)
    {
        $user = auth('api')->user();
        return response()->json(['user' => $user]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'display_name'     => 'sometimes|string|max:100',
            'email'            => 'sometimes|nullable|email|unique:users,email,' . $user->id,
            'phone'            => 'sometimes|nullable|string|max:15',
            'is_anonymous_mode'=> 'sometimes|boolean',
            'avatar'           => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user->update($request->only([
            'display_name', 'email', 'phone', 'is_anonymous_mode', 'avatar'
        ]));

        return response()->json(['message' => 'Profile updated', 'user' => $user->fresh()]);
    }

    public function refresh(Request $request)
    {
        try {
            $token = auth('api')->refresh();
            return response()->json(['access' => $token]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not refresh token'], 401);
        }
    }
}
