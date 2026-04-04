<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_thread_id',
        'sender_type',
        'sender_id',
        'body',
        'attachment',
        'is_read',
        'is_internal',
        'metadata',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_internal' => 'boolean',
        'metadata' => 'array',
    ];

    protected static function booted()
    {
        static::created(function ($message) {
            // If it's the first message in a thread from a resident, analyze the thread
            if ($message->sender_type === Resident::class) {
                $thread = $message->thread;
                if ($thread->messages()->count() === 1) {
                    $intelligence = app(\App\Services\SupportIntelligenceService::class);
                    $intelligence->analyzeThread($thread, $message->body);
                }
            }
        });
    }

    public function thread()
    {
        return $this->belongsTo(MessageThread::class, 'message_thread_id');
    }

    public function sender()
    {
        return $this->morphTo();
    }

    public function isFromAdmin()
    {
        return $this->sender_type === User::class || $this->sender_type === Admin::class;
    }

    public function isFromResident()
    {
        return $this->sender_type === Resident::class;
    }
}
