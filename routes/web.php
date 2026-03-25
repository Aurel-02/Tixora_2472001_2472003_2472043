<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\EventController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/landing', function () {
    return view('landing');
});

Route::get('/login-page', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);
Route::get('/admin/login', [AdminAuthController::class, 'loginPage']);
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/admin/logout', [AdminAuthController::class, 'logout']);

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/organizerdashboard', [DashboardController::class, 'organizer']);
Route::get('/organizer/dashboard', [DashboardController::class, 'organizer']);

Route::get('/event/{id}', [DashboardController::class, 'showEvent'])->name('event.detail');
Route::get('/admin/events', [EventController::class, 'index']);
Route::get('/admin/events/create', [EventController::class, 'create']);
Route::post('/admin/events', [EventController::class, 'store']);
Route::get('/admin/events/{id}/edit', [EventController::class, 'edit']);
Route::post('/admin/events/{id}/update', [EventController::class, 'update']);
Route::get('/admin/events/{id}/delete', [EventController::class, 'destroy']);
