<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payments.view')->only(['index']);
        $this->middleware('permission:payments.update')->only(['approve', 'reject']);
    }

    public function index()
    {
        $payments = Payment::with('homeowner','due')->latest()->paginate(15);
        return view('admin.payments.index', compact('payments'));
    }

    public function approve($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->approve();
        return back()->with('success','Payment approved.');
    }

    public function reject($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->reject();
        return back()->with('success','Payment rejected.');
    }
}
