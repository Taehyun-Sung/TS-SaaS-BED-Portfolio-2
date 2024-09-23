<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @group Position Management
 */
class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'advertising_start_date',
        'advertising_end_date',
        'title',
        'description',
        'keywords',
        'min_salary',
        'max_salary',
        'currency',
        'benefits',
        'requirements',
        'position_type',
        'company_id', // foreign key to companies
        'user_id', // foreign key to users
    ];

    /**
     * Get the company that owns the position.
     *
     * ## Relationship: Company
     * - **Type**: BelongsTo
     * - **Description**: A position belongs to one company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company() {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user that owns the position.
     *
     * ## Relationship: User
     * - **Type**: BelongsTo
     * - **Description**: A position belongs to one user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

}

