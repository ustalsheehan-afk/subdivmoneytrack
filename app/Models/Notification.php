<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'admin_id',
        'title',
        'message',
        'type',
        'link',
        'is_read',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
