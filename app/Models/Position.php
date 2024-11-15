<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'advertising_start_date',
        'advertising_end_date',
        'title',
        'description',
        'keywords',
        'min_salary',
        'max_salary',
        'salary_currency',
        'company_id',
        'user_id',
        'benefits',
        'requirements',
        'position_type'
    ];

    // a position belongs to a company
    public function company() {
        return $this->belongsTo(Company::class);
    }

    // a position belongs to a user
    public function user() {
        return $this->belongsTo(User::class);
    }
}
