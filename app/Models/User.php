<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'role_id',
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

    public function rbacRole()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function permissions()
    {
        return $this->rbacRole?->permissions();
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

    public function hasPermission(string $permissionKey): bool
    {
        $this->loadMissing('rbacRole.permissions');

        $role = $this->rbacRole;
        if (!$role) {
            return false;
        }

        $keys = $role->permissions->pluck('key')->all();
        if (in_array('*', $keys, true)) {
            return true;
        }

        return in_array($permissionKey, $keys, true);
    }
}
