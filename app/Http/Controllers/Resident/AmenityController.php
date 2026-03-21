<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\AmenityReservation;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    public function index()
    {
        $amenities = Amenity::whereIn('status', ['active', 'maintenance'])->latest()->get();
        return view('resident.amenities.index', compact('amenities'));
    }

    public function show(Amenity $amenity)
    {
        if (!in_array($amenity->status, ['active', 'maintenance'])) {
            return redirect()->route('resident.amenities.index')->with('error', 'This amenity is currently unavailable.');
        }

        // Fetch future reservations to disable slots
        $reservations = AmenityReservation::where('amenity_id', $amenity->id)
            ->where('date', '>=', now()->toDateString())
            ->whereIn('status', ['pending', 'approved'])
            ->get()
            ->groupBy('date') // Group by date "YYYY-MM-DD"
            ->map(function ($dateGroup) {
                return $dateGroup->pluck('time_slot')->toArray();
            });

        return view('resident.amenities.show', compact('amenity', 'reservations'));
    }
}
