<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

/**
 * UserPolicy Class
 *
 * This class is responsible for determining the authorization logic
 * for user-related actions such as viewing, creating, updating, deleting,
 * and restoring user accounts.
 *
 * Group: "User Management"
 */
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can browse the users.
     *
     * @param User $user The user making the request
     * @return \Illuminate\Auth\Access\Response
     *
     * @example
     * Request: GET /users
     * Response:
     * {
     *    "success": true,
     *    "message": "Authorized to browse users",
     *    "data": {}
     * }
     */
    public function browse(User $user)
    {
        return $user->isSuperUser() || $user->isAdmin() || $user->isStaff();
    }

    /**
     * Determine if the user can view the details of the target user.
     *
     * @param User $user The user making the request
     * @param User $targetUser The target user whose details are being viewed
     * @return \Illuminate\Auth\Access\Response
     *
     * @example
     * Request: GET /users/{id}
     * Response:
     * {
     *    "success": true,
     *    "message": "Authorized to view user details",
     *    "data": {
     *        "id": 1,
     *        "name": "John Doe",
     *        "email": "johndoe@example.com"
     *    }
     * }
     */
    public function view(User $user, User $targetUser)
    {
        return $user->id === $targetUser->id || $user->isSuperUser() || $user->isAdmin() || $user->isStaff();
    }

    /**
     * Determine if the user can update the target user's information.
     *
     * @param User $user The user making the request
     * @param User $targetUser The target user whose information is being updated
     * @return \Illuminate\Auth\Access\Response
     *
     * @example
     * Request: PUT /users/{id}
     * Response:
     * {
     *    "success": true,
     *    "message": "Authorized to update user information",
     *    "data": {
     *        "id": 1,
     *        "name": "John Updated",
     *        "email": "johnupdated@example.com"
     *    }
     * }
     */
    public function update(User $user, User $targetUser)
    {
        return $user->id === $targetUser->id || $user->isSuperUser() || $user->isAdmin() || $user->isStaff();
    }

    /**
     * Determine if the user can create a new user.
     *
     * @param User $user The user making the request
     * @return bool
     *
     * @example
     * Request: POST /users
     * Response:
     * {
     *    "success": true,
     *    "message": "Authorized to create new user",
     *    "data": {}
     * }
     */
    public function create(User $user)
    {
        return $user->isSuperUser() || $user->isAdmin() || $user->isStaff();
    }

    /**
     * Determine if the user can delete the target user.
     *
     * @param User $user The user making the request
     * @param User $targetUser The target user to be deleted
     * @return \Illuminate\Auth\Access\Response
     *
     * @example
     * Request: DELETE /users/{id}
     * Response:
     * {
     *    "success": true,
     *    "message": "User deleted successfully",
     *    "data": {}
     * }
     * Response (Client Denied):
     * {
     *    "success": false,
     *    "message": "Clients do not have permission to delete users.",
     *    "data": {}
     * }
     */
    public function delete(User $user, User $targetUser): Response
    {
        // Super-User: Full access to delete all users except themselves
        if ($user->isSuperUser() && $user->id !== $targetUser->id) {
            return Response::allow();
        }

        // Administrator: Can delete all users except themselves and super-users
        if ($user->isAdmin() && $user->id !== $targetUser->id && !$targetUser->isSuperUser()) {
            return Response::allow();
        }

        // Staff: Can only soft delete users except themselves and administrators/super-users
        if ($user->isStaff() && $user->id !== $targetUser->id && !$targetUser->isAdmin() && !$targetUser->isSuperUser()) {
            return Response::allow();
        }

        // Client: No access to delete users
        if ($user->isClient()) {
            return Response::deny('Clients do not have permission to delete users.');
        }

        // Default response for other cases
        return Response::deny('You do not have permission to delete this user.');
    }

    /**
     * Determine if the user can restore the target user account.
     *
     * @param User $user The user making the request
     * @param User $targetUser The target user whose account is being restored
     * @return \Illuminate\Auth\Access\Response
     *
     * @example
     * Request: POST /users/{id}/restore
     * Response:
     * {
     *    "success": true,
     *    "message": "User account restored successfully",
     *    "data": {}
     * }
     */
    public function restore(User $user, User $targetUser)
    {
        return $user->isSuperUser() || $user->isAdmin() || $user->isStaff();
    }

    /**
     * Determine if the user can force delete the target user account.
     *
     * @param User $user The user making the request
     * @param User $targetUser The target user whose account is being permanently deleted
     * @return bool
     *
     * @example
     * Request: DELETE /users/{id}/force
     * Response:
     * {
     *    "success": true,
     *    "message": "User account permanently deleted",
     *    "data": {}
     * }
     */
    public function forceDelete(User $user, User $targetUser)
    {
        return $user->isSuperUser();
    }

    /**
     * Determine if the user can force delete all user accounts.
     *
     * @param User $user The user making the request
     * @return bool
     *
     * @example
     * Request: DELETE /users/forceAll
     * Response:
     * {
     *    "success": true,
     *    "message": "All user accounts permanently deleted",
     *    "data": {}
     * }
     */
    public function forceDeleteAll(User $user)
    {
        return $user->isSuperUser();
    }
}
