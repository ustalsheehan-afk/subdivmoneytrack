<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'gallery',
        'hours',
        'holiday_hours',
        'default_date',
        'capacity',
        'max_capacity',
        'rules_path',
        'booking_rules',
        'availability',
        'days_available',
        'time_slots',
        'equipment',
        'slot_type',
        'status',
        'price',
        'is_available',
        'highlight',
        'buffer_minutes',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'availability' => 'array',
        'booking_rules' => 'array',
        'equipment' => 'array',
        'days_available' => 'array',
        'time_slots' => 'array',
        'gallery' => 'array',
        'default_date' => 'date',
        'highlight' => 'boolean',
        'buffer_minutes' => 'integer',
    ];
}
