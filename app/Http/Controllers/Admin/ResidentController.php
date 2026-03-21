<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\User;
use App\Exports\ResidentsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Throwable;

use App\Models\Invitation;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ResidentController extends Controller
{
    // ================================
    // IMPERSONATION
    // ================================
    public function loginAsResident(Resident $resident)
    {
        Auth::guard('resident')->login($resident);
        return redirect()->route('resident.dashboard');
    }

    // ================================
    // LIST + SEARCH + FILTER + SORT
    // ================================
    public function index(Request $request)
    {
        $query = Resident::query();

        // 1. FILTERS
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('block', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('block')) {
            $query->where('block', $request->block);
        }

        if ($request->filled('lot')) {
            $query->where('lot', $request->lot);
        }

        // 2. SORTING
        $sortOption = $request->input('sort_option', 'name_asc');
        switch ($sortOption) {
            case 'name_desc':
                $query->orderBy('first_name', 'desc')->orderBy('last_name', 'desc');
                break;
            case 'block_asc':
                $query->orderBy('block', 'asc');
                break;
            case 'block_desc':
                $query->orderBy('block', 'desc');
                break;
            case 'created_at_desc':
                $query->orderBy('created_at', 'desc');
                break;
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
            default:
                $query->orderBy('first_name', 'asc')->orderBy('last_name', 'asc');
                break;
        }

        // 3. GET DATA (Load ALL at once as requested)
        $residents = $query->get();

        // 4. DISTINCT BLOCKS & LOTS (For Filter Dropdown)
        $blocks = Resident::whereNotNull('block')
            ->distinct()
            ->orderBy('block')
            ->pluck('block');

        $lots = Resident::whereNotNull('lot')
            ->distinct()
            ->orderBy('lot')
            ->pluck('lot');

        return view('admin.residents.index', compact('residents', 'blocks', 'lots', 'sortOption'));
    }

    // ================================
    // EXPORT
    // ================================
    public function export(Request $request)
    {
        $filters = $request->only([
            'search',
            'status',
            'block',
            'lot',
            'move_in_date',
            'month',
            'year',
            'custom_date',
            'sort_option'
        ]);

        return Excel::download(new ResidentsExport($filters), 'residents.xlsx');
    }

    // ================================
    // CREATE / STORE
    // ================================
    public function create()
    {
        return view('admin.residents.create');
    }

    public function store(Request $request)
    {
        // 1. Validation (Use the existing validateResident method)
        $validated = $this->validateResident($request);

        try {
            $invitationLink = DB::transaction(function () use ($request, $validated) {
                // 2. Handle Photo Upload
                $photoPath = null;
                if ($request->hasFile('photo')) {
                    $photoPath = $request->file('photo')->store('residents', 'public');
                }

                // 3. Create Resident Profile
                $resident = Resident::create([
                    'first_name'     => $validated['first_name'],
                    'last_name'      => $validated['last_name'],
                    'email'          => $validated['email'],
                    'contact_number' => $validated['contact'],
                    'block'          => $validated['block'],
                    'lot'            => $validated['lot'],
                    'move_in_date'   => $validated['move_in_date'],
                    'status'         => $validated['status'],
                    'photo'          => $photoPath,
                ]);

                // 4. Generate Invitation Token
                $token = Str::random(40);
                $expiresAt = Carbon::now()->addDays(7);

                // 5. Create the invitation
                Invitation::create([
                    'resident_id' => $resident->id,
                    'name' => $resident->full_name,
                    'email' => $resident->email,
                    'token' => $token,
                    'role' => 'resident',
                    'status' => Invitation::STATUS_PENDING,
                    'expires_at' => $expiresAt,
                    'used' => false,
                    'email_status' => Invitation::DELIVERY_PENDING,
                    'sms_status' => Invitation::DELIVERY_PENDING,
                    'last_sent_at' => now(),
                ]);

                return route('register.invitation', ['token' => $token]);
            });

            return redirect()->route('admin.residents.index')
                             ->with('success', 'Resident created successfully.')
                             ->with('invitation_link', $invitationLink);

        } catch (Throwable $e) {
            return back()->with('error', 'Error creating resident: ' . $e->getMessage())->withInput();
        }
    }

    // ================================
    // SHOW
    // ================================
    public function show(Request $request, Resident $resident)
    {
        $resident->load([
            'dues'      => fn($q) => $q->latest(),
            'payments'  => fn($q) => $q->latest(),
            'penalties' => fn($q) => $q->latest(),
        ]);

        $financials = [
            'outstandingDues' => $resident->dues()->where('status', 'unpaid')->sum('amount'),
            'totalPayments'   => $resident->payments()->sum('amount'),
            'totalPenalties'  => $resident->penalties()->sum('amount'),
        ];

        if ($request->ajax()) {
            return view('admin.residents.partials.drawer-content', compact('resident', 'financials'));
        }

        return view('admin.residents.show', compact('resident', 'financials'));
    }

    // ================================
    // EDIT / UPDATE
    // ================================
    public function edit(Resident $resident)
    {
        return view('admin.residents.edit', compact('resident'));
    }

    public function update(Request $request, Resident $resident)
    {
        $data = $this->validateResident($request, $resident->id);

        // Map 'contact' from validation to 'contact_number' for the model
        if (isset($data['contact'])) {
            $data['contact_number'] = $data['contact'];
            unset($data['contact']);
        }

        // Remove password from update as per requirement
        unset($data['password']);

        if ($request->hasFile('photo')) {
            if ($resident->photo && Storage::disk('public')->exists($resident->photo)) {
                Storage::disk('public')->delete($resident->photo);
            }
            $data['photo'] = $request->file('photo')->store('residents', 'public');
        }

        $resident->update($data);

        return redirect()->route('admin.residents.index')
                         ->with('success', 'Resident successfully updated.');
    }

    // ================================
    // DELETE
    // ================================
    public function destroy(Resident $resident)
    {
        if ($resident->photo && Storage::disk('public')->exists($resident->photo)) {
            Storage::disk('public')->delete($resident->photo);
        }

        $resident->delete();

        return redirect()->route('admin.residents.index')
                         ->with('success', 'Resident deleted successfully.');
    }

    // ================================
    // BULK DELETE
    // ================================
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:residents,id',
        ]);

        $ids = $request->input('ids');
        $residents = Resident::whereIn('id', $ids)->get();

        foreach ($residents as $resident) {
            if ($resident->photo && Storage::disk('public')->exists($resident->photo)) {
                Storage::disk('public')->delete($resident->photo);
            }
            $resident->delete();
        }

        return redirect()->route('admin.residents.index')
                         ->with('success', count($residents) . ' residents deleted successfully.');
    }

    // ================================
    // VALIDATION
    // ================================
    private function validateResident(Request $request, ?int $residentId = null): array
    {
        return $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'contact'      => 'required|string|max:255',
            'block'        => 'required|integer|min:1',
            'lot'          => 'required|integer|min:1',
            'email'        => $residentId
                ? 'required|email|max:255|unique:residents,email,' . $residentId
                : 'required|email|max:255|unique:residents,email|unique:users,email',
            'move_in_date' => 'required|date',
            'status'       => 'required|in:active,inactive',
            'photo'        => 'nullable|image|max:2048',
        ]);
    }
}
