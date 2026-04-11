<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
    use HasFactory;

    public const CATEGORY_GENERAL = 'general';
    public const CATEGORY_PAYMENT = 'payment';
    public const CATEGORY_COMPLAINT = 'complaint';
    public const CATEGORY_RESERVATION = 'reservation';
    public const CATEGORY_SERVICE_REQUEST = 'service_request';

    public const CATEGORY_LABELS = [
        self::CATEGORY_GENERAL => 'General Inquiry',
        self::CATEGORY_PAYMENT => 'Payment Concern',
        self::CATEGORY_COMPLAINT => 'Complaint',
        self::CATEGORY_RESERVATION => 'Reservation',
        self::CATEGORY_SERVICE_REQUEST => 'Service Request',
    ];

    protected $fillable = [
        'category',
        'title',
        'subject',
        'body',
        'is_active',
        'use_count',
        'last_used_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORY_LABELS[$this->category] ?? ucfirst(str_replace('_', ' ', $this->category));
    }
}
