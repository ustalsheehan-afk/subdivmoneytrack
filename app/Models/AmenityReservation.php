<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmenityReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'amenity_id',
        'resident_id',
        'customer_type',
        'guest_name',
        'guest_contact',
        'guest_email',
        'booking_source',
        'created_by_admin_id',
        'date',
        'start_time',
        'end_time',
        'time_slot',
        'guest_count',
        'equipment_addons',
        'total_price',
        'notes',
        'status',
        'payment_status',
        'payment_method',
        'payment_proof',
        'payment_reference_no',
        'rejection_reason',
        'verified_at',
        'verified_by',
        'cancelled_at',
        'cancelled_by',
        'cancellation_reason',
        'cancellation_type',
    ];

    protected $casts = [
        'date' => 'date',
        'equipment_addons' => 'array',
        'verified_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function amenity()
    {
        return $this->belongsTo(Amenity::class);
    }

    public function resident()
    {
        return $this->hasOne(Resident::class, 'user_id', 'resident_id');
    }

    public function createdByAdmin()
    {
        return $this->belongsTo(User::class, 'created_by_admin_id');
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function getCustomerNameAttribute(): string
    {
        if ($this->customer_type === 'non_resident') {
            return $this->guest_name ?: 'Walk-in Guest';
        }

        return optional($this->resident)->full_name ?? 'Unknown';
    }

    public function getCustomerContactAttribute(): string
    {
        if ($this->customer_type === 'non_resident') {
            return $this->guest_contact ?: 'N/A';
        }

        return optional($this->resident)->contact_number ?? 'N/A';
    }

    public function getCustomerEmailAttribute(): string
    {
        if ($this->customer_type === 'non_resident') {
            return $this->guest_email ?: '';
        }

        return optional($this->resident)->email ?? '';
    }

    public function getCustomerUnitAttribute(): string
    {
        if ($this->customer_type === 'non_resident') {
            return 'Non-Resident';
        }

        return trim((optional($this->resident)->block ?? '?') . '-' . (optional($this->resident)->lot ?? '?'));
    }

    public function getCustomerTypeLabelAttribute(): string
    {
        return $this->customer_type === 'non_resident' ? 'Non-Resident' : 'Resident';
    }

    public function getReferenceCodeAttribute(): string
    {
        return 'AR-' . str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Scope to retrieve only active (non-cancelled) reservations.
     * NOTE: Cancelled reservations are NEVER deleted - use this scope to exclude them when needed.
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'cancelled');
    }

    /**
     * Scope to retrieve only cancelled reservations for auditing.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}
