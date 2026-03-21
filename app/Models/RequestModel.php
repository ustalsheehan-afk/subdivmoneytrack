<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestModel extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'resident_id',
        'subject',
        'message',
        'status',
        'date_sent',
    ];

    protected $casts = [
        'date_sent' => 'datetime',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id');
    }

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public function isPending()  { return $this->status === self::STATUS_PENDING; }
    public function isApproved() { return $this->status === self::STATUS_APPROVED; }
    public function isRejected() { return $this->status === self::STATUS_REJECTED; }
}
