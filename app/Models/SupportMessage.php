<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'category',
        'message',
        'resident_attachment',
        'admin_reply',
        'admin_attachment',
        'status',
        'is_read_by_admin',
        'replied_at',
        'replied_by',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'is_read_by_admin' => 'boolean',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }
}
