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
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

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
