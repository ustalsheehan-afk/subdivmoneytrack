<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FileService;

class RequestController extends Controller
{
    /**
     * Display the resident’s service requests.
     */
    public function index()
    {
      
        // Get the logged-in user's resident record
        $user = Auth::user();
        $resident = $user?->resident;

        // Make sure they have a resident record
        if (!$resident) {
            return redirect()->back()->with('error', 'Resident record not found.');
        }

        $requests = ServiceRequest::where('resident_id', $resident->id)
            ->latest()
            ->get();

        return view('resident.requests.index', compact('requests'));
    }

    /**
     * Show form to create a new request.
     */
    public function create()
    {
        return view('resident.requests.create');
    }

    /**
     * Store a new service request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'priority' => 'required|string',
            'reservation_date' => 'nullable|date',
            'reservation_time' => 'nullable|string',
            'guest_count' => 'nullable|integer|min:1',
            'equipment' => 'nullable|array',
        ]);

        // Find resident record for logged-in user
        $user = Auth::user();
        $resident = $user?->resident;

        if (!$resident) {
            return redirect()->back()->with('error', 'Resident record not found.');
        }

        // Handle Photo Upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = FileService::storeAndSync($request->file('photo'), 'requests');
        }

        // Try to find amenity if type starts with "Amenity: "
        $amenityId = null;
        if (str_starts_with($request->type, 'Amenity: ')) {
            $amenityName = trim(substr($request->type, 9));
            $amenity = \App\Models\Amenity::where('name', $amenityName)->first();
            if ($amenity) {
                $amenityId = $amenity->id;

                // Check for availability conflicts
                if ($request->reservation_date && $request->reservation_time) {
                    $conflicts = ServiceRequest::where('amenity_id', $amenityId)
                        ->where('reservation_date', $request->reservation_date)
                        ->whereIn('status', ['pending', 'approved', 'in progress'])
                        ->get();

                    foreach ($conflicts as $conflict) {
                        if ($request->reservation_time === 'Full Day' || $conflict->reservation_time === 'Full Day') {
                            return redirect()->back()
                                ->withInput()
                                ->with('error', 'This date is already booked (Full Day or conflicting slot).');
                        }
                        if ($request->reservation_time === $conflict->reservation_time) {
                            return redirect()->back()
                                ->withInput()
                                ->with('error', 'The selected time slot is already reserved.');
                        }
                    }
                }
            }
        }

        // ✅ Save using the resident ID (not user ID)
        $newRequest = ServiceRequest::create([
            'resident_id' => $resident->id,
            'amenity_id' => $amenityId,
            'type' => $request->type,
            'description' => $request->description,
            'photo' => $photoPath,
            'priority' => $request->priority,
            'status' => 'pending',
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'guest_count' => $request->guest_count,
            'equipment' => $request->equipment,
        ]);

        // Create Notification for Resident
        \App\Models\Notification::create([
            'resident_id' => $resident->id,
            'title' => '🛠 Request Submitted',
            'message' => "Your request for '{$request->type}' has been submitted and is pending review.",
            'type' => 'request',
            'link' => route('resident.requests.show', $newRequest->id),
            'is_read' => false,
        ]);

        // Notify Admins
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'admin_id' => $admin->id,
                'title' => '🛠 New Service Request',
                'message' => "{$resident->full_name} has submitted a new {$request->type} request.",
                'type' => 'request',
                'link' => route('admin.requests.index'),
                'is_read' => false,
            ]);
        }

        return redirect()->route('resident.requests.index')
            ->with('success', 'Your request has been submitted successfully!');
    }

    /**
     * Display a specific service request details.
     */
    public function show($id)
    {
        $user = Auth::user();
        $resident = $user?->resident;

        if (!$resident) {
            abort(403, 'Resident record not found.');
        }

        $requestItem = ServiceRequest::where('resident_id', $resident->id)->findOrFail($id);

        return view('resident.requests.show', compact('requestItem'));
    }

    /**
     * Show the edit form for a request.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $resident = $user?->resident;

        if (!$resident) {
            abort(403, 'Resident record not found.');
        }

        $requestItem = ServiceRequest::where('resident_id', $resident->id)->findOrFail($id);

        if ($requestItem->status === 'completed') {
            return redirect()->route('resident.requests.index')
                ->with('error', 'You cannot edit a completed request.');
        }

        return view('resident.requests.edit', compact('requestItem'));
    }

    /**
     * Update a request.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'priority' => 'required|string',
        ]);

        $user = Auth::user();
        $resident = $user?->resident;

        if (!$resident) {
            abort(403, 'Resident record not found.');
        }

        $requestItem = ServiceRequest::where('resident_id', $resident->id)->findOrFail($id);

        if ($requestItem->status === 'completed') {
            return redirect()->route('resident.requests.index')
                ->with('error', 'You cannot edit a completed request.');
        }

        $updateData = [
            'type' => $request->type,
            'description' => $request->description,
            'priority' => $request->priority,
        ];

        // Handle Photo Update
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($requestItem->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($requestItem->photo);
            }
            $updateData['photo'] = $request->file('photo')->store('requests', 'public');
        }

        $requestItem->update($updateData);

        return redirect()->route('resident.requests.index')
            ->with('success', 'Your request has been updated successfully!');
    }
}
