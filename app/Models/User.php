<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'active',
        'must_change_password',
        'lot_unit',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'must_change_password' => 'boolean',
    ];

    /* -----------------------------
       RELATIONSHIPS
    ----------------------------- */
    public function resident()
{
    return $this->hasOne(Resident::class, 'user_id'); 
}


    /* Shortcut to all requests through resident */
    public function requests()
    {
        return $this->hasManyThrough(
            RequestModel::class,
            Resident::class,
            'user_id',      // FK on residents table
            'resident_id',  // FK on requests table
            'id',           // Local key on users table
            'id'            // Local key on residents table
        );
    }

    /* -----------------------------
       ROLE HELPERS
    ----------------------------- */
    public function isAdmin() { return $this->role === 'admin'; }
    public function isResident() { return $this->role === 'resident'; }
}
