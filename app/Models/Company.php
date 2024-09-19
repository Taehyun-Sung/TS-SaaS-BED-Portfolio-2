<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'city', 'state', 'country', 'logo'];

    // Relationship with Position
    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    // Relationship with User
    public function users()
    {
        return $this->hasMany(User::class);
    }

}
