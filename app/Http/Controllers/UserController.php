<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * ## Get Users
     * - **Endpoint**: `GET /users`
     * - **Description**: Retrieves all users.
     *
     * **Successful Response (200)**:
     * ```json
     * {
     *   "success": true,
     *   "message": "Users retrieved successfully",
     *   "data": [ ... ]  // Array of users
     * }
     * ```
     */
    public function index()
    {
        return response()->json(['success' => true, 'message' => 'Users retrieved successfully', 'data' => User::all()]);
    }

    /**
     * Display the specified user.
     *
     * ## Get User
     * - **Endpoint**: `GET /users/{id}`
     * - **Description**: Retrieves a user by ID.
     *
     * **Successful Response (200)**:
     * ```json
     * {
     *   "success": true,
     *   "message": "User retrieved successfully",
     *   "data": { ... }  // User details
     * }
     * ```
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['success' => true, 'message' => 'User retrieved successfully', 'data' => $user]);
    }

    /**
     * Store a newly created user.
     *
     * ## Create User
     * - **Endpoint**: `POST /users`
     * - **Description**: Creates a new user.
     *
     * **Request Body (JSON)**:
     * ```json
     * {
     *   "name": "John Doe",
     *   "email": "user@example.com",
     *   "password": "userpassword"
     * }
     * ```
     *
     * **Successful Response (201)**:
     * ```json
     * {
     *   "success": true,
     *   "message": "User created successfully",
     *   "data": { ... }  // User details
     * }
     * ```
     *
     * **Error Response (422)**:
     * ```json
     * {
     *   "success": false,
     *   "message": "The given data was invalid.",
     *   "data": {
     *     "email": ["The email has already been taken."]
     *   }
     * }
     * ```
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            // Add other validation rules as necessary
        ]);

        $user = User::create($request->all());
        return response()->json(['success' => true, 'message' => 'User created successfully', 'data' => $user], 201);
    }

    /**
     * Update the specified user.
     *
     * ## Update User
     * - **Endpoint**: `PUT /users/{id}`
     * - **Description**: Updates a user by ID.
     *
     * **Request Body (JSON)**:
     * ```json
     * {
     *   "name": "Updated Name",
     *   "email": "updated@example.com"
     * }
     * ```
     *
     * **Successful Response (200)**:
     * ```json
     * {
     *   "success": true,
     *   "message": "User updated successfully",
     *   "data": { ... }  // Updated user details
     * }
     * ```
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        return response()->json(['success' => true, 'message' => 'User updated successfully', 'data' => $user]);
    }

    /**
     * Remove the specified user.
     *
     * ## Delete User
     * - **Endpoint**: `DELETE /users/{id}`
     * - **Description**: Deletes a user by ID.
     *
     * **Successful Response (200)**:
     * ```json
     * {
     *   "success": true,
     *   "message": "User deleted successfully"
     * }
     * ```
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }
}
