<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $amenities = Amenity::latest()->paginate(10);
        return view('admin.amenities.index', compact('amenities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.amenities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'buffer_minutes' => 'nullable|integer|min:0',
            'max_capacity' => 'required|integer|min:1',
            'days_available' => 'required|array', // ['Mon', 'Tue', ...]
            'time_slots' => 'required|array', // [['start' => '08:00', 'end' => '10:00'], ...]
            'equipment' => 'nullable|array', // [['name' => 'Chair', 'price' => 10], ...]
            'image' => 'nullable|image|max:5120', // 5MB
            'pdf_rules' => 'nullable|mimes:pdf|max:5120',
            'status' => 'required',
            'highlight' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('amenities/images', 'public');
        }

        if ($request->hasFile('pdf_rules')) {
            $validated['rules_path'] = $request->file('pdf_rules')->store('amenities/rules', 'public');
        }

        // Ensure status is stored as string
        $validated['status'] = in_array($request->status, ['active', '1', 1, true, 'on']) ? 'active' : 'inactive';

        // Ensure JSON fields are structured correctly (Laravel casts will handle encoding if passed as array)
        // Just need to make sure the structure from the form matches what we expect.
        // Form should send arrays.

        Amenity::create($validated);

        return redirect()->route('admin.amenities.index')
            ->with('success', 'Amenity created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Amenity $amenity)
    {
        // For admin, show might just be the edit page or a simple preview. 
        // Let's redirect to edit for now or just show generic details.
        return view('admin.amenities.show', compact('amenity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Amenity $amenity)
    {
        return view('admin.amenities.edit', compact('amenity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Amenity $amenity)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'buffer_minutes' => 'nullable|integer|min:0',
            'max_capacity' => 'required|integer|min:1',
            'days_available' => 'required|array',
            'time_slots' => 'required|array',
            'equipment' => 'nullable|array',
            'image' => 'nullable|image|max:5120',
            'pdf_rules' => 'nullable|mimes:pdf|max:5120',
            'status' => 'required',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($amenity->image) {
                Storage::disk('public')->delete($amenity->image);
            }
            $validated['image'] = $request->file('image')->store('amenities/images', 'public');
        }

        if ($request->hasFile('pdf_rules')) {
            // Delete old pdf
            if ($amenity->pdf_rules) {
                Storage::disk('public')->delete($amenity->pdf_rules);
            }
            $validated['pdf_rules'] = $request->file('pdf_rules')->store('amenities/rules', 'public');
        }

        // Handle status (support maintenance mode)
        if ($request->status === 'maintenance') {
            $validated['status'] = 'maintenance';
        } else {
            $validated['status'] = in_array($request->status, ['active', '1', 1, true, 'on']) ? 'active' : 'inactive';
        }

        // Default buffer_minutes if not provided
        $validated['buffer_minutes'] = $validated['buffer_minutes'] ?? 30;

        $amenity->update($validated);

        return redirect()->route('admin.amenities.index')
            ->with('success', 'Amenity updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Amenity $amenity)
    {
        if ($amenity->image) {
            Storage::disk('public')->delete($amenity->image);
        }
        if ($amenity->pdf_rules) {
            Storage::disk('public')->delete($amenity->pdf_rules);
        }
        
        $amenity->delete();

        return redirect()->route('admin.amenities.index')
            ->with('success', 'Amenity deleted successfully.');
    }
}
