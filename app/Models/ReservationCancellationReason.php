<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationCancellationReason extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'scope',
        'active',
        'sort_order',
    ];
}

