<?php

namespace App\Policies;

use App\Models\Position;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PositionPolicy
{
//    /**
//     * Determine whether the user can view any models.
//     */
//    public function viewAny(User $user): bool
//    {
//        //
//    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Position $position): bool
    {
        return $user->isSuperUser() || $user->isAdmin() || $user->isStaff();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isSuperUser() || $user->isStaff() || $user->isClient();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Position $position): Response
    {
        return  $user->isClient() && $user->id === $position->user_id ||
        $user->isAdmin() || $user->isSuperUser() || $user->isStaff()
            ? Response::allow()
            : Response::deny('You do not own this listing');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Position $position): Response
    {
        return  $user->isClient() && $user->id === $position->user_id ||
        $user->isAdmin() || $user->isSuperUser() || $user->isStaff()
            ? Response::allow()
            : Response::deny('You do not own this listing');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Position $position): Response
    {
        return  $user->isClient() && $user->id === $position->user_id ||
        $user->isAdmin() || $user->isSuperUser() || $user->isStaff()
            ? Response::allow()
            : Response::deny('You do not own this listing');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Position $position): bool
    {
        return $user->isAdmin() || $user->isStaff() || $user->isSuperUser();
    }

    public function restoreAll(User $user)
    {
        return $user->isAdmin() || $user->isStaff() || $user->isSuperUser();
    }

    public function forceDeleteAll(User $user)
    {
        return $user->isAdmin() || $user->isStaff() || $user->isSuperUser();
    }
}
