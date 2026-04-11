<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'amenity_id', // Added
        'type',
        'description',
        'photo',
        'priority',
        'status',
        'processed_at',
        'completed_at',
        'reservation_date', // Added
        'reservation_time', // Added
        'guest_count', // Added
        'equipment', // Added
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
        'equipment' => 'array', // Cast JSON to array
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id');
    }

    public function amenity()
    {
        return $this->belongsTo(Amenity::class, 'amenity_id');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $this->photo) || str_starts_with($this->photo, '//')) {
            return $this->photo;
        }

        $normalizedPath = str_replace('\\', '/', trim((string) $this->photo));
        $normalizedPath = ltrim($normalizedPath, '/');

        $candidates = [$normalizedPath];

        foreach (['storage/app/public/', 'app/public/', 'public/', 'storage/'] as $prefix) {
            if (str_starts_with($normalizedPath, $prefix)) {
                $candidates[] = substr($normalizedPath, strlen($prefix));
            }
        }

        $basename = basename($normalizedPath);
        if (! str_contains($normalizedPath, '/') && $basename !== '.' && $basename !== '..') {
            $candidates[] = 'requests/' . $basename;
        }

        if (str_starts_with($normalizedPath, 'requests/')) {
            $candidates[] = 'requests/' . basename($normalizedPath);
        }

        foreach (array_unique($candidates) as $candidate) {
            $candidate = ltrim($candidate, '/');

            if ($candidate === '') {
                continue;
            }

            if (Storage::disk('public')->exists($candidate) || File::exists(public_path('storage/' . $candidate))) {
                return asset('storage/' . $candidate);
            }
        }

        return null;
    }

    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELLED = 'cancelled'; // Added

    public function isPending()  { return $this->status === self::STATUS_PENDING; }
    public function isApproved() { return $this->status === self::STATUS_APPROVED; }
    public function isRejected() { return $this->status === self::STATUS_REJECTED; }
}
