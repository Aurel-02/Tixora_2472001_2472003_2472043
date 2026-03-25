<?php

namespace App\Http\Controllers;

use App\Models\Event;

class AdminDashboardController extends Controller
{
    public function index()
    {
        if (!session()->has('admin')) {
            return redirect('/admin/login');
        }

        $totalEvent = 0;

        return view('admin.dashboard', compact('totalEvent'));
    }
}
