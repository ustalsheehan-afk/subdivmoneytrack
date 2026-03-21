<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'amenity_reservation_id',
        'user_id',
        'action',
        'details',
        'previous_status',
        'new_status',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function reservation()
    {
        return $this->belongsTo(AmenityReservation::class, 'amenity_reservation_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
