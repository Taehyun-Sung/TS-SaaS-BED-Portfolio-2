<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @group User Management
 */
class User extends Authenticatable
{
    use HasFactory, SoftDeletes, HasApiTokens, HasRoles;

    protected $fillable = [
        'nickname',
        'given_name',
        'family_name',
        'email',
        'company_id',
        'user_type',
        'status',
        'password'

    ];

    /**
     * Get the company that owns the user.
     *
     * ## Relationship: Company
     * - **Type**: BelongsTo
     * - **Description**: A user belongs to one company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the positions associated with the user.
     *
     * ## Relationship: Positions
     * - **Type**: HasMany
     * - **Description**: A user can have multiple positions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function positions()
    {
        return $this->hasMany(Position::class);
    }
}
