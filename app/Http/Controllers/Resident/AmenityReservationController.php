<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\AmenityReservation;
use App\Models\Notification;
use App\Models\ReservationCancellationReason;
use App\Models\User;
use App\Services\AmenityReservationBookingService;
use App\Services\FileService;
use App\Services\ReservationCancellationService;
use App\Traits\HandlesReservationConflict;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AmenityReservationController extends Controller
{
    use HandlesReservationConflict;

    public function index()
    {
        $user = Auth::user();
        $resident = $user?->resident;
        $residentUserId = $user?->id;

        if (!$resident || !$residentUserId) {
            return redirect()->route('resident.dashboard')->with('error', 'Resident profile not found.');
        }

        $reservations = AmenityReservation::where('resident_id', $residentUserId)
            ->with('amenity')
            ->latest()
            ->paginate(10);
            
        return view('resident.reservations.index', compact('reservations'));
    }

    public function store(Request $request, Amenity $amenity)
    {
        $user = Auth::user();
        $resident = $user?->resident;
        $residentUserId = $user?->id;

        if (!$resident || !$residentUserId) {
            return redirect()->route('resident.dashboard')->with('error', 'Resident profile not found.');
        }

        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|string', // "HH:mm"
            'duration' => 'required|integer|min:1',
            'guest_count' => 'required|integer|min:1|max:' . $amenity->max_capacity,
            'equipment_addons' => 'nullable|json',
            'notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cash,gcash',
            'payment_proof' => 'nullable|image|max:5120', // 5MB
            'payment_reference_no' => 'nullable|string|max:50',
        ]);

        try {
            // Handle File Upload
            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $proofPath = FileService::storeAndSync($request->file('payment_proof'), 'payment_proofs');
            }

            $equipment = [];
            if ($request->equipment_addons) {
                $equipment = json_decode($request->equipment_addons, true);
            }

            $reservation = app(AmenityReservationBookingService::class)->create($amenity, [
                'resident_id' => $residentUserId,
                'customer_type' => 'resident',
                'booking_source' => 'resident_portal',
                'date' => $request->date,
                'start_time' => $request->start_time,
                'duration' => (int) $request->duration,
                'guest_count' => $request->guest_count,
                'equipment_addons' => is_array($equipment) ? $equipment : [],
                'status' => 'approved',
                'payment_status' => ($request->payment_method === 'gcash' && $proofPath) ? 'submitted' : 'pending',
                'payment_method' => $request->payment_method,
                'payment_proof' => $proofPath,
                'payment_reference_no' => $request->payment_reference_no,
                'notes' => $request->notes,
            ]);

            // Notify all admins of new reservation (non-blocking)
            try {
                $admins = User::where('role', 'admin')->orWhereHas('roles', function ($q) {
                    $q->where('name', 'admin');
                })->get();
                
                foreach ($admins as $admin) {
                    Notification::create([
                        'admin_id' => $admin->id,
                        'title' => 'New Amenity Reservation',
                        'message' => $resident->full_name . ' booked ' . $amenity->name . ' on ' . $reservation->date->format('M d, Y'),
                        'type' => 'reservation',
                        'link' => route('admin.amenity-reservations.index'),
                        'is_read' => false,
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('Failed to create admin notifications for reservation: ' . $e->getMessage());
            }

            return redirect()->route('resident.amenities.confirmation', $reservation->id);

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Resident amenity booking failed', [
                'error' => $e->getMessage(),
                'user_id' => $residentUserId,
                'resident_profile_id' => $resident->id ?? null,
                'amenity_id' => $amenity->id,
                'date' => $request->input('date'),
                'start_time' => $request->input('start_time'),
                'duration' => $request->input('duration'),
                'payment_method' => $request->input('payment_method'),
            ]);
            return back()->withErrors(['error' => 'An error occurred while processing your reservation. Please try again.'])->withInput();
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $resident = $user?->resident;
        $residentUserId = $user?->id;

        if (!$resident || !$residentUserId) {
            abort(403, 'Resident profile not found.');
        }

        $reservation = AmenityReservation::where('id', $id)
            ->where('resident_id', $residentUserId)
            ->with('amenity')
            ->firstOrFail();

        $cancellationReasons = ReservationCancellationReason::where('active', true)
            ->where(function($q) {
                $q->where('scope', 'resident')
                  ->orWhere('scope', 'both');
            })
            ->orderBy('sort_order')
            ->get();

        return view('resident.reservations.show', compact('reservation', 'cancellationReasons'));
    }

    public function uploadPayment(Request $request, $id)
    {
        $user = Auth::user();
        $resident = $user?->resident;
        $residentUserId = $user?->id;

        if (!$resident || !$residentUserId) {
            abort(403, 'Resident profile not found.');
        }

        $reservation = AmenityReservation::where('id', $id)
            ->where('resident_id', $residentUserId)
            ->firstOrFail();

        if ($reservation->payment_status === 'paid' || $reservation->payment_status === 'submitted') {
             return back()->with('error', 'Payment is already being processed or verified.');
        }

        $request->validate([
            'payment_proof' => 'required|image|max:5120',
            'payment_reference_no' => 'required|string|max:50',
        ]);

        FileService::deleteAndSync($reservation->payment_proof);
        $path = FileService::storeAndSync($request->file('payment_proof'), 'payment_proofs');

        $reservation->update([
            'payment_status' => 'submitted',
            'payment_proof' => $path,
            'payment_reference_no' => $request->payment_reference_no,
            'payment_method' => 'gcash',
        ]);

        return back()->with('success', 'Payment proof submitted successfully! Waiting for admin confirmation.');
    }

    public function confirmation($id)
    {
        $user = Auth::user();
        $resident = $user?->resident;
        $residentUserId = $user?->id;

        if (!$resident || !$residentUserId) {
            abort(403, 'Resident profile not found.');
        }

        $reservation = AmenityReservation::where('id', $id)
            ->where('resident_id', $residentUserId)
            ->with('amenity')
            ->firstOrFail();

        return view('resident.reservations.confirmation', compact('reservation'));
    }

    /**
     * API to get unavailable slots for a date
     */
    public function getUnavailableSlots(Request $request, $amenityId)
    {
        $request->validate(['date' => 'required|date']);
        $slots = $this->getUnavailableRanges($amenityId, $request->date);
        return response()->json($slots);
    }

    /**
     * Cancel a reservation
     */
    public function cancel(Request $request, AmenityReservation $reservation)
    {
        $user = Auth::user();
        $resident = $user?->resident;
        $residentUserId = $user?->id;

        if (!$resident || !$residentUserId) {
            abort(403, 'Resident profile not found.');
        }

        // Ensure the reservation belongs to the resident
        if ((int) $reservation->resident_id !== (int) $residentUserId) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'cancellation_reason_id' => 'required|exists:reservation_cancellation_reasons,id',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $reason = ReservationCancellationReason::findOrFail($request->cancellation_reason_id);
            $service = new ReservationCancellationService();
            $service->cancel($reservation, $user, $reason, $request->notes, 'user_cancelled');

            return response()->json([
                'success' => true,
                'message' => 'Reservation cancelled successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function viewReceipt(AmenityReservation $reservation)
    {
        $user = Auth::user();
        $resident = $user?->resident;
        $residentUserId = $user?->id;

        if (!$resident || !$residentUserId) {
            abort(403, 'Resident profile not found.');
        }

        // Ensure the reservation belongs to the resident
        if ((int) $reservation->resident_id !== (int) $residentUserId) {
            abort(403, 'Unauthorized.');
        }

        // Only show receipt if payment is verified
        if ($reservation->payment_status !== 'paid') {
            abort(403, 'Receipt is only available for verified payments.');
        }

        $reservation->load(['resident', 'amenity']);
        
        return view('admin.reservations.receipt', [
            'reservation' => $reservation,
            'verified_by' => $reservation->verified_by ? User::find($reservation->verified_by)->name : 'Admin',
            'verified_at' => $reservation->verified_at ?? now(),
        ]);
    }

    public function downloadReceipt(AmenityReservation $reservation)
    {
        $user = Auth::user();
        $resident = $user?->resident;
        $residentUserId = $user?->id;

        if (!$resident || !$residentUserId) {
            abort(403, 'Resident profile not found.');
        }

        if ((int) $reservation->resident_id !== (int) $residentUserId) {
            abort(403, 'Unauthorized.');
        }

        if ($reservation->payment_status !== 'paid') {
            abort(403, 'Receipt is only available for verified payments.');
        }

        $reservation->load(['resident', 'amenity']);

        $pdf = Pdf::loadView('admin.reservations.receipt', [
            'reservation' => $reservation,
            'verified_by' => $reservation->verified_by ? User::find($reservation->verified_by)->name : 'Admin',
            'verified_at' => $reservation->verified_at ?? now(),
        ]);

        return $pdf->download('reservation-receipt-' . $reservation->id . '.pdf');
    }
}
