<?php

namespace App\Services;

use App\Models\Amenity;
use App\Models\AmenityReservation;
use App\Traits\HandlesReservationConflict;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AmenityReservationBookingService
{
    use HandlesReservationConflict;

    public function create(Amenity $amenity, array $attributes, array $options = []): AmenityReservation
    {
        $date = $attributes['date'];
        $startTime = $attributes['start_time'];
        $duration = max(1, (int) $attributes['duration']);
        $endTime = Carbon::createFromFormat('H:i', $startTime)->addHours($duration)->format('H:i');
        $timeSlot = $startTime . ' - ' . $endTime;

        $this->ensureAmenityAvailableOnDate($amenity, $date);

        return DB::transaction(function () use ($amenity, $attributes, $options, $date, $startTime, $duration, $endTime, $timeSlot) {
            Amenity::whereKey($amenity->id)->lockForUpdate()->first();

            if (!($options['override'] ?? false) && !$this->isSlotAvailable($amenity->id, $date, $startTime, $endTime)) {
                throw ValidationException::withMessages([
                    'time_slot' => "The selected time ($timeSlot) is no longer available. Please choose another slot.",
                ]);
            }

            $equipment = $attributes['equipment_addons'] ?? [];
            $totalPrice = $this->calculateTotalPrice($amenity, $duration, $equipment);

            $paymentStatus = $attributes['payment_status'] ?? 'pending';
            $verifiedAt = $paymentStatus === 'paid' ? now() : null;
            $verifiedBy = $paymentStatus === 'paid' ? ($options['verified_by'] ?? null) : null;

            return AmenityReservation::create([
                'resident_id' => $attributes['resident_id'] ?? null,
                'customer_type' => $attributes['customer_type'] ?? 'resident',
                'guest_name' => $attributes['guest_name'] ?? null,
                'guest_contact' => $attributes['guest_contact'] ?? null,
                'guest_email' => $attributes['guest_email'] ?? null,
                'booking_source' => $attributes['booking_source'] ?? 'resident_portal',
                'created_by_admin_id' => $attributes['created_by_admin_id'] ?? null,
                'amenity_id' => $amenity->id,
                'date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'time_slot' => $timeSlot,
                'guest_count' => $attributes['guest_count'],
                'equipment_addons' => $equipment ?: null,
                'total_price' => $totalPrice,
                'status' => $attributes['status'] ?? 'approved',
                'payment_status' => $paymentStatus,
                'payment_method' => $attributes['payment_method'],
                'payment_proof' => $attributes['payment_proof'] ?? null,
                'payment_reference_no' => $attributes['payment_reference_no'] ?? null,
                'notes' => $attributes['notes'] ?? null,
                'verified_at' => $verifiedAt,
                'verified_by' => $verifiedBy,
            ]);
        });
    }

    public function buildTimeSlotPreview(string $startTime, int $duration): string
    {
        $endTime = Carbon::createFromFormat('H:i', $startTime)->addHours($duration)->format('H:i');

        return $startTime . ' - ' . $endTime;
    }

    public function calculateTotalPrice(Amenity $amenity, int $duration, array $equipment = []): float
    {
        $total = (float) $amenity->price * $duration;

        foreach ($equipment as $item) {
            $total += (float) ($item['price'] ?? 0);
        }

        return $total;
    }

    public function ensureAmenityAvailableOnDate(Amenity $amenity, string $date): void
    {
        $dayName = Carbon::parse($date)->format('D');
        $availableDays = collect($amenity->days_available ?? [])->map(fn ($day) => substr($day, 0, 3))->all();

        if ($availableDays && !in_array($dayName, $availableDays, true)) {
            throw ValidationException::withMessages([
                'date' => 'The amenity is closed on ' . $dayName . '.',
            ]);
        }
    }
}
