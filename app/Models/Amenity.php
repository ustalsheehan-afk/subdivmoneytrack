<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $this->image) || str_starts_with($this->image, '//')) {
            return $this->image;
        }

        $normalizedPath = str_replace('\\', '/', trim($this->image));
        $normalizedPath = ltrim($normalizedPath, '/');

        $candidates = [$normalizedPath];

        foreach (['storage/app/public/', 'app/public/', 'public/', 'storage/'] as $prefix) {
            if (str_starts_with($normalizedPath, $prefix)) {
                $candidates[] = substr($normalizedPath, strlen($prefix));
            }
        }

        $basename = basename($normalizedPath);
        if (! str_contains($normalizedPath, '/') && $basename !== '.' && $basename !== '..') {
            $candidates[] = 'amenities/images/' . $basename;
        }

        if (str_starts_with($normalizedPath, 'images/')) {
            $candidates[] = 'amenities/' . $normalizedPath;
            $candidates[] = 'amenities/images/' . basename($normalizedPath);
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
}
