<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/landing', function () {
    return view('landing');
});

Route::get('/login-page', function () {
    return view('login-page');
});

Route::get('/dashboard', [DashboardController::class, 'index']);
