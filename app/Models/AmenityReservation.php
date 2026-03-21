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
    ];

    protected $casts = [
        'date' => 'date',
        'equipment_addons' => 'array',
    ];

    public function amenity()
    {
        return $this->belongsTo(Amenity::class);
    }

    public function resident()
    {
        return $this->hasOne(Resident::class, 'user_id', 'resident_id');
    }
}
