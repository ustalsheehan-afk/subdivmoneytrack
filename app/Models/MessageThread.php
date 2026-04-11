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
        'intent',
        'status',
        'priority',
        'assigned_to',
        'module_type',
        'module_id',
        'metadata',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'metadata' => 'array',
    ];

    protected static function booted()
    {
        static::created(function ($thread) {
            $intelligence = app(\App\Services\SupportIntelligenceService::class);
            // We assume the first message is created immediately after the thread
            // This might need a listener on the Message model instead if created separately
        });
    }

    public function assignedAdmin()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

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

    public function unreadMessagesCount($senderTypes = null)
    {
        $query = $this->messages()->where('is_read', false);

        if (!is_null($senderTypes)) {
            $query->whereIn('sender_type', (array) $senderTypes);
        }

        return $query->count();
    }

    public function module()
    {
        return $this->morphTo();
    }
}
