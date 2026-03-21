<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'amenity_id', // Added
        'type',
        'description',
        'photo',
        'priority',
        'status',
        'processed_at',
        'completed_at',
        'reservation_date', // Added
        'reservation_time', // Added
        'guest_count', // Added
        'equipment', // Added
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
        'equipment' => 'array', // Cast JSON to array
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id');
    }

    public function amenity()
    {
        return $this->belongsTo(Amenity::class, 'amenity_id');
    }

    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELLED = 'cancelled'; // Added

    public function isPending()  { return $this->status === self::STATUS_PENDING; }
    public function isApproved() { return $this->status === self::STATUS_APPROVED; }
    public function isRejected() { return $this->status === self::STATUS_REJECTED; }
}
