<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TambahEventController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\EventController;

Route::get('/', function () {
    return view('landing');
});

Route::get('/landing', function () {
    return view('landing');
});

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'role:1']);
Route::get('/admin/login', [AuthController::class, 'loginPage']);
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/admin/logout', [AuthController::class, 'logout']);

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'role:3']);

// Buyer Profile Routes
Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->middleware(['auth', 'role:3'])->name('profile.edit');
Route::post('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->middleware(['auth', 'role:3'])->name('profile.update');
Route::get('/profile/change-password', [\App\Http\Controllers\ProfileController::class, 'editPassword'])->middleware(['auth', 'role:3'])->name('profile.password.edit');
Route::post('/profile/change-password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->middleware(['auth', 'role:3'])->name('profile.password.update');
Route::get('/organizerdashboard', [DashboardController::class, 'organizer'])->middleware(['auth', 'role:2'])->name('organizerdashboard');
Route::get('/organizer/dashboard', [DashboardController::class, 'organizer'])->middleware(['auth', 'role:2']);
Route::get('/organizer/events/create', [TambahEventController::class, 'create'])->middleware(['auth', 'role:2'])->name('organizer.events.create');
Route::get('/organizer/events', [DashboardController::class, 'organizer'])->middleware(['auth', 'role:2']);
Route::post('/organizer/events', [TambahEventController::class, 'store'])->middleware(['auth', 'role:2'])->name('organizer.events.store');
Route::get('/organizer/event/{id}', [DashboardController::class, 'showEventOrganizer'])->middleware(['auth', 'role:2'])->name('organizer.event.detail');
Route::post('/organizer/event/{id}/update-description', [\App\Http\Controllers\EditEventController::class, 'updateDescription'])->middleware(['auth', 'role:2'])->name('organizer.event.update-description');
Route::post('/organizer/event/{id}/add-quota', [\App\Http\Controllers\EditEventController::class, 'addQuota'])->middleware(['auth', 'role:2'])->name('organizer.event.add-quota');


Route::get('/event/{id}', [DashboardController::class, 'showEvent'])->name('event.detail');
Route::get('/event/{id}/select-seat', [\App\Http\Controllers\SelectSeatController::class, 'index'])->name('event.select-seat');
Route::get('/admin/events', [EventController::class, 'index']);
Route::get('/admin/events/create', [EventController::class, 'create']);
Route::post('/admin/events', [EventController::class, 'store']);
Route::get('/admin/events/{id}/edit', [EventController::class, 'edit']);
Route::post('/admin/events/{id}/update', [EventController::class, 'update']);
Route::get('/admin/events/{id}/delete', [EventController::class, 'destroy']);
