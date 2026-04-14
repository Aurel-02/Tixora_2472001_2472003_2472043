<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TambahEventController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FaceScanController;
use App\Http\Controllers\CheckInController;

Route::get('/', [DashboardController::class, 'landing']);

Route::get('/landing', [DashboardController::class, 'landing']);

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');

Route::get('/signup', [SignupController::class, 'show'])->name('signup');
Route::post('/signup', [SignupController::class, 'store'])->name('signup.post');
Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
Route::get('/admin/login', [AuthController::class, 'loginPage']);
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/admin/logout', [AuthController::class, 'logout']);

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'role:3']);

Route::get('/my-tickets', [\App\Http\Controllers\MyTicketController::class, 'index'])->middleware(['auth', 'role:3'])->name('my-tickets');
Route::post('/my-tickets/cancel/{id}', [\App\Http\Controllers\MyTicketController::class, 'cancel'])->middleware(['auth', 'role:3'])->name('my-tickets.cancel');

Route::get('/notifications', [\App\Http\Controllers\BuyerNotificationController::class, 'index'])->middleware(['auth', 'role:3'])->name('buyer.notification');

Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->middleware(['auth'])->name('profile.edit');
Route::post('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->middleware(['auth'])->name('profile.update');
Route::get('/profile/change-password', [\App\Http\Controllers\ProfileController::class, 'editPassword'])->middleware(['auth'])->name('profile.password.edit');
Route::post('/profile/change-password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->middleware(['auth'])->name('profile.password.update');
Route::get('/organizerdashboard', [DashboardController::class, 'organizer'])->middleware(['auth', 'role:2'])->name('organizerdashboard');
Route::get('/organizer/dashboard', [DashboardController::class, 'organizer'])->middleware(['auth', 'role:2']);
// Route::get('/organizer/events/create', [TambahEventController::class, 'create'])->middleware(['auth', 'role:2'])->name('organizer.events.create');
Route::get('/organizer/events', [DashboardController::class, 'organizer'])->middleware(['auth', 'role:2']);
// Route::post('/organizer/events', [TambahEventController::class, 'store'])->middleware(['auth', 'role:2'])->name('organizer.events.store');
Route::get('/organizer/event/{id}', [DashboardController::class, 'showEventOrganizer'])->middleware(['auth', 'role:2'])->name('organizer.event.detail');
Route::get('/organizer/statistik', [\App\Http\Controllers\StatistikController::class, 'index'])->middleware(['auth', 'role:2'])->name('organizer.statistik');
Route::post('/organizer/event/{id}/update-description', [\App\Http\Controllers\EditEventController::class, 'updateDescription'])->middleware(['auth', 'role:2'])->name('organizer.event.update-description');
Route::post('/organizer/event/{id}/add-quota', [\App\Http\Controllers\EditEventController::class, 'addQuota'])->middleware(['auth', 'role:2'])->name('organizer.event.add-quota');
Route::get('/organizer/notifications', [\App\Http\Controllers\NotifikasiController::class, 'organizer'])->middleware(['auth', 'role:2'])->name('organizer.notifications');
Route::get('/organizer/revenue', [\App\Http\Controllers\RevenueController::class, 'index'])->middleware(['auth', 'role:2'])->name('organizer.revenue');
Route::get('/organizer/checkin', [CheckInController::class, 'index'])->middleware(['auth', 'role:2'])->name('organizer.checkin');
Route::get('/organizer/checkin/report', [CheckInController::class, 'report'])->middleware(['auth', 'role:2'])->name('organizer.checkin.report');
Route::post('/organizer/checkin/scan-qr', [CheckInController::class, 'scanQr'])->middleware(['auth', 'role:2'])->name('checkin.scan-qr');
Route::post('/organizer/checkin/confirm', [CheckInController::class, 'confirmCheckin'])->middleware(['auth', 'role:2'])->name('checkin.confirm');
Route::post('/organizer/checkin/sync-face', [CheckInController::class, 'syncFace'])->middleware(['auth', 'role:2'])->name('checkin.sync-face');
Route::post('/organizer/request-event/{id}', [DashboardController::class, 'requestEventManagement'])->middleware(['auth', 'role:2'])->name('organizer.event.request');


Route::get('/event/{id}', [DashboardController::class, 'showEvent'])->name('event.detail');
Route::get('/event/{id}/select-seat', [\App\Http\Controllers\SelectSeatController::class, 'index'])->name('event.select-seat');
Route::post('/event/{id}/checkout', [\App\Http\Controllers\SelectSeatController::class, 'checkout'])->name('event.checkout');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::post('/checkout/process-payment', [\App\Http\Controllers\SelectSeatController::class, 'processPayment'])->middleware(['auth', 'role:3'])->name('checkout.process-payment');
Route::get('/checkout/face-scan', [FaceScanController::class, 'index'])->name('face-scan.index');
Route::post('/checkout/face-scan/upload', [FaceScanController::class, 'upload'])->name('face-scan.upload');
Route::get('/checkout/face-scan/status', [FaceScanController::class, 'checkStatus'])->middleware(['auth', 'role:3'])->name('face-scan.status');
Route::get('/admin/events/{id}/check-deletable', [\App\Http\Controllers\Admin\EventController::class, 'checkDeletable'])->name('admin.event.check-deletable');
Route::get('/admin/events', [EventController::class, 'index']);
Route::get('/admin/events/create', [TambahEventController::class, 'create'])->name('admin.events.create');
Route::post('/admin/events', [TambahEventController::class, 'store'])->name('admin.events.store');
Route::get('/admin/events/{id}/edit', [EventController::class, 'edit'])->name('admin.events.edit');
Route::post('/admin/events/{id}/update', [EventController::class, 'update'])->name('admin.events.update');
Route::post('/admin/events/{id}/delete', [EventController::class, 'destroy'])->name('admin.events.destroy');
Route::get('/admin/revenue', [\App\Http\Controllers\RevenueController::class, 'index'])->name('admin.revenue');
Route::get('/admin/revenue/export-pdf', [\App\Http\Controllers\RevenueController::class, 'exportPdf'])->name('admin.revenue.export_pdf');
Route::get('/admin/event/{id}', [DashboardController::class, 'showAdminEventDetail'])->name('admin.event.detail');
Route::post('/admin/event/{id}/reject',
    [EventController::class,'reject'])->name('admin.event.reject');
Route::get('/admin/statistik', [\App\Http\Controllers\StatistikController::class, 'index'])->name('admin.statistik');
Route::get('/admin/notifikasi', [DashboardController::class, 'adminNotifications'])->name('admin.notifications');
Route::post('/admin/approve-permohonan/{id}', [DashboardController::class, 'approveEventManagement'])->name('admin.permohonan.approve');
Route::post('/admin/reject-permohonan/{id}', [DashboardController::class, 'rejectEventManagement'])->name('admin.permohonan.reject');

// ── Admin User Management ──
Route::get('/admin/user-management', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('admin.users.index');
Route::post('/admin/users/{id}/deactivate', [\App\Http\Controllers\Admin\UserManagementController::class, 'deactivate'])->name('admin.users.deactivate');
Route::post('/admin/users/{id}/activate', [\App\Http\Controllers\Admin\UserManagementController::class, 'activate'])->name('admin.users.activate');
Route::delete('/admin/users/{id}', [\App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('admin.users.destroy');
