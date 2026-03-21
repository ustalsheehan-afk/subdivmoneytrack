<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payment extends Model
{
    use HasFactory;

    /* -----------------------------
       FILLABLE FIELDS
    ----------------------------- */
    protected $fillable = [
        'resident_id',
        'due_id',
        'penalty_id',
        'reference_no',
        'amount',
        'date_paid',
        'payment_method',
        'proof',
        'source',
        'status',
    ];

    /* -----------------------------
       ATTRIBUTE CASTS
    ----------------------------- */
    protected $casts = [
        'date_paid'   => 'datetime',
        'amount' => 'decimal:2',
    ];

    /* -----------------------------
       STATUS CONSTANTS
    ----------------------------- */
    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    /* -----------------------------
       SOURCE CONSTANTS
    ----------------------------- */
    public const SOURCE_ADMIN    = 'admin';
    public const SOURCE_RESIDENT = 'resident';

    /* -----------------------------
       PAYMENT METHOD CONSTANTS
    ----------------------------- */
    public const METHOD_CASH          = 'cash';
    public const METHOD_GCASH         = 'gcash';
    public const METHOD_BANK_TRANSFER = 'bank transfer';

    /* -----------------------------
       RELATIONSHIPS
    ----------------------------- */
    public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id');
    }

    public function due()
    {
        return $this->belongsTo(Due::class, 'due_id');
    }

    public function penalty()
    {
        return $this->belongsTo(Penalty::class, 'penalty_id');
    }

    public function penalties()
    {
        return $this->hasMany(Penalty::class, 'payment_id');
    }

    /* -----------------------------
       STATUS HELPERS
    ----------------------------- */
    public function approve()
    {
        if ($this->status !== self::STATUS_PENDING) return false;
        
        return $this->update(['status' => self::STATUS_APPROVED]);
    }

    public function reject()
    {
        if ($this->status !== self::STATUS_PENDING) return false;
        
        return $this->update(['status' => self::STATUS_REJECTED]);
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }
}
