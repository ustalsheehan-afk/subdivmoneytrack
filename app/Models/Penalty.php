<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'due_id',
        'payment_id',
        'type',
        'reason',
        'amount',
        'date_issued',
        'due_date',
        'status',
    ];

    protected $casts = [
        'date_issued' => 'datetime',
        'due_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public const STATUS_PENDING  = 'pending';
    public const STATUS_PAID     = 'paid';
    public const STATUS_REJECTED = 'rejected';

    public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function due()
    {
        return $this->belongsTo(Due::class);
    }

    public function isPending()  { return $this->status === self::STATUS_PENDING; }
    public function isPaid()     { return $this->status === self::STATUS_PAID; }
    public function isRejected() { return $this->status === self::STATUS_REJECTED; }
}
