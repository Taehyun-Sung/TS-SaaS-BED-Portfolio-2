<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @group Company Management
 */
class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'city', 'state', 'country', 'logo'];

    /**
     * Get the positions associated with the company.
     *
     * ## Relationship: Positions
     * - **Type**: HasMany
     * - **Description**: A company can have multiple positions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    /**
     * Get the users associated with the company.
     *
     * ## Relationship: Users
     * - **Type**: HasMany
     * - **Description**: A company can have multiple users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

}
