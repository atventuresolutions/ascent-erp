<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login the user
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validate = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::whereEmail($validate['email'])->first();
        if ($user) {
            if (Hash::check($validate['password'], $user->password)) {
                return response()->json([
                    'message' => 'Login successful',
                    'data'    => [
                        'token' => $user->createToken(
                            'auth_token'
                        )->plainTextToken,
                    ],
                ]);
            }
        }

        return response()->json([
            'message' => 'Credentials mismatch',
        ], 401);
    }

    /**
     * Logout the user (Revoke the token)
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful',
        ]);
    }
}
