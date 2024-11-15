<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;
    public function browse(User $user)
    {
        return $user->isSuperUser() || $user->isAdmin() || $user->isStaff();
    }

    public function view(User $user, User $targetUser)
    {
        return $user->id === $targetUser->id || $user->isSuperUser() || $user->isAdmin() || $user->isStaff();
    }

    public function update(User $user, User $targetUser)
    {
        return $user->id === $targetUser->id || $user->isSuperUser() || $user->isAdmin() || $user->isStaff();
    }

    public function create(User $user)
    {
        return $user->isSuperUser() || $user->isAdmin() || $user->isStaff();
    }

    public function delete(User $user, User $targetUser) : Response
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



    public function restore(User $user, User $targetUser)
    {
        return $user->isSuperUser() || $user->isAdmin() || $user->isStaff();
    }

    public function forceDelete(User $user, User $targetUser)
    {
        return $user->isSuperUser();
    }

    public function forceDeleteAll(User $user)
    {
        return $user->isSuperUser();
    }
}
