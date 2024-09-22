<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function login(Request $request)
    {

        // Validate the login credentials
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],

        ]);

        \Log::info('Login attempt:', $request->only('email', 'password'));

        // Attempt to authenticate the user
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
                'data' => [],
            ], 401);
        }

        // Retrieve the authenticated users
        $user = Auth::user();

        // Generate a token for the user
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'user_type' => $user->user_type,
                    'token' => $token,
                ],
            ],
        ], 200);
    }

    public function destroy(Request $request)
    {
        // Revoke the user's token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
            'data' => [],
        ], 200);
    }
}
