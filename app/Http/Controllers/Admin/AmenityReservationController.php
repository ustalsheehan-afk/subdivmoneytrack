<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\AmenityReservation;
use App\Models\Notification;
use App\Models\ReservationAuditLog;
use App\Models\ReservationCancellationReason;
use App\Models\Resident;
use App\Models\User;
use App\Services\AmenityReservationBookingService;
use App\Services\ReservationCancellationService;
use App\Traits\HandlesReservationConflict;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Traits\LogsActivity;

class AmenityReservationController extends Controller
{
    use LogsActivity;
    use HandlesReservationConflict;

    public function __construct()
    {
        $this->middleware('permission:reservations.view')->only(['index', 'getData']);
        $this->middleware('permission:reservations.create')->only(['create', 'store', 'reschedule']);
        $this->middleware('permission:reservations.approve')->only(['updateStatus', 'bulkAction', 'toggleMaintenance']);
        $this->middleware('permission:reservations.cancel')->only([]);
        $this->middleware('permission:reservations.export')->only(['export', 'exportCsv']);
    }

    public function index()
    {
        $amenities = Amenity::where('status', 'active')->orWhere('status', 'maintenance')->get()->map(function($amenity, $index) {
            $colors = ['#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#6366F1', '#14B8A6'];
            $amenity->color = $colors[$index % count($colors)];
            $amenity->image_url = $amenity->image_url;
            $amenity->capacity = 0;
            return $amenity;
        });

        return view('admin.reservations.index', compact('amenities'));
    }

    public function create()
    {
        $amenities = Amenity::where('status', '!=', 'inactive')->get();
        $residents = Resident::with('user')
            ->where('status', 'active')
            ->get()
            ->filter(fn ($resident) => $resident->user)
            ->values();

        return view('admin.reservations.create', compact('amenities', 'residents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_type' => 'required|in:resident,non_resident',
            'resident_id' => 'nullable|required_if:customer_type,resident|exists:users,id',
            'guest_name' => 'nullable|required_if:customer_type,non_resident|string|max:255',
            'guest_contact' => 'nullable|required_if:customer_type,non_resident|string|max:50',
            'guest_email' => 'nullable|email|max:255',
            'amenity_id' => 'required|exists:amenities,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|string',
            'duration' => 'required|integer|min:1',
            'guest_count' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,gcash',
            'payment_status' => 'required|in:paid,pending',
            'override' => 'nullable|boolean',
            'payment_reference_no' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        $amenity = Amenity::findOrFail($request->amenity_id);

        try {
            $isOverride = $request->boolean('override');

            $reservation = app(AmenityReservationBookingService::class)->create($amenity, [
                'resident_id' => $request->input('customer_type') === 'resident' ? $request->resident_id : null,
                'customer_type' => $request->customer_type,
                'guest_name' => $request->input('customer_type') === 'non_resident' ? $request->guest_name : null,
                'guest_contact' => $request->input('customer_type') === 'non_resident' ? $request->guest_contact : null,
                'guest_email' => $request->input('customer_type') === 'non_resident' ? $request->guest_email : null,
                'booking_source' => 'admin_created',
                'created_by_admin_id' => Auth::id(),
                'date' => $request->date,
                'start_time' => $request->start_time,
                'duration' => (int) $request->duration,
                'guest_count' => $request->guest_count,
                'status' => 'approved',
                'payment_status' => $request->payment_status,
                'payment_method' => $request->payment_method,
                'payment_reference_no' => $request->payment_reference_no,
                'notes' => $request->notes,
            ], [
                'override' => $isOverride,
                'verified_by' => Auth::id(),
            ]);

            // Audit Log if override used
            if ($isOverride) {
                ReservationAuditLog::create([
                    'amenity_reservation_id' => $reservation->id,
                    'user_id' => Auth::id(),
                    'action' => 'override_create',
                    'details' => ['reason' => 'Admin override used to bypass conflict check.'],
                    'previous_status' => null,
                    'new_status' => 'approved',
                ]);
            }

            return redirect()->route('admin.amenity-reservations.confirmation', $reservation);

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])->withInput();
        }
    }

    public function confirmation(AmenityReservation $reservation)
    {
        $reservation->load(['resident.user', 'amenity', 'createdByAdmin']);

        return view('admin.reservations.confirmation', compact('reservation'));
    }

    public function getUnavailableSlots(Request $request, Amenity $amenity)
    {
        $request->validate(['date' => 'required|date']);

        $slots = $this->getUnavailableRanges($amenity->id, $request->date);

        return response()->json($slots);
    }

    public function getData(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        
        // Fetch raw reservations for the date first
        $rawReservations = AmenityReservation::with(['resident', 'amenity'])
            ->whereDate('date', $date)
            ->get();

        // Calculate load per amenity
        $amenityLoad = $rawReservations->where('status', 'approved')
            ->groupBy('amenity_id')
            ->map(function ($group) {
                return $group->sum('guest_count');
            });

        // Fetch Amenities
        $amenities = Amenity::where('status', 'active')->orWhere('status', 'maintenance')->get()->map(function($amenity, $index) use ($amenityLoad) {
            $colors = ['#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#6366F1', '#14B8A6'];
            $amenity->color = $colors[$index % count($colors)];
            $amenity->capacity = $amenityLoad->get($amenity->id, 0); // Current booked guests
            // Ensure image URL is available or use a default
            $amenity->image_url = $amenity->image_url;
            return $amenity;
        });

        // Dashboard Metrics
        $approvedReservations = $rawReservations->where('status', 'approved');
        $revenue = $approvedReservations->sum('total_price'); // Assuming total_price exists
        $totalBookings = $rawReservations->count();
        $pendingCount = $rawReservations->where('status', 'pending')->count();
        $approvedCount = $approvedReservations->count();
        
        // Calculate available slots (Simplified: Total Slots - Booked Hours)
        // Assuming 18 operational hours (6AM - 12AM) per amenity
        $totalPotentialSlots = $amenities->count() * 18; 
        $bookedHours = $approvedReservations->sum(function($res) {
            // Calculate duration
            $parts = explode('-', str_replace(' ', '', $res->time_slot));
            $start = Carbon::parse($parts[0] ?? '00:00');
            $end = Carbon::parse($parts[1] ?? '00:00');
            return $end->diffInHours($start);
        });
        $availableSlots = max(0, $totalPotentialSlots - $bookedHours);

        // Fetch Recent Activity Logs
        $activities = ReservationAuditLog::with('admin')
            ->latest()
            ->take(20)
            ->get()
            ->map(function($log) {
                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'details' => $log->details,
                    'admin_name' => optional($log->admin)->name ?? 'System',
                    'time_ago' => $log->created_at->diffForHumans(),
                    'created_at' => $log->created_at->format('M d, H:i'),
                    'status_color' => match($log->action) {
                        'approved' => 'green',
                        'rejected' => 'red',
                        'pending' => 'yellow',
                        'maintenance' => 'orange',
                        default => 'gray'
                    }
                ];
            });

        // Transform Reservations
        $reservations = $rawReservations->map(function ($res) use ($amenities) {
                // Parse Time
                // Assuming "08:00 - 10:00" format
                $parts = explode('-', str_replace(' ', '', $res->time_slot));
                $startStr = $parts[0] ?? '00:00';
                $endStr = $parts[1] ?? '00:00'; // Default or calculate duration
                
                $start = Carbon::parse($res->date->format('Y-m-d') . ' ' . $startStr);
                $end = Carbon::parse($res->date->format('Y-m-d') . ' ' . $endStr);
                
                // Calculate start hour (float) and duration (hours)
                $startHour = $start->hour + ($start->minute / 60);
                $endHour = $end->hour + ($end->minute / 60);
                $duration = $endHour - $startHour;

                return [
                    'id' => $res->id,
                    'resident_name' => $res->customer_name,
                    'customer_type' => $res->customer_type_label,
                    'contact' => $res->customer_contact,
                    'email' => $res->customer_email,
                    'unit' => $res->customer_unit,
                    'full_address' => $res->customer_type === 'non_resident'
                        ? 'Walk-in / External Booking'
                        : 'Block ' . (optional($res->resident)->block ?? '?') . ' Lot ' . (optional($res->resident)->lot ?? '?'),
                    'amenity_id' => $res->amenity_id,
                    'amenity_name' => optional($res->amenity)->name,
                    'status' => $res->status,
                    'start_hour' => $startHour,
                    'duration' => $duration > 0 ? $duration : 1, // Min 1 hour
                    'time_slot' => $res->time_slot,
                    'guest_count' => $res->guest_count,
                    'payment_status' => $res->payment_status ?? 'unpaid',
                    'payment_reference_no' => $res->payment_reference_no,
                    'total_price' => $res->total_price,
                    'notes' => $res->notes,
                    'equipment_addons' => $res->equipment_addons,
                    'color' => null,
                ];
            });
            
        // Fetch Actionable Reservations (Unified List)
        $actionable = AmenityReservation::with(['resident', 'amenity'])
            ->where(function($query) {
                // 1. Pending Approval
                $query->where('status', 'pending')
                // 2. GCash Proof Submitted (Verification Pending)
                      ->orWhere('payment_status', 'submitted')
                // 3. Pending Cash Payment (Approved but unpaid cash)
                      ->orWhere(function($q) {
                          $q->where('payment_method', 'cash')
                            ->where('payment_status', 'pending')
                            ->where('status', '!=', 'rejected')
                            ->where('status', '!=', 'cancelled');
                      });
            })
            ->orderBy('date', 'asc') // Urgency: Earliest date first
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($res) {
                $data = $this->transformReservation($res);
                
                // Determine Status Reason
                if ($res->payment_status === 'submitted') {
                    $data['status_reason'] = 'GCash Proof Submitted';
                    $data['action_type'] = 'verify_payment';
                    $data['status_color'] = 'blue';
                } elseif ($res->status === 'pending') {
                    $data['status_reason'] = 'Pending Approval';
                    $data['action_type'] = 'review';
                    $data['status_color'] = 'yellow';
                } elseif ($res->payment_method === 'cash' && $res->payment_status === 'pending') {
                    $data['status_reason'] = 'Pending Cash Payment';
                    $data['action_type'] = 'collect_payment';
                    $data['status_color'] = 'orange';
                } else {
                    $data['status_reason'] = 'Requires Action';
                    $data['action_type'] = 'review';
                    $data['status_color'] = 'gray';
                }
                
                // Check for Overdue
                $resDate = Carbon::parse($res->date->format('Y-m-d') . ' ' . explode('-', $res->time_slot)[0]);
                if ($resDate->isPast()) {
                    $data['is_overdue'] = true;
                    $data['status_reason'] = 'Overdue - ' . $data['status_reason'];
                    $data['status_color'] = 'red';
                } else {
                    $data['is_overdue'] = false;
                }

                return $data;
            });

        $cancelledReservations = $reservations->where('status', 'cancelled')->values();

        return response()->json([
            'amenities' => $amenities,
            'reservations' => $reservations,
            'actionable' => $actionable,
            'cancelled_reservations' => $cancelledReservations,
            'metrics' => [
                'total_bookings' => $totalBookings,
                'pending' => $actionable->count(),
                'approved' => $approvedCount,
                'cancelled' => $cancelledReservations->count(),
                'available_slots' => $availableSlots,
                'revenue' => $revenue
            ],
            'activities' => $activities
        ]);
    }

    private function transformReservation($res) {
        return [
            'id' => $res->id,
            'resident_name' => $res->customer_name,
            'customer_type' => $res->customer_type_label,
            'unit' => $res->customer_unit,
            'amenity_id' => $res->amenity_id,
            'amenity_name' => optional($res->amenity)->name,
            'amenity_image' => optional($res->amenity)->image_url,
            'date' => $res->date->format('M d, Y'),
            'time_slot' => $res->time_slot,
            'guest_count' => $res->guest_count,
            'email' => $res->customer_email,
            'contact' => $res->customer_contact,
            'notes' => $res->notes,
            'equipment_addons' => $res->equipment_addons,
            'total_price' => $res->total_price,
            'payment_status' => $res->payment_status,
            'payment_method' => $res->payment_method,
            'payment_proof' => $this->storagePublicUrl($res->payment_proof),
            'payment_reference_no' => $res->payment_reference_no,
            'created_at_formatted' => $res->created_at->format('M d, h:i A'),
            'status' => $res->status,
            'cancellation_reason' => $res->cancellation_reason,
            'reference_code' => $res->reference_code,
        ];
    }

    private function storagePublicUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $path) || str_starts_with($path, '//')) {
            return $path;
        }

        $normalizedPath = ltrim($path, '/');

        if (str_starts_with($normalizedPath, 'storage/')) {
            $normalizedPath = substr($normalizedPath, strlen('storage/'));
        }

        return asset('storage/' . $normalizedPath);
    }

    public function verifyPayment(AmenityReservation $reservation)
    {
        $oldStatus = $reservation->payment_status;

        $reservation->update([
            'payment_status' => 'paid',
            'status' => 'approved', // Auto-approve reservation when payment is verified
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        ReservationAuditLog::create([
            'amenity_reservation_id' => $reservation->id,
            'user_id' => Auth::id(),
            'action' => 'payment_verified',
            'details' => ['amount' => $reservation->total_price],
            'previous_status' => $oldStatus,
            'new_status' => 'paid',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment verified successfully!',
            'receipt_url' => route('admin.amenity-reservations.receipt', $reservation->id)
        ]);
    }

    public function viewReceipt(AmenityReservation $reservation)
    {
        $reservation->load(['resident', 'amenity']);
        
        return view('admin.reservations.receipt', [
            'reservation' => $reservation,
            'verified_by' => $reservation->verified_by ? \App\Models\User::find($reservation->verified_by)->name : 'Admin',
            'verified_at' => $reservation->verified_at ?? now(),
        ]);
    }

    public function downloadReceipt(AmenityReservation $reservation)
    {
        $reservation->load(['resident', 'amenity']);

        if ($reservation->payment_status !== 'paid') {
            abort(403, 'Receipt is only available for verified payments.');
        }

        $pdf = Pdf::loadView('admin.reservations.receipt', [
            'reservation' => $reservation,
            'verified_by' => $reservation->verified_by ? \App\Models\User::find($reservation->verified_by)->name : 'Admin',
            'verified_at' => $reservation->verified_at ?? now(),
        ]);

        return $pdf->download('reservation-receipt-' . $reservation->id . '.pdf');
    }

    public function rejectPayment(Request $request, AmenityReservation $reservation)
    {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        $reservation->update([
            'payment_status' => 'rejected',
            'rejection_reason' => $request->reason,
            // Keep reservation status as pending or reject it? 
            // Usually if payment is rejected, reservation might still be valid if they retry payment.
            // But let's keep it pending so they can re-upload or pay cash.
        ]);

        ReservationAuditLog::create([
            'amenity_reservation_id' => $reservation->id,
            'user_id' => Auth::id(),
            'action' => 'payment_rejected',
            'details' => ['reason' => $request->reason],
            'previous_status' => 'submitted',
            'new_status' => 'rejected',
        ]);

        return response()->json(['success' => true]);
    }

    public function reschedule(Request $request, AmenityReservation $reservation)
    {
        $request->validate([
            'date' => 'required|date',
            'time_slot' => 'required|string', // "HH:mm - HH:mm"
            'amenity_id' => 'required|exists:amenities,id',
            'override' => 'nullable|boolean',
        ]);

        $parts = explode(' - ', str_replace(' ', '', $request->time_slot)); // "08:00-10:00" or "08:00 - 10:00" -> "08:00-10:00"
        if (count($parts) < 2) {
             // Try standard explode
             $parts = explode(' - ', $request->time_slot);
        }
        $startTime = $parts[0] ?? null;
        $endTime = $parts[1] ?? null;

        if (!$startTime || !$endTime) {
            return response()->json(['error' => 'Invalid time slot format.'], 422);
        }

        try {
            DB::beginTransaction();
            Amenity::where('id', $request->amenity_id)->lockForUpdate()->first();

            $isOverride = $request->boolean('override');
            if (!$isOverride) {
                if (!$this->isSlotAvailable($request->amenity_id, $request->date, $startTime, $endTime, $reservation->id)) {
                    DB::rollBack();
                    return response()->json(['error' => 'Conflict detected: The selected slot is already booked.'], 422);
                }
            }
            
            $oldData = [
                'date' => $reservation->date->format('Y-m-d'),
                'time_slot' => $reservation->time_slot,
                'amenity_id' => $reservation->amenity_id,
            ];

            $reservation->update([
                'date' => $request->date,
                'time_slot' => $request->time_slot,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'amenity_id' => $request->amenity_id,
                'status' => 'approved' // Auto approve if rescheduled by admin
            ]);

            // Audit Log
            ReservationAuditLog::create([
                'amenity_reservation_id' => $reservation->id,
                'user_id' => Auth::id(),
                'action' => $isOverride ? 'override_reschedule' : 'rescheduled',
                'details' => [
                    'from' => $oldData,
                    'to' => $request->only(['date', 'time_slot', 'amenity_id']),
                    'reason' => $isOverride ? 'Admin override used.' : null
                ],
                'previous_status' => $reservation->status,
                'new_status' => 'approved',
            ]);

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:amenity_reservations,id',
            'action' => 'required|in:approve,reject,delete',
        ]);

        $count = 0;
        $reservations = AmenityReservation::whereIn('id', $request->ids)->get();

        foreach ($reservations as $reservation) {
            if ($request->action === 'delete') {
                // Prevent deletion of cancelled or approved reservations to maintain audit trail
                if (in_array($reservation->status, ['cancelled', 'approved', 'completed'])) {
                    continue; // Skip deletion of protected statuses
                }

                $reservation->delete();
                $count++;
                
                ReservationAuditLog::create([
                    'amenity_reservation_id' => $reservation->id,
                    'user_id' => Auth::id(),
                    'action' => 'deleted',
                    'details' => ['reason' => 'Bulk action'],
                    'previous_status' => $reservation->status,
                    'new_status' => 'deleted',
                ]);
            } else {
                $status = $request->action === 'approve' ? 'approved' : 'rejected';
                
                // Skip if already in that status
                if ($reservation->status === $status) continue;

                // Check conflict for approval
                if ($status === 'approved') {
                    $start = $reservation->start_time;
                    $end = $reservation->end_time;
                    if (!$start || !$end) {
                         $parts = explode('-', str_replace(' ', '', $reservation->time_slot));
                         $start = $parts[0] ?? '00:00';
                         $end = $parts[1] ?? '00:00';
                    }

                    if (!$this->isSlotAvailable($reservation->amenity_id, $reservation->date->format('Y-m-d'), $start, $end, $reservation->id)) {
                        continue; // Skip conflicting approvals
                    }
                }

                $oldStatus = $reservation->status;
                $reservation->update(['status' => $status]);
                $count++;

                ReservationAuditLog::create([
                    'amenity_reservation_id' => $reservation->id,
                    'user_id' => Auth::id(),
                    'action' => $status,
                    'details' => ['reason' => 'Bulk action'],
                    'previous_status' => $oldStatus,
                    'new_status' => $status,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => "$count reservations processed successfully."
        ]);
    }

    public function updateStatus(Request $request, AmenityReservation $reservation)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,pending,cancelled',
            'reason' => 'nullable|string|max:255',
        ]);

        // Prevent status changes on already-cancelled reservations to preserve audit trail
        if ($reservation->status === 'cancelled') {
            return response()->json(['error' => 'Cannot change status of cancelled reservation. Cancelled reservations are immutable for audit purposes.'], 422);
        }

        if ($request->status === 'approved') {
            $start = $reservation->start_time;
            $end = $reservation->end_time;
            if (!$start || !$end) {
                 $parts = explode('-', str_replace(' ', '', $reservation->time_slot));
                 $start = $parts[0] ?? '00:00';
                 $end = $parts[1] ?? '00:00';
            }

            if (!$this->isSlotAvailable($reservation->amenity_id, $reservation->date->format('Y-m-d'), $start, $end, $reservation->id)) {
                return response()->json(['error' => 'Conflict detected: The selected slot is already booked.'], 422);
            }
        }

        $oldStatus = $reservation->status;
        $reservation->update(['status' => $request->status]);

        // Notify resident of status change (non-blocking)
        if ($reservation->resident_id) {
            try {
                $statusMessage = match($request->status) {
                    'approved' => 'Your reservation for ' . optional($reservation->amenity)->name . ' has been approved!',
                    'rejected' => 'Your reservation for ' . optional($reservation->amenity)->name . ' has been rejected.',
                    'pending' => 'Your reservation for ' . optional($reservation->amenity)->name . ' is pending review.',
                    default => 'Your reservation status has been updated.',
                };
                
                Notification::create([
                    'resident_id' => $reservation->resident_id,
                    'title' => 'Reservation ' . ucfirst($request->status),
                    'message' => $statusMessage,
                    'type' => 'reservation',
                    'link' => route('resident.amenities.reservation.show', $reservation->id),
                    'is_read' => false,
                ]);
            } catch (\Exception $e) {
                \Log::warning('Failed to create resident notification: ' . $e->getMessage());
            }
        }

        // Audit Log
        ReservationAuditLog::create([
            'amenity_reservation_id' => $reservation->id,
            'user_id' => Auth::id(),
            'action' => $request->status,
            'details' => [
                'reason' => $request->reason ?? 'Admin action',
            ],
            'previous_status' => $oldStatus,
            'new_status' => $request->status,
        ]);

        // Log Activity
        $this->logActivity($request->status, 'reservations', ucfirst($request->status) . " reservation for " . optional($reservation->amenity)->name . " by " . (optional($reservation->resident)->full_name ?? 'Unknown'), [
            'reservation_id' => $reservation->id,
            'amenity_id' => $reservation->amenity_id
        ]);

        return response()->json(['success' => true]);
    }

    public function toggleMaintenance(Request $request, Amenity $amenity)
    {
        $request->validate([
            'maintenance' => 'required|boolean'
        ]);

        $amenity->update([
            'status' => $request->maintenance ? 'maintenance' : 'active'
        ]);

        return response()->json(['success' => true]);
    }

    public function export(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $reservations = AmenityReservation::with(['resident', 'amenity'])
            ->whereDate('date', $date)
            ->orderBy('amenity_id')
            ->orderBy('time_slot')
            ->get();
            
        $pdf = Pdf::loadView('admin.reservations.pdf', compact('reservations', 'date'));
        return $pdf->download('reservations-' . $date . '.pdf');
    }

    public function exportCsv(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $reservations = AmenityReservation::with(['resident', 'amenity'])
            ->whereDate('date', $date)
            ->orderBy('amenity_id')
            ->orderBy('time_slot')
            ->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=reservations-$date.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($reservations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Amenity', 'Time Slot', 'Resident Name', 'Contact', 'Unit', 'Guests', 'Status', 'Payment Status']);

            foreach ($reservations as $res) {
                fputcsv($file, [
                    optional($res->amenity)->name,
                    $res->time_slot,
                    optional($res->resident)->full_name ?? 'Unknown',
                    optional($res->resident)->contact_number ?? 'N/A',
                    (optional($res->resident)->block ?? '?') . '-' . (optional($res->resident)->lot ?? '?'),
                    $res->guest_count,
                    ucfirst($res->status),
                    ucfirst($res->payment_status ?? 'unpaid')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function cancel(Request $request, AmenityReservation $reservation)
    {
        $request->validate([
            'cancellation_reason_id' => 'required|exists:reservation_cancellation_reasons,id',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $reason = ReservationCancellationReason::findOrFail($request->cancellation_reason_id);
            $service = new ReservationCancellationService();
            $service->cancel($reservation, Auth::user(), $reason, $request->notes, 'admin_cancelled');

            // Notify resident of cancellation (non-blocking)
            if ($reservation->resident_id) {
                try {
                    Notification::create([
                        'resident_id' => $reservation->resident_id,
                        'title' => 'Reservation Cancelled',
                        'message' => 'Your reservation for ' . optional($reservation->amenity)->name . ' has been cancelled. Reason: ' . $reason->label,
                        'type' => 'reservation',
                        'link' => route('resident.amenities.reservation.show', $reservation->id),
                        'is_read' => false,
                    ]);
                } catch (\Exception $e) {
                    \Log::warning('Failed to create cancellation notification: ' . $e->getMessage());
                }
            }

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
}
