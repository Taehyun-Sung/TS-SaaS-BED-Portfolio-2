<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * Retrieve all users in the system. Only authorized users can access this resource.
     *
     * @authenticated
     * @response 200 scenario="Success" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "nickname": "JohnDoe",
     *       "given_name": "John",
     *       "family_name": "Doe",
     *       "email": "john@example.com",
     *       "company_id": 1,
     *       "user_type": "administrator",
     *       "status": "active"
     *     }
     *   ],
     *   "message": "Users retrieved successfully"
     * }
     *
     */
    public function index()
    {
        Gate::authorize('browse', User::class);
        $users = User::all();
        return ApiResponseClass::sendResponse(
            $users,
            'Users retrieved successfully',
            200
        );
    }

    /**
     * Store a newly created user in storage.
     *
     * Validates the provided data and creates a new user in the system.
     *
     * @bodyParam nickname string The user's nickname (optional). Example: JohnDoe
     * @bodyParam given_name string required The user's first name. Example: John
     * @bodyParam family_name string required The user's last name. Example: Doe
     * @bodyParam email string required The user's email. Must be unique. Example: john@example.com
     * @bodyParam password string required Minimum 8 characters. Example: password123
     * @bodyParam company_id integer The ID of the company the user belongs to (optional). Example: 1
     * @bodyParam user_type string required The role of the user (client, staff, applicant, administrator, super-user). Example: administrator
     * @bodyParam status string required The user's status (active, unconfirmed, suspended, banned, unknown). Example: active
     *
     * @authenticated
     * @response 201 scenario="User created successfully" {
     *   "id": 2,
     *   "nickname": "JaneDoe",
     *   "given_name": "Jane",
     *   "family_name": "Doe",
     *   "email": "jane@example.com",
     *   "user_type": "client",
     *   "status": "active"
     * }
     */
    public function store(Request $request)
    {
        Gate::authorize('create', User::class);
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
        $validateData['password'] = bcrypt($validateData['password']);
        $user = User::create($validateData);
        return ApiResponseClass::sendResponse(
            $user,
            'User created successfully',
            201
        );
    }

    /**
     * Display the specified user.
     *
     * Show the details of a specific user by ID.
     *
     * @urlParam user int required The ID of the user. Example: 1
     *
     * @authenticated
     * @response 200 scenario="Success" {
     *   "id": 1,
     *   "nickname": "JohnDoe",
     *   "given_name": "John",
     *   "family_name": "Doe",
     *   "email": "john@example.com",
     *   "user_type": "administrator",
     *   "status": "active"
     * }
     */
    public function show(User $user)
    {
        Gate::authorize('view', $user);
        return ApiResponseClass::sendResponse(
            $user,
            "User retrieved successfully",
            200
        );
    }

    /**
     * Update the specified user in storage.
     *
     * Validates and updates the user’s details.
     *
     * @urlParam user int required The ID of the user. Example: 1
     * @bodyParam given_name string required The user's first name. Example: John
     * @bodyParam family_name string required The user's last name. Example: Doe
     * @bodyParam email string required The user's email. Must be unique. Example: john@example.com
     * @bodyParam password string required Minimum 8 characters. Example: password123
     * @bodyParam company_id integer The ID of the company the user belongs to (optional). Example: 1
     * @bodyParam user_type string required The role of the user (client, staff, applicant, administrator, super-user). Example: administrator
     * @bodyParam status string required The user's status (active, unconfirmed, suspended, banned, unknown). Example: active
     *
     * @authenticated
     * @response 200 scenario="Success" {
     *   "id": 1,
     *   "nickname": "JohnDoe",
     *   "given_name": "John",
     *   "family_name": "Doe",
     *   "email": "john@example.com",
     *   "user_type": "administrator",
     *   "status": "active"
     * }
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user);
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
        $validateData['password'] = bcrypt($validateData['password']);
        $user->update($validateData);

        return ApiResponseClass::sendResponse(
            $user,
            'User updated successfully',
            200
        );
    }

    /**
     * Remove the specified user from storage (soft delete).
     *
     * Marks the user as deleted (soft delete) and prevents them from accessing the system.
     *
     * @urlParam user int required The ID of the user. Example: 1
     *
     * @authenticated
     * @response 200 scenario="Success" {
     *   "id": 1,
     *   "nickname": "JohnDoe",
     *   "given_name": "John",
     *   "family_name": "Doe",
     *   "email": "john@example.com",
     *   "user_type": "administrator",
     *   "status": "deleted"
     * }
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);
        $user->delete();
        return ApiResponseClass::sendResponse(
            $user,
            'User deleted successfully',
            200
        );
    }

    /**
     * Restore a soft-deleted user.
     *
     * Restores a user that was previously soft-deleted.
     *
     * @urlParam id int required The ID of the user to restore. Example: 1
     *
     * @authenticated
     * @response 200 scenario="User restored successfully" {
     *   "id": 1,
     *   "nickname": "JohnDoe",
     *   "given_name": "John",
     *   "family_name": "Doe",
     *   "email": "john@example.com",
     *   "status": "active"
     * }
     */
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        Gate::authorize('restore', $user);
        $user->restore();
        return ApiResponseClass::sendResponse(
            $user,
            'User restored successfully',
            200
        );
    }

    /**
     * Forcefully delete all users except the current user.
     *
     * Permanently deletes all users in the system except the currently logged-in user.
     *
     * @authenticated
     * @response 200 scenario="Success" {
     *   "success": true,
     *   "message": "All users except the current user have been deleted successfully."
     * }
     */
    public function destroyAll()
    {
        Gate::authorize('forceDeleteAll', User::class);
        $currentUserId = auth()->id();
        User::where('id', '!=', $currentUserId)->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'All users except the current user have been deleted successfully.'
        ], 200);

    }
}
