<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\AmenityReservation;
use App\Models\ReservationAuditLog;
use App\Models\ReservationCancellationReason;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReservationCancellationService
{
    public function cancel(AmenityReservation $reservation, User $actor, ReservationCancellationReason $reason, ?string $notes, string $cancellationType): void
    {
        if ($reservation->status === 'completed') {
            abort(422, 'Completed reservations cannot be cancelled.');
        }

        if ($reservation->status === 'cancelled' || $reservation->cancelled_at) {
            abort(422, 'This reservation is already cancelled and cannot be modified.');
        }

        if (!in_array($reservation->status, ['pending', 'approved'], true)) {
            abort(422, 'Only pending or approved reservations can be cancelled.');
        }

        DB::transaction(function () use ($reservation, $actor, $reason, $notes, $cancellationType) {
            $oldStatus = $reservation->status;

            $cancellationReasonText = $reason->label;
            if ($notes) {
                $cancellationReasonText .= ' — ' . trim($notes);
            }

            $reservation->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => $actor->id,
                'cancellation_reason' => $cancellationReasonText,
                'cancellation_type' => $cancellationType,
            ]);

            ReservationAuditLog::create([
                'amenity_reservation_id' => $reservation->id,
                'user_id' => $actor->id,
                'action' => 'cancelled',
                'details' => [
                    'reason' => $reason->label,
                    'notes' => $notes,
                    'cancellation_type' => $cancellationType,
                ],
                'previous_status' => $oldStatus,
                'new_status' => 'cancelled',
            ]);

            ActivityLog::create([
                'causer_id' => $actor->id,
                'causer_type' => get_class($actor),
                'action' => 'cancelled',
                'module' => 'reservations',
                'description' => 'Cancelled reservation #' . $reservation->id . ' with reason: ' . $reason->label,
                'metadata' => [
                    'reservation_id' => $reservation->id,
                    'amenity_id' => $reservation->amenity_id,
                    'reason' => $reason->label,
                    'notes' => $notes,
                    'cancellation_type' => $cancellationType,
                    'role' => $actor->rbacRole->name ?? $actor->role ?? null,
                    'ip' => request()?->ip(),
                ],
            ]);
        });
    }
}

