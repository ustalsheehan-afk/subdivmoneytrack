<?php

use App\Models\Due;
use App\Models\Resident;
use App\Models\Payment;
use App\Models\Penalty;
use Carbon\Carbon;
use Illuminate\Support\Str;

$residents = Resident::all();
if ($residents->isEmpty()) {
    echo "No residents found! Please seed residents first.\n";
    exit;
}

$jan1 = Carbon::create(2026, 1, 1);
$jan31 = Carbon::create(2026, 1, 31);
$now = now();

echo "Starting Jan 2026 Setup...\n";

// --- 1. Create Dues ---

// Batch 1: Monthly HOA Dues
$batchIdHOA = Str::uuid()->toString();
$hoaDuesData = [];
foreach ($residents as $resident) {
    $hoaDuesData[] = [
        'id' => Str::uuid()->toString(),
        'batch_id' => $batchIdHOA,
        'resident_id' => $resident->id,
        'title' => 'Monthly HOA Dues - January',
        'description' => 'Monthly Homeowners Association Dues',
        'amount' => 1500.00,
        'type' => 'monthly_hoa',
        'frequency' => 'monthly',
        'status' => 'unpaid',
        'due_date' => $jan31->toDateString(),
        'billing_period_start' => $jan1->toDateString(),
        'billing_period_end' => $jan31->toDateString(),
        'month' => 'January',
        'created_at' => $now,
        'updated_at' => $now,
    ];
}
Due::insert($hoaDuesData);
echo "Created HOA Dues (Batch: $batchIdHOA)\n";

// Batch 2: Parking Fees
$batchIdParking = Str::uuid()->toString();
$parkingDuesData = [];
foreach ($residents as $resident) {
    $parkingDuesData[] = [
        'id' => Str::uuid()->toString(),
        'batch_id' => $batchIdParking,
        'resident_id' => $resident->id,
        'title' => 'Parking Fees',
        'description' => 'Regular Parking Fees',
        'amount' => 200.00,
        'type' => 'regular_fees',
        'frequency' => 'monthly',
        'status' => 'unpaid',
        'due_date' => $jan31->toDateString(),
        'billing_period_start' => $jan1->toDateString(),
        'billing_period_end' => $jan31->toDateString(),
        'month' => 'January',
        'created_at' => $now,
        'updated_at' => $now,
    ];
}
Due::insert($parkingDuesData);
echo "Created Parking Dues (Batch: $batchIdParking)\n";

// --- 2. Payments ---

// HOA: All Paid
$hoaDues = Due::where('batch_id', $batchIdHOA)->get();
foreach ($hoaDues as $due) {
    $methods = ['cash', 'gcash', 'bank transfer'];
    $method = $methods[array_rand($methods)];
    // Random date between Jan 1 and Jan 25
    $paidDate = $jan1->copy()->addDays(rand(0, 25));

    Payment::create([
        'resident_id' => $due->resident_id,
        'due_id' => $due->id,
        'amount' => $due->amount,
        'date_paid' => $paidDate,
        'payment_method' => $method,
        'status' => 'approved',
        'reference_no' => 'JAN-' . strtoupper(uniqid()),
    ]);

    $due->update(['status' => 'paid']);
}
echo "Marked all HOA Dues as PAID.\n";

// Parking: Some Paid (70%)
$parkingDues = Due::where('batch_id', $batchIdParking)->get();
$payingResidents = $parkingDues->shuffle()->take((int)($parkingDues->count() * 0.7));

foreach ($payingResidents as $due) {
    $methods = ['cash', 'gcash', 'bank transfer'];
    $method = $methods[array_rand($methods)];
    $paidDate = $jan1->copy()->addDays(rand(0, 25));

    Payment::create([
        'resident_id' => $due->resident_id,
        'due_id' => $due->id,
        'amount' => $due->amount,
        'date_paid' => $paidDate,
        'payment_method' => $method,
        'status' => 'approved',
        'reference_no' => 'JAN-' . strtoupper(uniqid()),
    ]);

    $due->update(['status' => 'paid']);
}
echo "Marked " . $payingResidents->count() . " Parking Dues as PAID.\n";

// --- 3. Penalties ---

// Apply to UNPAID Parking Dues
$unpaidParking = Due::where('batch_id', $batchIdParking)->where('status', 'unpaid')->get();

foreach ($unpaidParking as $due) {
    Penalty::create([
        'resident_id' => $due->resident_id,
        'due_id' => $due->id,
        'type' => 'Overdue',
        'amount' => 50.00,
        'reason' => 'Penalty for overdue Parking Fees - January 2026',
        'status' => 'pending',
        'date_issued' => $now,
    ]);
}
echo "Applied " . $unpaidParking->count() . " Penalties for unpaid Parking Fees.\n";
