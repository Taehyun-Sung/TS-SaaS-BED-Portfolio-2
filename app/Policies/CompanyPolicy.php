<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * @group Policies - Company
 *
 * This policy class contains authorization logic to determine whether a user
 * can perform various actions on the Company model. The actions include viewing,
 * creating, updating, deleting, restoring, and force-deleting companies.
 *
 * The policies are used to determine access based on user roles, including Admin,
 * SuperUser, Staff, and Client.
 */
class CompanyPolicy
{
    /**
     * Determine whether the user can view any company models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     * @response 200 {
     *     "success": true,
     *     "message": "User is authorized to view any company.",
     *     "data": {}
     * }
     * @response 403 {
     *     "success": false,
     *     "message": "User is not authorized to view any company.",
     *     "data": {}
     * }
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin() || $user->isSuperUser() || $user->isStaff() || $user->isClient();
    }

    /**
     * Determine whether the user can view a specific company model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company  $company
     * @return bool
     * @response 200 {
     *     "success": true,
     *     "message": "User is authorized to view the company.",
     *     "data": {}
     * }
     * @response 403 {
     *     "success": false,
     *     "message": "User is not authorized to view the company.",
     *     "data": {}
     * }
     */
    public function view(User $user, Company $company): bool
    {
        return $user->isClient() && $user->id === $company->user_id ||
            $user->isAdmin() || $user->isSuperUser() || $user->isStaff();
    }

    /**
     * Determine whether the user can create a new company model.
     *
     * @param  \App\Models\User  $user
     * @return bool
     * @response 200 {
     *     "success": true,
     *     "message": "User is authorized to create a new company.",
     *     "data": {}
     * }
     * @response 403 {
     *     "success": false,
     *     "message": "User is not authorized to create a new company.",
     *     "data": {}
     * }
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isSuperUser() || $user->isStaff() || $user->isClient();
    }

    /**
     * Determine whether the user can update a specific company model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Auth\Access\Response
     * @response 200 {
     *     "success": true,
     *     "message": "User is authorized to update the company.",
     *     "data": {}
     * }
     * @response 403 {
     *     "success": false,
     *     "message": "User is not authorized to update the company.",
     *     "data": {}
     * }
     */
    public function update(User $user, Company $company): Response
    {
        return  $user->isClient() && $user->id === $company->user_id ||
        $user->isAdmin() || $user->isSuperUser() || $user->isStaff()
            ? Response::allow()
            : Response::deny('You do not own this company');
    }

    /**
     * Determine whether the user can delete a specific company model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Auth\Access\Response
     * @response 200 {
     *     "success": true,
     *     "message": "User is authorized to delete the company.",
     *     "data": {}
     * }
     * @response 403 {
     *     "success": false,
     *     "message": "User is not authorized to delete the company.",
     *     "data": {}
     * }
     */
    public function delete(User $user, Company $company): Response
    {
        return  $user->isClient() && $user->id === $company->user_id ||
        $user->isAdmin() || $user->isSuperUser() || $user->isStaff()
            ? Response::allow()
            : Response::deny('You do not own this company');
    }

    /**
     * Determine whether the user can restore a soft-deleted company model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company  $company
     * @return bool
     * @response 200 {
     *     "success": true,
     *     "message": "User is authorized to restore the company.",
     *     "data": {}
     * }
     * @response 403 {
     *     "success": false,
     *     "message": "User is not authorized to restore the company.",
     *     "data": {}
     * }
     */
    public function restore(User $user, Company $company): bool
    {
        return $user->isAdmin() || $user->isStaff() || $user->isSuperUser();
    }

    /**
     * Determine whether the user can permanently delete a company model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company  $company
     * @return bool
     * @response 200 {
     *     "success": true,
     *     "message": "User is authorized to permanently delete the company.",
     *     "data": {}
     * }
     * @response 403 {
     *     "success": false,
     *     "message": "User is not authorized to permanently delete the company.",
     *     "data": {}
     * }
     */
    public function forceDelete(User $user, Company $company): bool
    {
        return $user->isAdmin() || $user->isStaff() || $user->isSuperUser();
    }

    /**
     * Determine whether the user can restore all company models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     * @response 200 {
     *     "success": true,
     *     "message": "User is authorized to restore all companies.",
     *     "data": {}
     * }
     * @response 403 {
     *     "success": false,
     *     "message": "User is not authorized to restore all companies.",
     *     "data": {}
     * }
     */
    public function restoreAll(User $user)
    {
        return $user->isAdmin() || $user->isStaff() || $user->isSuperUser();
    }

    /**
     * Determine whether the user can permanently delete all company models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     * @response 200 {
     *     "success": true,
     *     "message": "User is authorized to permanently delete all companies.",
     *     "data": {}
     * }
     * @response 403 {
     *     "success": false,
     *     "message": "User is not authorized to permanently delete all companies.",
     *     "data": {}
     * }
     */
    public function forceDeleteAll(User $user)
    {
        return $user->isAdmin() || $user->isStaff() || $user->isSuperUser();
    }
}
