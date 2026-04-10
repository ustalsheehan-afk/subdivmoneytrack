<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| AUTH CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\ResidentAuthController; // Custom Resident Auth
use App\Http\Controllers\Auth\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| ADMIN CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BoardMemberController;
use App\Http\Controllers\Admin\ResidentController;
use App\Http\Controllers\Admin\DueController;
use App\Http\Controllers\Admin\DuesBatchController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PenaltyController;
use App\Http\Controllers\Admin\SmsTemplateController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ServiceRequestController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\AmenityReservationController as AdminAmenityReservationController;
use App\Http\Controllers\Admin\DummyReservationController;

/*
|--------------------------------------------------------------------------
| RESIDENT CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Resident\DashboardController as ResidentDashboardController;
use App\Http\Controllers\Resident\ResidentPaymentController;
use App\Http\Controllers\Resident\RequestController as ResidentRequestController;
use App\Http\Controllers\Resident\DuesController as ResidentDuesController;
use App\Http\Controllers\Resident\AnnouncementController as ResidentAnnouncementController;
use App\Http\Controllers\Resident\ProfileController as ResidentProfileController;
use App\Http\Controllers\Resident\PenaltyController as ResidentPenaltyController;
use App\Http\Controllers\Resident\AmenityController as ResidentAmenityController;
use App\Http\Controllers\Resident\AmenityReservationController as ResidentAmenityReservationController;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| MODELS
|--------------------------------------------------------------------------
*/
use App\Models\Announcement;

use App\Http\Controllers\Auth\InvitationRegistrationController;
use App\Http\Controllers\Admin\InvitationController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

// ✅ FIXED: Invitation Registration (TOKEN REQUIRED)
Route::get('/register', [InvitationRegistrationController::class, 'show'])->name('register.invitation');

use Illuminate\Support\Facades\Artisan;

Route::get('/fix-storage', function () {
    Artisan::call('storage:link');
    return 'Storage linked!';
});

/*
|--------------------------------------------------------------------------
| MAINTENANCE ROUTE (SHARED HOSTING FIX)
|--------------------------------------------------------------------------
*/
Route::get('/sys-maintenance', function () {
    if (!app()->environment('production')) {
        return 'Not in production environment.';
    }

    try {
        // 1. Create Storage Link
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        $output1 = \Illuminate\Support\Facades\Artisan::output();

        // 2. Clear Caches
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        $output2 = "Caches cleared successfully.";

        return "<h1>System Maintenance</h1><pre>$output1\n$output2</pre>";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
})->middleware('web');
Route::post('/register', [InvitationRegistrationController::class, 'register'])->name('register.invitation.submit');

Route::get('/registration-success', function() {
    return view('auth.registration-success');
})->name('register.success');

Route::get('/announcements', [AnnouncementController::class, 'public'])->name('announcements.public');
Route::get('/verify-receipt/{id}', [PaymentController::class, 'verifyReceipt'])->name('payments.verify');

// Simple Debug Route
Route::get('/test-invite/{token}', function($token) {
    $invitation = \App\Models\Invitation::where('token', $token)->first();
    if (!$invitation) return 'NOT FOUND';
    if ($invitation->isExpired()) return 'EXPIRED';
    if ($invitation->status !== \App\Models\Invitation::STATUS_PENDING) return 'NOT PENDING';
    return 'VALID';
});

/*
|--------------------------------------------------------------------------
| SMART UNIFIED AUTH (Login & Password Reset)
|--------------------------------------------------------------------------
*/
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.submit');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Forgot Password
Route::get('forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
Route::post('forgot-password', [PasswordResetController::class, 'store'])
    ->middleware('throttle:3,10')
    ->name('password.email');

// Reset Password
Route::get('reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
Route::post('reset-password', [PasswordResetController::class, 'update'])
    ->middleware('throttle:5,10')
    ->name('password.update');

// Legacy redirects
Route::get('admin/login', function() { return redirect()->route('login'); })->name('admin.login');
Route::get('resident/login', function() { return redirect()->route('login'); })->name('resident.login');
Route::get('resident/forgot-password', function() { return redirect()->route('password.request'); });

/*
|--------------------------------------------------------------------------
| ADMIN PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');
    Route::post('notifications/mark-all-read', [DashboardController::class, 'markAllNotificationsAsRead'])->name('notifications.mark-all-read');
    Route::get('home', [DashboardController::class, 'index'])->name('home');
    Route::get('about-board', [DashboardController::class, 'board'])->name('about.board');

    // System Notifications API
    Route::get('system-notifications', [App\Http\Controllers\Admin\NotificationController::class, 'getSystemNotifications'])
        ->middleware('permission:notifications.view')
        ->name('system-notifications');

    // Board Members
    Route::resource('board', BoardMemberController::class);
    Route::post('board/{board}/toggle', [BoardMemberController::class, 'toggleStatus'])->name('board.toggle');

    // Announcements
    Route::get('announcements/trashed', [AnnouncementController::class, 'trashed'])->name('announcements.trashed');
    Route::get('announcements/archive', [AnnouncementController::class, 'archive'])->name('announcements.archive');
    Route::get('announcements/drafts', [AnnouncementController::class, 'drafts'])->name('announcements.drafts');
    Route::patch('announcements/bulk-restore', [AnnouncementController::class, 'bulkRestore'])->name('announcements.bulkRestore');
    Route::post('announcements/bulk-archive', [AnnouncementController::class, 'bulkArchive'])->name('announcements.bulkArchive');
    Route::delete('announcements/bulk-trash', [AnnouncementController::class, 'bulkTrash'])->name('announcements.bulkTrash');
    Route::delete('announcements/bulk-force-delete', [AnnouncementController::class, 'bulkForceDelete'])->name('announcements.bulkForceDelete');
    Route::post('announcements/{announcement}/archive', [AnnouncementController::class, 'archiveOne'])->name('announcements.archiveOne');
    Route::patch('announcements/{announcement}/restore', [AnnouncementController::class, 'restore'])->name('announcements.restore');
    Route::delete('announcements/{announcement}/force-delete', [AnnouncementController::class, 'forceDelete'])->name('announcements.forceDelete');
    Route::patch('announcements/{announcement}/toggle-pin', [AnnouncementController::class, 'togglePin'])->name('announcements.togglePin');
    Route::resource('announcements', AnnouncementController::class);

    // Dues actions (New Batched System)
    Route::get('dues/dashboard', [DuesBatchController::class, 'dashboard'])->name('dues.dashboard');
    Route::post('dues/{due}/pay', [DuesBatchController::class, 'markAsPaid'])->name('dues.markAsPaid');
    Route::post('dues/send-sms-reminders', [DuesBatchController::class, 'sendSmsReminders'])->name('dues.sendSmsReminders');
    Route::resource('dues', DuesBatchController::class)->names([
        'index' => 'dues.index',
        'create' => 'dues.create',
        'store' => 'dues.store',
        'show' => 'dues.show',
        'edit' => 'dues.edit',
        'update' => 'dues.update',
        'destroy' => 'dues.destroy',
    ]);

    // Reminders
    Route::post('reminders/send', [DashboardController::class, 'sendReminders'])->name('reminders.send');

    // Messages Center (Refactored SaaS Structure)
    Route::group(['prefix' => 'messages', 'as' => 'messages.'], function() {
        Route::get('support', [App\Http\Controllers\Admin\MessageController::class, 'index'])->name('index');
        Route::get('support/{thread}', [App\Http\Controllers\Admin\MessageController::class, 'show'])->name('show');
        Route::post('support/{thread}/reply', [App\Http\Controllers\Admin\MessageController::class, 'reply'])->name('reply');
        Route::post('support/{thread}/status', [App\Http\Controllers\Admin\MessageController::class, 'updateStatus'])->name('updateStatus');
        Route::post('support/{thread}/action', [App\Http\Controllers\Admin\MessageController::class, 'performAction'])->name('performAction');
        
        Route::get('notifications', [App\Http\Controllers\Admin\NotificationModuleController::class, 'index'])->name('notifications.index');
        Route::post('notifications/{notification}/read', [App\Http\Controllers\Admin\NotificationModuleController::class, 'markAsRead'])->name('notifications.read');
        Route::post('notifications/mark-all-read', [App\Http\Controllers\Admin\NotificationModuleController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    });

    // System (Reports & Logs)
    Route::group(['prefix' => 'system', 'as' => 'system.'], function() {
        Route::get('reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])
            ->middleware('permission:reports.view')
            ->name('reports.index');

        Route::get('activity-logs', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])
            ->middleware('permission:logs.view')
            ->name('activity-logs.index');
        Route::get('activity-logs/export', [App\Http\Controllers\Admin\ActivityLogController::class, 'export'])
            ->middleware('permission:logs.export')
            ->name('activity-logs.export');

        Route::get('roles-permissions', [App\Http\Controllers\Admin\RolesPermissionsController::class, 'index'])
            ->middleware('permission:roles.view')
            ->name('roles-permissions.index');
        Route::post('roles-permissions/{role}/toggle', [App\Http\Controllers\Admin\RolesPermissionsController::class, 'toggle'])
            ->middleware('permission:roles.update')
            ->name('roles-permissions.toggle');
    });

    // Resource routes
    Route::resources([
        'residents'     => ResidentController::class,
        'penalties'     => PenaltyController::class,
        'accounts'      => AccountController::class,
        'requests'      => ServiceRequestController::class,
        'amenities'     => AmenityController::class,
    ]);

    Route::post('residents/{resident}/update-notes', [ResidentController::class, 'updateNotes'])->name('residents.update-notes');

    Route::get('payments/{id}/review', [PaymentController::class, 'review'])->name('payments.review');
    Route::post('payments/review', [PaymentController::class, 'store'])->name('payments.review-post');
    Route::resource('payments', PaymentController::class)->names('payments');
    Route::post('payments/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
    Route::get('payments/{id}/receipt', [PaymentController::class, 'receipt'])->name('payments.view.receipt');

    // Amenity Reservations
    Route::get('dummy-reservation', [DummyReservationController::class, 'index'])->name('dummy-reservation');
    Route::get('amenity-reservations', [AdminAmenityReservationController::class, 'index'])->name('amenity-reservations.index');
    Route::get('amenity-reservations/create', [AdminAmenityReservationController::class, 'create'])->name('amenity-reservations.create');
    Route::post('amenity-reservations', [AdminAmenityReservationController::class, 'store'])->name('amenity-reservations.store');
    Route::get('amenity-reservations/{reservation}/confirmation', [AdminAmenityReservationController::class, 'confirmation'])->name('amenity-reservations.confirmation');
    Route::get('amenity-reservations/{amenity}/unavailable-slots', [AdminAmenityReservationController::class, 'getUnavailableSlots'])->name('amenity-reservations.unavailable-slots');
    Route::get('amenity-reservations/export', [AdminAmenityReservationController::class, 'export'])->name('amenity-reservations.export');
    Route::get('amenity-reservations/export-csv', [AdminAmenityReservationController::class, 'exportCsv'])->name('amenity-reservations.exportCsv');
    Route::get('amenity-reservations/data', [AdminAmenityReservationController::class, 'getData'])->name('amenity-reservations.data');
    Route::post('amenity-reservations/bulk-action', [AdminAmenityReservationController::class, 'bulkAction'])->name('amenity-reservations.bulk-action');
    Route::post('amenity-reservations/{reservation}/status', [AdminAmenityReservationController::class, 'updateStatus'])->name('amenity-reservations.updateStatus');
    Route::post('amenity-reservations/{reservation}/verify-payment', [AdminAmenityReservationController::class, 'verifyPayment'])->name('amenity-reservations.verifyPayment');
    Route::post('amenity-reservations/{reservation}/reject-payment', [AdminAmenityReservationController::class, 'rejectPayment'])->name('amenity-reservations.rejectPayment');
    Route::get('amenity-reservations/{reservation}/receipt', [AdminAmenityReservationController::class, 'viewReceipt'])->name('amenity-reservations.receipt');
    Route::get('amenity-reservations/{reservation}/download-receipt', [AdminAmenityReservationController::class, 'downloadReceipt'])->name('amenity-reservations.download.receipt');
    Route::post('amenity-reservations/{amenity}/toggle-maintenance', [AdminAmenityReservationController::class, 'toggleMaintenance'])->name('amenity-reservations.toggleMaintenance');
    Route::post('amenity-reservations/{reservation}/reschedule', [AdminAmenityReservationController::class, 'reschedule'])->name('amenity-reservations.reschedule');
    Route::post('amenity-reservations/{reservation}/cancel', [AdminAmenityReservationController::class, 'cancel'])->name('amenity-reservations.cancel');

    // Dues custom actions
    Route::get('dues/export', [DueController::class, 'export'])->name('dues.export');
    Route::post('dues/{batchId}/archive', [DueController::class, 'archive'])->name('dues.archive');
    // Removed redundant and conflicting dues.destroy route


    // Residents export and bulk actions
    Route::get('residents/export', [ResidentController::class, 'export'])->name('residents.export');
    Route::post('residents/bulk-destroy', [ResidentController::class, 'bulkDestroy'])->name('residents.bulkDestroy');

    // Penalties bulk actions
    Route::post('penalties/bulk-destroy', [PenaltyController::class, 'bulkDestroy'])->name('penalties.bulkDestroy');
    Route::post('penalties/bulk-approve', [PenaltyController::class, 'bulkApprovePenalties'])->name('penalties.bulkApprove');
    Route::post('penalties/send-sms-notices', [PenaltyController::class, 'sendSmsNotices'])->name('penalties.sendSmsNotices');
    Route::get('penalties/{penalty}/data', [PenaltyController::class, 'getData'])->name('penalties.data');

    Route::get('notifications/sms-templates', [SmsTemplateController::class, 'index'])->name('smsTemplates.index');
    Route::post('notifications/sms-templates', [SmsTemplateController::class, 'update'])->name('smsTemplates.update');

    // Payments custom actions
    Route::post('payments/bulk-action', [PaymentController::class, 'bulkAction'])->name('payments.bulkAction');
    Route::post('payments/bulk-approve', [PaymentController::class, 'bulkApprovePayments'])->name('payments.bulkApprove');
    Route::get('residents/{resident}/dues', [PaymentController::class, 'getDuesByResident'])->name('payments.getDuesByResident');
    Route::post('payments/{payment}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
    Route::post('payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
    Route::get('payments/{payment}/data', [PaymentController::class, 'getData'])->name('payments.data');
    Route::post('payments/{id}/update-status', [PaymentController::class, 'updateStatus'])->name('payments.updateStatus');
    Route::get('payments/{payment}/receipt', [PaymentController::class, 'downloadReceipt'])->name('payments.receipt');

    // Accounts actions
    Route::post('accounts/{id}/reset', [AccountController::class, 'reset'])->name('accounts.reset');
    Route::post('accounts/{id}/toggle', [AccountController::class, 'toggle'])->name('accounts.toggle');

    // Residents actions
    Route::post('residents/{resident}/login', [ResidentController::class, 'loginAsResident'])->name('residents.loginAs');
    Route::post('invitations', [InvitationController::class, 'store'])->name('invitations.store');
    Route::post('residents/invite', [InvitationController::class, 'store'])->name('residents.invite'); // Alias for backward compatibility
    Route::get('invitations', [InvitationController::class, 'index'])->name('invitations.index');
    Route::get('invitations/export', [InvitationController::class, 'export'])->name('invitations.export');
    Route::get('invitations/{id}', [InvitationController::class, 'show'])->name('invitations.show');
    Route::post('invitations/{id}/resend', [InvitationController::class, 'resend'])->name('invitations.resend');
    Route::post('invitations/{id}/renew', [InvitationController::class, 'renew'])->name('invitations.renew');
    Route::post('invitations/{id}/cancel', [InvitationController::class, 'cancel'])->name('invitations.cancel');

    // Service requests actions
    Route::post('requests/{id}/status', [ServiceRequestController::class, 'updateStatus'])->name('requests.updateStatus');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export/csv', [ReportController::class, 'exportCsv'])->name('reports.exportCsv');
    Route::get('reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.exportPdf');
    Route::get('reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.exportExcel');

});

/*
|--------------------------------------------------------------------------
| RESIDENT PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'resident'])->prefix('resident')->name('resident.')->group(function () {

    // Force Password Change Routes (Kept for manual access if needed, or we can remove if unused)
    Route::get('change-password', [ResidentAuthController::class, 'showChangePasswordForm'])->name('change-password');
    Route::post('change-password', [ResidentAuthController::class, 'updatePassword'])->name('change-password.update');

    // Dashboard
    Route::get('dashboard', [ResidentDashboardController::class, 'index'])->name('dashboard'); // Renamed to dashboard as per instruction, was home
    Route::get('home', [ResidentDashboardController::class, 'index'])->name('home'); // Alias for backward compatibility if needed

    // Public routes (About, Board, etc.)
    Route::get('/about/board', [ResidentDashboardController::class, 'board'])->name('about.board');
    Route::get('/about/amenities', function () {
        return view('resident.about.amenities');
    })->name('about.amenities');

    // Amenities & Reservations
    Route::get('amenities', [ResidentAmenityController::class, 'index'])->name('amenities.index');
    Route::get('amenities/{amenity}', [ResidentAmenityController::class, 'show'])->name('amenities.show');
    Route::get('amenities/{amenity}/reserve', [ResidentAmenityReservationController::class, 'store']);
    Route::post('amenities/{amenity}/reserve', [ResidentAmenityReservationController::class, 'store'])->name('amenities.reserve'); // Allow POST for form
    Route::get('amenities/{amenity}/unavailable-slots', [ResidentAmenityReservationController::class, 'getUnavailableSlots'])->name('amenities.unavailable-slots');
    Route::get('amenities/reservation/{reservation}/confirmation', [ResidentAmenityReservationController::class, 'confirmation'])->name('amenities.confirmation');
    Route::get('amenities/reservation/{reservation}', [ResidentAmenityReservationController::class, 'show'])->name('amenities.reservation.show');
    Route::get('amenities/reservation/{reservation}/receipt', [ResidentAmenityReservationController::class, 'viewReceipt'])->name('amenities.reservation.receipt');
    Route::get('amenities/reservation/{reservation}/download-receipt', [ResidentAmenityReservationController::class, 'downloadReceipt'])->name('amenities.reservation.download.receipt');
    Route::post('amenities/reservation/{reservation}/payment', [ResidentAmenityReservationController::class, 'uploadPayment'])->name('amenities.reservation.payment');
    Route::post('amenities/reservation/{reservation}/cancel', [ResidentAmenityReservationController::class, 'cancel'])->name('amenities.reservation.cancel');
    Route::get('my-reservations', [ResidentAmenityReservationController::class, 'index'])->name('my-reservations.index');

    // Profile
    Route::get('profile', [ResidentProfileController::class, 'index'])->name('profile.index');
    Route::get('profile/edit', [ResidentProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ResidentProfileController::class, 'update'])->name('profile.update');
    // Messaging System
    Route::get('messages', [App\Http\Controllers\Resident\MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/create', [App\Http\Controllers\Resident\MessageController::class, 'create'])->name('messages.create');
    Route::post('messages', [App\Http\Controllers\Resident\MessageController::class, 'store'])->name('messages.store');
    Route::get('messages/{thread}', [App\Http\Controllers\Resident\MessageController::class, 'show'])->name('messages.show');
    Route::post('messages/{thread}/reply', [App\Http\Controllers\Resident\MessageController::class, 'reply'])->name('messages.reply');

    Route::get('profile/settings', [ResidentProfileController::class, 'settings'])->name('profile.settings');


    // Dues
    Route::get('dues', [ResidentDuesController::class, 'index'])->name('dues.index');
    Route::get('dues/statement', [ResidentDuesController::class, 'statement'])->name('dues.statement');
    Route::get('dues/statement/download', [ResidentDuesController::class, 'downloadStatement'])->name('dues.download');

    // Payments
    Route::get('payments', [ResidentPaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{id}/pay', [ResidentPaymentController::class, 'pay'])->name('payments.pay'); 
    Route::post('payments/{id}/process', [ResidentPaymentController::class, 'processPayment'])->name('payments.process');

    // Penalties
    Route::get('penalties', [ResidentPenaltyController::class, 'index'])->name('penalties.index');
    Route::get('penalties/{id}', [ResidentPenaltyController::class, 'show'])->name('penalties.show');

    // Service Requests
    Route::resource('requests', ResidentRequestController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update'])
        ->names('requests');

    // Contact
    Route::get('contact', function () {
        return view('resident.contact');
    })->name('contact');

    // System Notifications API
    Route::get('system-notifications', [App\Http\Controllers\Admin\NotificationController::class, 'getSystemNotifications'])
        ->middleware('permission:notifications.view')
        ->name('system-notifications');

    // Announcements
    Route::get('announcements', [ResidentAnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('announcements/{announcement}', [ResidentAnnouncementController::class, 'show'])->name('announcements.show');
    Route::post('announcements/{announcement}/read', [ResidentAnnouncementController::class, 'markAsRead'])->name('announcements.read');

    // Upcoming Events
    Route::get('events', [ResidentDashboardController::class, 'events'])->name('events.index');

    // Notifications
    Route::get('notifications/{id}', [App\Http\Controllers\Resident\NotificationController::class, 'show'])->name('notifications.show');
    Route::post('notifications/mark-all-read', [App\Http\Controllers\Resident\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
});


/*
|--------------------------------------------------------------------------
| LOCAL TEST ROUTE
|--------------------------------------------------------------------------
*/
if (app()->isLocal()) {
    Route::get('/test-date', function () {
        $a = Announcement::first();
        return $a ? gettype($a->created_at) . ' → ' . $a->created_at : '❌ No announcements found.';
    });
}
