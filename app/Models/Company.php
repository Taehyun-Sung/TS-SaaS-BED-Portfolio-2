<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'city_id',
        'state_id',
        'country_id',
        'logo',
        'user_id'
    ];

    public function positions() {
        return $this->hasMany(Position::class);
    }

}
