<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Due extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'batch_id',
        'resident_id',
        'title',
        'description',
        'amount',
        'paid_amount',
        'type',
        'frequency',
        'month',
        'due_date',
        'status',
        'billing_period_start',
        'billing_period_end',
        'archived_at',
    ];

    public function batch()
    {
        return $this->belongsTo(DuesBatch::class, 'batch_id');
    }

    public function duesBatch()
    {
        return $this->belongsTo(DuesBatch::class, 'batch_id');
    }

    /**
     * Attribute casting
     */
    protected $casts = [
        'batch_id' => 'string',
        'due_date' => 'date',
        'billing_period_start' => 'date',
        'billing_period_end' => 'date',
        'archived_at' => 'datetime',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    /* =========================
     * Constants
     * ========================= */

    // Status
    public const STATUS_UNPAID = 'unpaid';

    public const STATUS_PENDING = 'pending';

    public const STATUS_PAID = 'paid';

    public const TYPE_MONTHLY_HOA = 'monthly_hoa';

    public const TYPE_REGULAR_FEES = 'regular_fees';

    public const TYPE_SPECIAL_ASSESSMENTS = 'special_assessments';

    public const FREQUENCY_MONTHLY = 'monthly';

    public const FREQUENCY_ONE_TIME = 'one_time';

    public const FREQUENCY_QUARTERLY = 'quarterly';

    /* =========================
     * Relationships
     * ========================= */

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'due_id');
    }

    public function penalties()
    {
        return $this->hasMany(Penalty::class, 'due_id');
    }

    /* =========================
     * Business Logic
     * ========================= */

    /**
     * Get the total amount paid from approved payments.
     */
    public function getTotalPaidAttribute()
    {
        if ($this->relationLoaded('payments')) {
            return (float) $this->payments
                ->where('status', Payment::STATUS_APPROVED)
                ->sum('amount');
        }

        return (float) $this->payments()
            ->where('status', Payment::STATUS_APPROVED)
            ->sum('amount');
    }

    /**
     * Get the remaining balance.
     */
    public function getBalanceAttribute()
    {
        return max(0, (float) $this->amount - $this->total_paid);
    }

    /**
     * Get dynamic status based on payments.
     */
    public function getDynamicStatusAttribute()
    {
        return $this->resolvePaymentStatus();
    }

    /**
     * Get status label with color.
     */
    public function getStatusInfoAttribute()
    {
        return match($this->dynamic_status) {
            self::STATUS_PAID => ['label' => 'Paid', 'color' => 'emerald'],
            'partial' => ['label' => 'Partial', 'color' => 'orange'],
            default => ['label' => 'Unpaid', 'color' => 'red'],
        };
    }

    /**
     * Default billing period start (first day of month).
     */
    public static function defaultBillingPeriodStart(?Carbon $anchorDate = null): Carbon
    {
        $anchor = $anchorDate ? $anchorDate->copy() : Carbon::now();

        return $anchor->startOfMonth();
    }

    /**
     * Default billing period end (last day of month).
     */
    public static function defaultBillingPeriodEnd(?Carbon $anchorDate = null): Carbon
    {
        $anchor = $anchorDate ? $anchorDate->copy() : Carbon::now();

        return $anchor->endOfMonth();
    }

    /**
     * Automatically apply default billing period dates on create
     * when none are provided, while allowing manual overrides.
     */
    protected static function booted(): void
    {
        static::creating(function (Due $due) {
            $dueDate = $due->due_date instanceof Carbon
                ? $due->due_date
                : ($due->due_date ? Carbon::parse($due->due_date) : null);

            if (! $due->billing_period_start) {
                $due->billing_period_start = self::defaultBillingPeriodStart($dueDate);
            }

            if (! $due->billing_period_end) {
                $due->billing_period_end = self::defaultBillingPeriodEnd($dueDate);
            }
        });

    }

    /**
     * Total approved payments
     */
    public function totalCollected(): float
    {
        return $this->total_paid;
    }

    /**
     * Auto mark as paid if fully collected
     */
    public function markPaidIfFullyCollected(): void
    {
        $totalPaid = $this->totalCollected();
        
        $this->update([
            'paid_amount' => $totalPaid,
            'status' => ($totalPaid >= $this->amount) ? self::STATUS_PAID : $this->status
        ]);
    }

    /**
     * Outstanding balance
     */
    public function getOutstandingAttribute(): float
    {
        return max(0, (float) $this->amount - $this->total_paid);
    }

    /**
     * Check if overdue
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->dynamic_status !== self::STATUS_PAID
            && $this->billing_period_end instanceof Carbon
            && $this->billing_period_end->isPast();
    }

    public function resolvePaymentStatus(): string
    {
        $totalPaid = $this->total_paid;
        $totalDue = (float) $this->amount;

        if ($totalPaid <= 0) {
            return self::STATUS_UNPAID;
        }

        if ($totalPaid < $totalDue) {
            return 'partial';
        }

        return self::STATUS_PAID;
    }

    /* =========================
     * Scopes
     * ========================= */

    public function scopeUnpaid($query)
    {
        return $query->where('status', self::STATUS_UNPAID);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeMonthlyHoa($query)
    {
        return $query->where('type', self::TYPE_MONTHLY_HOA);
    }

    public function scopeRegularFees($query)
    {
        return $query->where('type', self::TYPE_REGULAR_FEES);
    }

    public function scopeSpecialAssessments($query)
    {
        return $query->where('type', self::TYPE_SPECIAL_ASSESSMENTS);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }
}
