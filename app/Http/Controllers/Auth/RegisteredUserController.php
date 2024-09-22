<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {

        // Validate the incoming request
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'user_type' => ['required', 'in:applicant,client,staff,administrator,super-user'], //
        ]);


        // Create the new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type, // Store user type
            'given_name' => $request->given_name,
            'family_name' => $request->family_name,
            'status' => $request->status
        ]);
        \Log::info($request->all());


        // Assign role based on user type
        $user->assignRole($request->user_type); // Directly assign role matching the user type

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'nickname' => $user->nickname,
                    'email' => $user->email,
                    'user_type' => $user->user_type,
                ],
            ],
        ], 201);
    }
}
