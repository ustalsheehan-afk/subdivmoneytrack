<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'subject',
        'category',
        'status',
        'module_type',
        'module_id',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function unreadMessagesCount()
    {
        return $this->messages()->where('is_read', false)->count();
    }

    public function module()
    {
        return $this->morphTo();
    }
}
