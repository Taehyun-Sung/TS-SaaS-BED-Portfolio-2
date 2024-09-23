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
     * Register a new user.
     *
     * ## User Registration
     * - **Endpoint**: `POST /register`
     * - **Description**: Registers a new user.
     *
     * **Request Body (JSON)**:
     * ```json
     * {
     *   "name": "Taehyun",
     *   "email": "Taehyun@example.com",
     *   "password": "password",
     *   "user_type": "client"
     * }
     * ```
     *
     * **Successful Response (201)**:
     * ```json
     * {
     *   "success": true,
     *   "message": "User registered successfully",
     *   "data": {
     *     "user": {
     *       "id": 1,
     *       "nickname": null,
     *       "email": "Taehyun@example.com",
     *       "user_type": "client"
     *     }
     *   }
     * }
     * ```
     *
     * **Error Responses**:
     * - **Validation Errors (422)**:
     * ```json
     * {
     *   "success": false,
     *   "message": "The given data was invalid.",
     *   "data": {
     *     "email": ["The email has already been taken."],
     *     "password": ["The password must be at least 8 characters."]
     *   }
     * }
     * ```
     */
    public function register(Request $request)
    {

        // Validate the incoming request
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8'],
            'user_type' => ['required', 'in:applicant,client,staff,administrator,super-user'], //
        ]);

        \Log::info('Registering user:', $request->all());

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
        \Log::info('Registering user:', $request->all());


        // Assign role based on user type
        $user->assignRole($request->user_type); // Directly assign role matching the user type
        \Log::info('Registered user password:', ['password' => $user->password]);


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
