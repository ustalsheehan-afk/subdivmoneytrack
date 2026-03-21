<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\AmenityReservation;
use App\Traits\HandlesReservationConflict;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AmenityReservationController extends Controller
{
    use HandlesReservationConflict;

    public function index()
    {
        $user = Auth::guard('resident')->user();
        $resident = $user->resident;

        if (!$resident) {
            abort(403, 'Resident profile not found.');
        }

        $reservations = AmenityReservation::where('resident_id', $resident->id)
            ->with('amenity')
            ->latest()
            ->get();
            
        return view('resident.reservations.index', compact('reservations'));
    }

    public function store(Request $request, Amenity $amenity)
    {
        $user = Auth::guard('resident')->user();
        $resident = $user->resident;

        if (!$resident) {
            abort(403, 'Resident profile not found.');
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

        // Calculate time range
        $startTime = $request->start_time; // e.g., "08:00"
        $duration = (int) $request->duration;
        $endTime = date('H:i', strtotime("$startTime + $duration hours"));
        $timeSlot = "$startTime - $endTime"; 

        // 1. Check if open on that day
        $dayName = date('D', strtotime($request->date)); // Mon, Tue...
        if (!in_array($dayName, $amenity->days_available ?? [])) {
             return back()->withErrors(['date' => 'The amenity is closed on ' . $dayName . '.'])->withInput();
        }

        // 2. Conflict Detection & Creation (Atomic)
        try {
            DB::beginTransaction();

            // Lock Amenity row for update to prevent race conditions during heavy load
            // This serializes attempts to book the same amenity
            Amenity::where('id', $amenity->id)->lockForUpdate()->first();

            // Check availability using trait
            if (!$this->isSlotAvailable($amenity->id, $request->date, $startTime, $endTime)) {
                DB::rollBack();
                return back()->withErrors(['time_slot' => "The selected time ($timeSlot) is no longer available. Please choose another slot."])->withInput();
            }

            // Handle File Upload
            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
            }

            // Calculate Total Price
            $pricePerHour = $amenity->price;
            $amenityCost = $pricePerHour * $duration;
            $totalPrice = $amenityCost;
            
            $equipment = [];
            if ($request->equipment_addons) {
                $equipment = json_decode($request->equipment_addons, true);
                if (is_array($equipment)) {
                    foreach ($equipment as $item) {
                        $totalPrice += ($item['price'] ?? 0);
                    }
                }
            }

            // Create Reservation
            // Status is 'approved' because schedule is confirmed, 
            // but payment_status tracks the "approval" workflow.
            // If admin workflow requires "pending", we can stick to 'pending',
            // but user asked for "No 'Pending Approval' status".
            // So we set status = 'approved' (meaning slot secured), and use payment_status for verification.
            $reservation = AmenityReservation::create([
                'resident_id' => $resident->id,
                'amenity_id' => $amenity->id,
                'date' => $request->date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'time_slot' => $timeSlot, // Keep for legacy/display compatibility
                'guest_count' => $request->guest_count,
                'equipment_addons' => $equipment, 
                'total_price' => $totalPrice,
                'status' => 'approved', // Slot confirmed!
                'payment_status' => ($request->payment_method === 'gcash' && $proofPath) ? 'submitted' : 'pending',
                'payment_method' => $request->payment_method,
                'payment_proof' => $proofPath,
                'payment_reference_no' => $request->payment_reference_no,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('resident.amenities.confirmation', $reservation->id);

        } catch (\Exception $e) {
            DB::rollBack();
            // Log error if needed
            return back()->withErrors(['error' => 'An error occurred while processing your reservation. Please try again.'])->withInput();
        }
    }

    public function show($id)
    {
        $user = Auth::guard('resident')->user();
        $resident = $user->resident;

        $reservation = AmenityReservation::where('id', $id)
            ->where('resident_id', $resident->id)
            ->with('amenity')
            ->firstOrFail();

        return view('resident.reservations.show', compact('reservation'));
    }

    public function uploadPayment(Request $request, $id)
    {
        $user = Auth::guard('resident')->user();
        $resident = $user->resident;

        $reservation = AmenityReservation::where('id', $id)
            ->where('resident_id', $resident->id)
            ->firstOrFail();

        if ($reservation->payment_status === 'paid' || $reservation->payment_status === 'submitted') {
             return back()->with('error', 'Payment is already being processed or verified.');
        }

        $request->validate([
            'payment_proof' => 'required|image|max:5120',
            'payment_reference_no' => 'required|string|max:50',
        ]);

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

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
        $user = Auth::guard('resident')->user();
        $resident = $user->resident;

        $reservation = AmenityReservation::where('id', $id)
            ->where('resident_id', $resident->id)
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
}
