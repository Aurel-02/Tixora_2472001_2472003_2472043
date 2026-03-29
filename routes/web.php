<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
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
Route::get('/organizerdashboard', [DashboardController::class, 'organizer'])->middleware(['auth', 'role:2']);
Route::get('/organizer/dashboard', [DashboardController::class, 'organizer'])->middleware(['auth', 'role:2']);

Route::get('/event/{id}', [DashboardController::class, 'showEvent'])->name('event.detail');
Route::get('/admin/events', [EventController::class, 'index']);
Route::get('/admin/events/create', [EventController::class, 'create']);
Route::post('/admin/events', [EventController::class, 'store']);
Route::get('/admin/events/{id}/edit', [EventController::class, 'edit']);
Route::post('/admin/events/{id}/update', [EventController::class, 'update']);
Route::get('/admin/events/{id}/delete', [EventController::class, 'destroy']);
