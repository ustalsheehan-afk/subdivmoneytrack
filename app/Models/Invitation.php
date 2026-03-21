<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Invitation extends Model
{
    use HasFactory;

    // Status Constants
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';

    // Delivery Constants
    const DELIVERY_PENDING = 'pending';
    const DELIVERY_SENT = 'sent';
    const DELIVERY_FAILED = 'failed';

    protected $fillable = [
        'first_name',
        'last_name',
        'resident_id',
        'email',
        'phone',
        'token',
        'status',
        'expires_at',
        'accepted_at',
        'last_sent_at',
        'email_status',
        'sms_status',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
        'last_sent_at' => 'datetime',
    ];

    /**
     * Fallback to resident name if empty
     */
    public function getFirstNameAttribute($value)
    {
        if (empty($value) && $this->resident_id) {
            return $this->resident?->first_name;
        }
        return $value;
    }

    public function getLastNameAttribute($value)
    {
        if (empty($value) && $this->resident_id) {
            return $this->resident?->last_name;
        }
        return $value;
    }

    /**
     * Relationship to Resident
     */
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * RULE: Validation must ONLY depend on:
     * 1. token exists (handled by controller lookup)
     * 2. status === "pending"
     * 3. expires_at > now
     */
    public function isValid()
    {
        return $this->status === self::STATUS_PENDING && !$this->isExpired();
    }

    /**
     * RULE: isExpired() must ONLY check expires_at chronologically.
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Scope for valid invitations
     */
    public function scopeValid($query)
    {
        return $query->where('status', self::STATUS_PENDING)
                     ->where('expires_at', '>', Carbon::now());
    }
}
