<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    // A position belongs to a company
    public function company() {
        return $this->belongsTo(Company::class);
    }

    // A position belongs to a user
    public function user() {
        return $this->belongsTo(User::class);
    }

}

