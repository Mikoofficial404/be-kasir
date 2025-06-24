<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(HttpRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:100',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'success' => false,
            ], 422);
        }

        $credentials = $request->only('username', 'password');

        if (! $token = auth()->guard('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Maaf Username atau Password Salah',
                'success' => false,
            ], 401);
        }

        return response()->json([
            'message' => 'Login Success',
            'success' => true,
            'token' => $token,
            'user' => auth()->guard('api')->user(),
        ], 200);
    }

    public function logout(HttpRequest $request)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Logout Success',
            ], 200);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Logout Failed',
            ], 500);
        }
    }
}
