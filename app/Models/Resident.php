<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Resident extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'residents';

    /**
     * Fillable attributes for mass assignment
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'contact_number',
        'block',
        'lot',
        'move_in_date',
        'status',
        'photo',
        'membership_type',
        'property_type',
        'lot_area',
        'floor_area',
    ];

    /**
     * Hidden attributes for arrays / JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'move_in_date' => 'date',
        'move_history' => 'array',
        'lot_area' => 'decimal:2',
        'floor_area' => 'decimal:2',
    ];

    /**
     * Get email, fallback to user email
     */
    public function getEmailAttribute($value)
    {
        if (empty($value) && $this->user_id) {
            return $this->user?->email;
        }
        return $value;
    }

    /**
     * Automatically hash password when set
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::needsRehash($value)
                ? Hash::make($value)
                : $value;
        }
    }

    /**
     * Full name accessor
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the total balance for the resident (sum of unpaid dues and pending penalties)
     */
    public function getTotalBalanceAttribute()
    {
        $unpaidDues = $this->dues()->where('status', Due::STATUS_UNPAID)->sum('amount');
        $pendingPenalties = $this->penalties()->where('status', Penalty::STATUS_PENDING)->sum('amount');
        
        return $unpaidDues + $pendingPenalties;
    }

    /**
     * Get the payment status (Good Standing vs With Balance)
     */
    public function getPaymentStatusAttribute()
    {
        return $this->total_balance <= 0 ? 'Good Standing' : 'With Balance';
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function supportMessages()
    {
        return $this->hasMany(SupportMessage::class);
    }

    /**
     * Relationships
     */

    // Penalties
    public function penalties()
    {
        return $this->hasMany(Penalty::class, 'resident_id');
    }

    // Dues
    public function dues()
    {
        return $this->hasMany(Due::class, 'resident_id');
    }

    // Payments
    public function payments()
    {
        return $this->hasMany(Payment::class, 'resident_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship to Invitations
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * Check if the resident has a linked user account
     */
    public function hasAccount()
    {
        return !is_null($this->user_id);
    }
}
