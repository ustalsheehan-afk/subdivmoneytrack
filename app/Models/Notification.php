<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\NotificationService;
use Illuminate\Support\Str;

class Notification extends Model
{
    use HasFactory;

    public const ROLE_RESIDENT = 'resident';
    public const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'resident_id',
        'admin_id',
        'user_id',
        'role',
        'title',
        'message',
        'type',
        'category',
        'entity_type',
        'entity_id',
        'deduplication_key',
        'link',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Notification $notification) {
            $notification->normalizeLegacyAttributes();

            if (! $notification->deduplication_key) {
                $notification->deduplication_key = $notification->buildDeduplicationKey();
            }

            $exists = static::query()
                ->where('deduplication_key', $notification->deduplication_key)
                ->where('created_at', '>=', now()->subDay())
                ->exists();

            if ($exists) {
                return false;
            }
        });
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function scopeForResident($query, Resident $resident)
    {
        return $query->where('resident_id', $resident->id)
            ->where('role', self::ROLE_RESIDENT);
    }

    public function scopeForAdmin($query, User $admin)
    {
        return $query->where('admin_id', $admin->id)
            ->where('role', self::ROLE_ADMIN);
    }

    public function getNormalizedDeduplicationKeyAttribute(): string
    {
        if ($this->deduplication_key) {
            return $this->deduplication_key;
        }

        return sha1(implode('|', [
            $this->role,
            $this->type,
            $this->category,
            $this->entity_type,
            $this->entity_id,
            $this->resident_id,
            $this->admin_id,
            $this->title,
            $this->message,
        ]));
    }

    private function normalizeLegacyAttributes(): void
    {
        $this->title = trim(Str::of((string) $this->title)->ascii()->toString());
        $this->message = trim(Str::of((string) $this->message)->ascii()->toString());

        if (! $this->role) {
            if ($this->resident_id) {
                $this->role = self::ROLE_RESIDENT;
                $this->user_id ??= Resident::query()->whereKey($this->resident_id)->value('user_id');
            } elseif ($this->admin_id) {
                $this->role = self::ROLE_ADMIN;
                $this->user_id ??= $this->admin_id;
            }
        }

        if (! $this->category) {
            $this->category = match ($this->type) {
                'payment' => NotificationService::CATEGORY_PAYMENT,
                'system' => NotificationService::CATEGORY_BILLING,
                'reminder' => NotificationService::CATEGORY_REMINDER,
                'alert' => NotificationService::CATEGORY_ALERT,
                default => $this->category,
            };
        }

        if (! $this->type || in_array($this->type, ['payment', 'system', 'reminder', 'alert'], true)) {
            $this->type = $this->inferStructuredType();
        }

        $this->entity_type ??= $this->category ?? 'notification';
    }

    private function inferStructuredType(): string
    {
        $title = strtolower((string) $this->title);
        $message = strtolower((string) $this->message);

        if (str_contains($title, 'billing') || str_contains($message, 'billing statement')) {
            return NotificationService::TYPE_BILLING_CREATED;
        }

        if (str_contains($title, 'rejected')) {
            return NotificationService::TYPE_PAYMENT_FAILED;
        }

        if (str_contains($title, 'approved')) {
            return NotificationService::TYPE_PAYMENT_COMPLETED;
        }

        if (str_contains($title, 'submitted') || str_contains($message, 'under review')) {
            return NotificationService::TYPE_PAYMENT_SUBMITTED;
        }

        if (str_contains($title, 'overdue') || str_contains($message, 'overdue')) {
            return NotificationService::TYPE_PAYMENT_OVERDUE;
        }

        if (str_contains($title, 'reminder')) {
            return NotificationService::TYPE_PAYMENT_REMINDER;
        }

        return strtoupper(($this->category ?? 'system') . '_EVENT');
    }

    private function buildDeduplicationKey(): string
    {
        return sha1(implode('|', [
            $this->role,
            $this->user_id,
            $this->type,
            $this->entity_type,
            $this->entity_id,
            $this->title,
            $this->message,
        ]));
    }
}
