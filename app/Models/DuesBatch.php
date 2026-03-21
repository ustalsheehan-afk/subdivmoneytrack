<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuesBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'billing_period_start',
        'due_date',
        'frequency',
        'total_expected',
        'created_by',
    ];

    protected $casts = [
        'id' => 'string', // Cast to string to prevent MySQL type conversion errors with mixed UUID/Int batch_ids
        'billing_period_start' => 'date',
        'due_date' => 'date',
        'total_expected' => 'decimal:2',
    ];

    public function dues()
    {
        return $this->hasMany(Due::class, 'batch_id');
    }

    public function residentDues()
    {
        return $this->hasMany(Due::class, 'batch_id');
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Due::class, 'batch_id', 'due_id');
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function getCollectedAmountAttribute()
    {
        return (float) $this->dues()->sum('paid_amount');
    }

    public function getProgressAttribute()
    {
        if ($this->total_expected <= 0) return 0;
        return ($this->collected_amount / $this->total_expected) * 100;
    }

    public function getStatusLabelAttribute()
    {
        if ($this->progress >= 100) {
            return 'Completed';
        }
        
        if ($this->due_date && $this->due_date->isPast()) {
            return 'Overdue';
        }

        return 'Collecting';
    }

    public function getStatusColorAttribute()
    {
        return match($this->status_label) {
            'Completed' => 'green',
            'Overdue' => 'red',
            'Collecting' => 'blue',
            default => 'gray',
        };
    }
}
