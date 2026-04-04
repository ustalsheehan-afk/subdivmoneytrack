<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category',
        'priority',
        'date_posted',
        'is_pinned',
        'pin_duration',
        'pin_expires_at',
        'status',
        'image',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'date_posted' => 'datetime',
        'pin_expires_at' => 'datetime',
    ];

    public function readers()
    {
        return $this->belongsToMany(User::class, 'announcement_reads')
                    ->withPivot('read_at')
                    ->withTimestamps();
    }
}
