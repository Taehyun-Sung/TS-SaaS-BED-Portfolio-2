<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * This endpoint allows a user to register with the provided details. A token is generated upon successful registration.
     *
     * @bodyParam nickname string optional User's nickname. Max length: 255 characters.
     * @bodyParam given_name string required User's given name. Max length: 255 characters.
     * @bodyParam family_name string required User's family name. Max length: 255 characters.
     * @bodyParam email string required User's email address. Must be unique.
     * @bodyParam password string required User's password. Min length: 8 characters.
     * @bodyParam company_id int optional ID of the company associated with the user.
     * @bodyParam user_type string required Type of user (client, staff, applicant, administrator, super-user).
     * @bodyParam status string required Status of the user (active, unconfirmed, suspended, banned, unknown).
     * @response 201 scenario="Successful registration" {
     *    "status": "success",
     *    "data": {
     *        "id": 1,
     *        "nickname": "JohnDoe",
     *        "given_name": "John",
     *        "family_name": "Doe",
     *        "email": "john.doe@example.com",
     *        "company_id": 1,
     *        "user_type": "client",
     *        "status": "active",
     *        "token": "generated_token_here"
     *    },
     *    "message": "You are registered successfully with token generated_token_here"
     * }
     * @response 422 scenario="Validation Error" {
     *    "status": "error",
     *    "message": "The given data was invalid.",
     *    "errors": {
     *        "email": ["The email has already been taken."]
     *    }
     * }
     */
    public function register(Request $request) {
        $validateData = $request->validate([
            'nickname' => 'nullable|string|max:255',
            'given_name' => 'required|string|max:255',
            'family_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'company_id' => 'nullable|exists:companies,id',
            'user_type' => 'required|in:client,staff,applicant,administrator,super-user',
            'status' => 'required|in:active,unconfirmed,suspended,banned,unknown',
        ]);
        $validateData['password'] = Hash::make($validateData['password']);
        $user = User::create($validateData);
        $token = $user->createToken($user->given_name);
//        return ApiResponseClass::sendResponse(
//            $user,
//            "You are registered successfully with token {$token->plainTextToken}",
//            201
//        );
        return [
            'user' => $user,
            'message' => "You are registered successfully.",
            'token' => $token->plainTextToken
        ];

    }

    /**
     * Login a user.
     *
     * This endpoint allows a user to login with their email and password. A token is generated upon successful login.
     *
     * @bodyParam email string required User's email address.
     * @bodyParam password string required User's password.
     * @response 200 scenario="Successful login" {
     *    "status": "success",
     *    "data": {
     *        "id": 1,
     *        "nickname": "JohnDoe",
     *        "given_name": "John",
     *        "family_name": "Doe",
     *        "email": "john.doe@example.com",
     *        "token": "generated_token_here"
     *    },
     *    "message": "You are logged in successfully with token generated_token_here"
     * }
     * @response 401 scenario="Unauthorized" {
     *    "status": "error",
     *    "message": "The provided credentials are incorrect"
     * }
     */
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)) {
            return ApiResponseClass::sendResponse(
                null,
                "The provided credentials are incorrect",
                401
            );
        }
        $token = $user->createToken($user->given_name);

//        return ApiResponseClass::sendResponse(
//            $user,
//            "You are logged in successfully with token {$token->plainTextToken}",
//            200
//        );
        return [
            'user' => $user,
            'message' => "You are logged in successfully.",
            'token' => $token->plainTextToken
        ];
    }

    /**
     * Logout the user.
     *
     * This endpoint allows the user to logout by deleting their authentication tokens.
     *
     * @response 200 scenario="Successful logout" {
     *    "status": "success",
     *    "message": "You are logged out successfully."
     * }
     */
    public function logout(Request $request) {
        $request->user()->tokens()->delete();
//        return ApiResponseClass::sendResponse(
//            null,
//            "You are logged out successfully.",
//            200
//        );
        return [
            'message' => "You are logged out successfully."
        ];

    }
}
