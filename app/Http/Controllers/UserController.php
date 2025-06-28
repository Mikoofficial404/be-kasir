<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();
        if (! $user) {
            return response()->json([
                'message' => 'User Not Found',
                'success' => false,
            ], 422);
        }

        return response()->json([
            'message' => 'Get User Data Resource',
            'data' => $user,
            'success' => true,
        ], 200);

    }
}
