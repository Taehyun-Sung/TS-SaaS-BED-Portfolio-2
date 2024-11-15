<?php

namespace App\Policies;

use App\Models\Position;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Class PositionPolicy
 *
 * This policy handles all actions related to the Position model for authorization.
 * It determines the access rights for viewing, creating, updating, deleting, and restoring
 * Position records based on the user's role and ownership.
 *
 * @group Position Policy
 *
 * @package App\Policies
 */
class PositionPolicy
{
    /**
     * Determine whether the user can view the model.
     *
     * This method checks whether a user has permission to view the details of a specific position.
     * Admin, SuperUser, and Staff users can always view the position.
     *
     * @param User $user The authenticated user attempting to view the position.
     * @param Position $position The position being accessed.
     *
     * @return bool Whether the user has permission to view the position.
     *
     * @example
     * // Returns true for admin, super user, or staff
     * $user->can('view', $position);
     */
    public function view(User $user, Position $position): bool
    {
        return $user->isSuperUser() || $user->isAdmin() || $user->isStaff();
    }

    /**
     * Determine whether the user can create models.
     *
     * This method checks if the user is allowed to create a new position. Admin, SuperUser, Staff,
     * and Client users are permitted to create a new position.
     *
     * @param User $user The authenticated user attempting to create a position.
     *
     * @return bool Whether the user can create a position.
     *
     * @example
     * // Returns true for admin, super user, staff, or client
     * $user->can('create', Position::class);
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isSuperUser() || $user->isStaff() || $user->isClient();
    }

    /**
     * Determine whether the user can update the model.
     *
     * This method checks if the user can update a specific position. Admin, SuperUser, Staff, and
     * the owner of the position (client) have permission to update it.
     *
     * @param User $user The authenticated user attempting to update the position.
     * @param Position $position The position being updated.
     *
     * @return \Illuminate\Auth\Access\Response The result of the authorization attempt.
     *
     * @example
     * // Returns Response::allow() for users who own the position or have higher privileges
     * $user->can('update', $position);
     */
    public function update(User $user, Position $position): Response
    {
        return $user->isClient() && $user->id === $position->user_id ||
        $user->isAdmin() || $user->isSuperUser() || $user->isStaff()
            ? Response::allow()
            : Response::deny('You do not own this listing');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * This method checks if the user can delete a specific position. Admin, SuperUser, Staff, and
     * the owner of the position (client) can delete it.
     *
     * @param User $user The authenticated user attempting to delete the position.
     * @param Position $position The position being deleted.
     *
     * @return \Illuminate\Auth\Access\Response The result of the authorization attempt.
     *
     * @example
     * // Returns Response::allow() for users who own the position or have higher privileges
     * $user->can('delete', $position);
     */
    public function delete(User $user, Position $position): Response
    {
        return $user->isClient() && $user->id === $position->user_id ||
        $user->isAdmin() || $user->isSuperUser() || $user->isStaff()
            ? Response::allow()
            : Response::deny('You do not own this listing');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * This method checks if the user can restore a previously deleted position. Admin, SuperUser,
     * Staff, and the owner of the position (client) can restore it.
     *
     * @param User $user The authenticated user attempting to restore the position.
     * @param Position $position The position being restored.
     *
     * @return \Illuminate\Auth\Access\Response The result of the authorization attempt.
     *
     * @example
     * // Returns Response::allow() for users who own the position or have higher privileges
     * $user->can('restore', $position);
     */
    public function restore(User $user, Position $position): Response
    {
        return $user->isClient() && $user->id === $position->user_id ||
        $user->isAdmin() || $user->isSuperUser() || $user->isStaff()
            ? Response::allow()
            : Response::deny('You do not own this listing');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * This method checks if the user can permanently delete a position. Admin, SuperUser, and Staff
     * are permitted to forcefully delete positions.
     *
     * @param User $user The authenticated user attempting to permanently delete the position.
     * @param Position $position The position being permanently deleted.
     *
     * @return bool Whether the user has permission to permanently delete the position.
     *
     * @example
     * // Returns true for admin, super user, or staff
     * $user->can('forceDelete', $position);
     */
    public function forceDelete(User $user, Position $position): bool
    {
        return $user->isAdmin() || $user->isStaff() || $user->isSuperUser();
    }

    /**
     * Determine whether the user can restore all models.
     *
     * This method checks if the user can restore all deleted positions. Admin, SuperUser, and Staff
     * are permitted to restore all positions.
     *
     * @param User $user The authenticated user attempting to restore all positions.
     *
     * @return bool Whether the user can restore all positions.
     *
     * @example
     * // Returns true for admin, super user, or staff
     * $user->can('restoreAll', Position::class);
     */
    public function restoreAll(User $user)
    {
        return $user->isAdmin() || $user->isStaff() || $user->isSuperUser();
    }

    /**
     * Determine whether the user can permanently delete all models.
     *
     * This method checks if the user can permanently delete all positions. Admin, SuperUser, and Staff
     * are permitted to forcefully delete all positions.
     *
     * @param User $user The authenticated user attempting to permanently delete all positions.
     *
     * @return bool Whether the user can permanently delete all positions.
     *
     * @example
     * // Returns true for admin, super user, or staff
     * $user->can('forceDeleteAll', Position::class);
     */
    public function forceDeleteAll(User $user)
    {
        return $user->isAdmin() || $user->isStaff() || $user->isSuperUser();
    }
}
