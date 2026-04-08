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

Route::get('/', function () {
    return view('landing');
});

Route::get('/landing', function () {
    return view('landing');
});

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');

Route::get('/signup', [SignupController::class, 'show'])->name('signup');
Route::post('/signup', [SignupController::class, 'store'])->name('signup.post');
Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard']);
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
Route::get('/organizer/events/create', [TambahEventController::class, 'create'])->middleware(['auth', 'role:2'])->name('organizer.events.create');
Route::get('/organizer/events', [DashboardController::class, 'organizer'])->middleware(['auth', 'role:2']);
Route::post('/organizer/events', [TambahEventController::class, 'store'])->middleware(['auth', 'role:2'])->name('organizer.events.store');
Route::get('/organizer/event/{id}', [DashboardController::class, 'showEventOrganizer'])->middleware(['auth', 'role:2'])->name('organizer.event.detail');
Route::get('/organizer/statistik', [\App\Http\Controllers\StatistikController::class, 'index'])->middleware(['auth', 'role:2'])->name('organizer.statistik');
Route::post('/organizer/event/{id}/update-description', [\App\Http\Controllers\EditEventController::class, 'updateDescription'])->middleware(['auth', 'role:2'])->name('organizer.event.update-description');
Route::post('/organizer/event/{id}/add-quota', [\App\Http\Controllers\EditEventController::class, 'addQuota'])->middleware(['auth', 'role:2'])->name('organizer.event.add-quota');
Route::get('/organizer/notifications', [\App\Http\Controllers\NotifikasiController::class, 'organizer'])->middleware(['auth', 'role:2'])->name('organizer.notifications');
Route::get('/organizer/revenue', [\App\Http\Controllers\RevenueController::class, 'index'])->middleware(['auth', 'role:2'])->name('organizer.revenue');
Route::get('/organizer/checkin', function () {
    return view('checkin');
})->middleware(['auth', 'role:2'])->name('organizer.checkin');


Route::get('/event/{id}', [DashboardController::class, 'showEvent'])->name('event.detail');
Route::get('/event/{id}/select-seat', [\App\Http\Controllers\SelectSeatController::class, 'index'])->name('event.select-seat');
Route::post('/event/{id}/checkout', [\App\Http\Controllers\SelectSeatController::class, 'checkout'])->name('event.checkout');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/face-scan', [FaceScanController::class, 'index'])->name('face-scan.index');
Route::post('/checkout/face-scan/upload', [FaceScanController::class, 'upload'])->name('face-scan.upload');
Route::get('/admin/events', [EventController::class, 'index']);
Route::get('/admin/events/create', [EventController::class, 'create']);
Route::post('/admin/events', [EventController::class, 'store']);
Route::get('/admin/events/{id}/edit', [EventController::class, 'edit']);
Route::post('/admin/events/{id}/update', [EventController::class, 'update']);
Route::get('/admin/events/{id}/delete', [EventController::class, 'destroy']);
Route::get('/admin/revenue', [\App\Http\Controllers\RevenueController::class, 'index'])->name('admin.revenue');
Route::get('/admin/event/{id}', [DashboardController::class, 'showAdminEventDetail'])->name('admin.event.detail');
Route::post('/admin/event/{id}/approve',
    [EventController::class,'approve'])->name('admin.event.approve');
Route::post('/admin/event/{id}/reject',
    [EventController::class,'reject'])->name('admin.event.reject');
