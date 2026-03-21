<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;

class ServiceRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = ServiceRequest::with('resident');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $requests->whereHas('resident', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('block', 'like', "%{$search}%")
                  ->orWhere('lot', 'like', "%{$search}%");
            });
        }
        
        // Block Filter
        if ($request->filled('block')) {
            $block = $request->block;
            $requests->whereHas('resident', function($q) use ($block) {
                $q->where('block', $block);
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $requests->where('status', $request->status);
        } else {
            // Tab Logic (View)
            $view = $request->get('view', 'active');
            if ($view === 'history') {
                $requests->whereIn('status', ['completed', 'approved', 'rejected']);
            } else {
                $requests->whereIn('status', ['pending', 'in progress']);
            }
        }

        // Priority Filter
        if ($request->filled('priority')) {
            $requests->where('priority', $request->priority);
        }

        // Date Filter
        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $requests->whereDate('created_at', \Carbon\Carbon::today());
                    break;
                case 'week':
                    $requests->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $requests->whereMonth('created_at', \Carbon\Carbon::now()->month)
                             ->whereYear('created_at', \Carbon\Carbon::now()->year);
                    break;
            }
        }

        // Sorting
        $sort = $request->get('sort');
        switch ($sort) {    
            case 'oldest':
                $requests->orderBy('created_at', 'asc');
                break;
            case 'priority_high':
                // Custom sort for priority (High -> Medium -> Low)
                $requests->orderByRaw("FIELD(priority, 'high', 'medium', 'low')");
                break;
            case 'priority_low':
                $requests->orderByRaw("FIELD(priority, 'low', 'medium', 'high')");
                break;
            case 'newest':
                $requests->orderBy('created_at', 'desc');
                break;
            default:
                $requests->orderBy('updated_at', 'desc');
                break;
        }

        $requests = $requests->paginate(15);

        $baseQuery = ServiceRequest::query();

        $summaryTotal = (clone $baseQuery)->count();
        $summaryPending = (clone $baseQuery)->where('status', 'pending')->count();
        $summaryInProgress = (clone $baseQuery)->where('status', 'in progress')->count();
        $summaryCompleted = (clone $baseQuery)->where('status', 'completed')->count();
        $summaryRejected = (clone $baseQuery)->where('status', 'rejected')->count();

        $typeBreakdown = (clone $baseQuery)
            ->selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->orderByDesc('total')
            ->get();

        if ($request->ajax()) {
            return view('admin.requests.partials.list', compact('requests'))->render();
        }

        $blocks = \App\Models\Resident::select('block')->distinct()->orderBy('block')->pluck('block');

        return view('admin.requests.index', compact(
            'requests',
            'blocks',
            'summaryTotal',
            'summaryPending',
            'summaryInProgress',
            'summaryCompleted',
            'summaryRejected',
            'typeBreakdown'
        ));
    }

    // ✅ Show specific request (optional)
    public function show($id)
    {
        $request = ServiceRequest::with('resident')->findOrFail($id);
        if (request()->ajax()) {
            return view('admin.requests.partials.drawer', compact('request'))->render();
        }
        return view('admin.requests.show', compact('request'));
    }

    // ✅ Update request status (Pending → In Progress → Completed)
    public function updateStatus(Request $request, $id)
    {
        $req = ServiceRequest::findOrFail($id);
        $status = $request->status;
        
        $updateData = ['status' => $status];

        if ($status === 'in progress' && !$req->processed_at) {
            $updateData['processed_at'] = now();
        } elseif ($status === 'completed' && !$req->completed_at) {
            $updateData['completed_at'] = now();
            // Also ensure processed_at is set if skipped
            if (!$req->processed_at) {
                $updateData['processed_at'] = now();
            }
        }

        $req->update($updateData);

        // Create Notification
        $title = match($status) {
            'in progress' => '🛠 Request In Progress',
            'completed' => '✅ Request Completed',
            'rejected' => '❌ Request Rejected',
            default => '🛠 Request Updated',
        };

        $message = match($status) {
            'in progress' => "Your maintenance request for '{$req->type}' is now being processed.",
            'completed' => "Your request for '{$req->type}' has been resolved and completed.",
            'rejected' => "Your request for '{$req->type}' has been rejected. Please contact the office.",
            default => "The status of your request '{$req->type}' has been updated to {$status}.",
        };

        \App\Models\Notification::create([
            'resident_id' => $req->resident_id,
            'title' => $title,
            'message' => $message,
            'type' => 'request',
            'link' => route('resident.requests.show', $req->id),
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Request status updated successfully!');
    }
}
