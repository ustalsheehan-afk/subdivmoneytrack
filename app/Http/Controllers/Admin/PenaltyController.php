<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penalty;
use App\Models\Resident;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use App\Traits\LogsActivity;

class PenaltyController extends Controller
{
    use LogsActivity;
    /**
     * Display a listing of all penalties (index page).
     */
    public function index(Request $request)
    {
        $penalties = Penalty::with('resident', 'payment');

        // SEARCH: resident first/last name, block, lot
        if ($request->filled('search')) {
            $search = $request->input('search');
            $penalties = $penalties->whereHas('resident', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('block', 'like', "%{$search}%")
                  ->orWhere('lot', 'like', "%{$search}%");
            });
        }

        // BLOCK FILTER
        if ($request->filled('block')) {
            $block = $request->block;
            $penalties = $penalties->whereHas('resident', function($q) use ($block) {
                $q->where('block', $block);
            });
        }

        // LOT FILTER
        if ($request->filled('lot')) {
            $lot = $request->lot;
            $penalties = $penalties->whereHas('resident', function($q) use ($lot) {
                $q->where('lot', $lot);
            });
        }

        // STATUS FILTER
        if ($request->filled('status')) {
            $penalties = $penalties->where('status', $request->status);
        }

        // TYPE FILTER
        if ($request->filled('type')) {
            $penalties = $penalties->where('type', $request->type);
        }

        // DATE FILTERS
        if ($request->filled('month')) {
            $penalties = $penalties->whereMonth('date_issued', $request->month);
        }

        if ($request->filled('year')) {
            $penalties = $penalties->whereYear('date_issued', $request->year);
        }

        if ($request->filled('custom_date')) {
            $penalties = $penalties->whereDate('date_issued', $request->custom_date);
        }

        // ORDER + PAGINATE
        $sortField = $request->input('sort_by', 'updated_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $allowedSorts = ['date_issued', 'amount', 'status', 'type', 'updated_at'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'updated_at';
        }
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $penalties = $penalties->orderBy($sortField, $sortOrder)->paginate(15);

        if ($request->ajax()) {
            return view('admin.penalties.partials.rows', compact('penalties'))->render();
        }

        // Stats
        $totalPaid     = Penalty::where('status', 'paid')->sum('amount');
        $totalPending  = Penalty::where('status', 'pending')->sum('amount');
        $totalUnpaid   = Penalty::where('status', 'unpaid')->sum('amount');
        $totalCount    = Penalty::count();

        // Filter Data
        $blockLots = Resident::select('block', 'lot')
            ->whereNotNull('block')
            ->whereNotNull('lot')
            ->distinct()
            ->orderBy('block')
            ->orderBy('lot')
            ->get()
            ->groupBy('block');

        return view('admin.penalties.index', compact(
            'penalties', 
            'totalPaid', 
            'totalPending', 
            'totalUnpaid', 
            'totalCount',
            'blockLots'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(Penalty $penalty)
    {
        $penalty->load(['resident', 'payment']);
        return view('admin.penalties.partials.drawer', compact('penalty'))->render();
    }

    /**
     * Show form to create a new penalty.
     */
    public function create()
    {
        $residents = Resident::orderBy('first_name')->get();
        $payments  = Payment::orderByDesc('date_paid')->get();
        return view('admin.penalties.create', compact('residents', 'payments'));
    }

    /**
     * Store a new penalty.
     */
    public function store(Request $request)
    {
        $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'payment_id'  => 'nullable|exists:payments,id',
            'amount'      => 'required|numeric|min:0',
            'date_issued' => 'nullable|date',
            'reason'      => 'nullable|string',
            'status'      => 'required|in:unpaid,paid,pending',
            'type'        => 'nullable|in:general,late_payment,overdue,violation,damage',
        ]);

        $penalty = Penalty::create([
            'resident_id' => $request->resident_id,
            'payment_id'  => $request->payment_id,
            'amount'      => $request->amount,
            'date_issued' => $request->date_issued ?? now(),
            'reason'      => $request->reason,
            'status'      => $request->status,
            'type'        => $request->type ?? 'general',
        ]);

        $this->logActivity('created', 'penalties', 'Issued penalty of ₱' . number_format($penalty->amount, 2) . ' to ' . $penalty->resident->first_name . ' ' . $penalty->resident->last_name, [
            'penalty_id' => $penalty->id,
            'resident_id' => $penalty->resident_id
        ]);

        return redirect()->route('admin.penalties.index')
                         ->with('success', 'Penalty created successfully.');
    }

    /**
     * Show form to edit a penalty.
     */
    public function edit(Penalty $penalty)
    {
        $residents = Resident::orderBy('first_name')->get();
        $payments  = Payment::orderByDesc('date_paid')->get();
        return view('admin.penalties.edit', compact('penalty', 'residents', 'payments'));
    }

    /**
     * Update penalty in database.
     */
    public function update(Request $request, Penalty $penalty)
    {
        $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'payment_id'  => 'nullable|exists:payments,id',
            'amount'      => 'required|numeric|min:0',
            'date_issued' => 'nullable|date',
            'reason'      => 'nullable|string',
            'status'      => 'required|in:unpaid,paid,pending',
            'type'        => 'nullable|in:general,late_payment,overdue,violation,damage',
        ]);

        $penalty->update([
            'resident_id' => $request->resident_id,
            'payment_id'  => $request->payment_id,
            'amount'      => $request->amount,
            'date_issued' => $request->date_issued ?? now(),
            'reason'      => $request->reason,
            'status'      => $request->status,
            'type'        => $request->type ?? 'general',
        ]);

        $this->logActivity('updated', 'penalties', 'Updated penalty for ' . $penalty->resident->first_name . ' ' . $penalty->resident->last_name, [
            'penalty_id' => $penalty->id,
            'new_status' => $penalty->status
        ]);

        return redirect()->route('admin.penalties.index')
                         ->with('success', 'Penalty updated successfully.');
    }

    /**
     * Delete a penalty.
     */
    public function destroy(Penalty $penalty)
    {
        $penalty->delete();
        return redirect()->route('admin.penalties.index')
                         ->with('success', 'Penalty deleted successfully.');
    }

    /**
     * Bulk delete penalties.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('selected_ids');
        if ($ids && is_array($ids)) {
            Penalty::whereIn('id', $ids)->delete();
            return redirect()->route('admin.penalties.index')
                             ->with('success', count($ids) . ' penalties deleted successfully.');
        }
        return redirect()->back()->with('error', 'No penalties selected.');
    }

    /**
     * Get penalty data for AJAX drawer.
     */
    public function getData($id)
    {
        $penalty = Penalty::with(['resident', 'payment'])->findOrFail($id);

        // Status Logic
        $status = $penalty->status;
        $badgeClass = '';
        $dotClass = '';

        if ($status === 'paid') {
            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-100';
            $dotClass = 'bg-emerald-500';
        } elseif ($status === 'pending') {
            $badgeClass = 'bg-yellow-50 text-yellow-700 border-yellow-100';
            $dotClass = 'bg-yellow-500';
        } else {
            $badgeClass = 'bg-red-50 text-red-700 border-red-100';
            $dotClass = 'bg-red-500';
        }

        return response()->json([
            'id' => str_pad($penalty->id, 5, '0', STR_PAD_LEFT),
            'customTitle' => 'Penalty #' . str_pad($penalty->id, 5, '0', STR_PAD_LEFT),
            'resident_name' => $penalty->resident->first_name . ' ' . $penalty->resident->last_name,
            'resident_photo' => $penalty->resident->photo 
                ? Storage::disk('public')->url($penalty->resident->photo)
                : null, // Frontend handles null with onerror
            'resident_property' => 'Block ' . ($penalty->resident->block ?? '?') . ' Lot ' . ($penalty->resident->lot ?? '?'),
            'resident_profile_url' => route('admin.residents.show', $penalty->resident->id),
            'amount' => '₱' . number_format($penalty->amount, 2),
            'type' => ucfirst(str_replace('_', ' ', $penalty->type)),
            'status' => ucfirst($status),
            'status_badge_class' => $badgeClass,
            'status_dot_class' => $dotClass,
            'reason' => $penalty->reason ?? 'No description provided.',
            'date_issued' => $penalty->date_issued ? Carbon::parse($penalty->date_issued)->format('M d, Y') : '-',
            'has_payment' => (bool) $penalty->payment,
            'payment_id' => $penalty->payment ? $penalty->payment->id : null,
            'payment_or' => $penalty->payment ? ($penalty->payment->or_number ?? 'No OR Number') : null,
            'payment_date' => $penalty->payment && $penalty->payment->date_paid ? Carbon::parse($penalty->payment->date_paid)->format('M d, Y') : '-',
            'edit_url' => route('admin.penalties.edit', $penalty->id),
            'delete_url' => route('admin.penalties.destroy', $penalty->id),
        ]);
    }

    /**
     * Resident-specific timeline (optional).
     */
    public function timeline(Request $request, Resident $resident)
    {
        $penalties = $resident->penalties()->with('payment');

        if ($request->filled('status')) {
            $penalties = $penalties->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $penalties = $penalties->where('type', $request->type);
        }
        if ($request->filled('month')) {
            $penalties = $penalties->whereMonth('date_issued', $request->month);
        }
        if ($request->filled('year')) {
            $penalties = $penalties->whereYear('date_issued', $request->year);
        }
        if ($request->filled('custom_date')) {
            $penalties = $penalties->whereDate('date_issued', $request->custom_date);
        }

        $penalties = $penalties->orderByDesc('date_issued')->paginate(10);

        return view('admin.penalties.timeline', compact('resident', 'penalties'));
    }
}
