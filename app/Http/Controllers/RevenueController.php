<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RevenueController extends Controller
{
    public function index()
    {
        $adminRevenue = 5000000;
        $organizerRevenue = 45000000;
        $totalTransactions = 1240;
        
        $recentTransactions = [
            ['id' => 'TX-1001', 'event' => 'Music Festival 2024', 'amount' => 150000, 'date' => '2024-04-07'],
            ['id' => 'TX-1002', 'event' => 'Tech Conference', 'amount' => 200000, 'date' => '2024-04-06'],
            ['id' => 'TX-1003', 'event' => 'Art Exhibition', 'amount' => 75000, 'date' => '2024-04-06'],
        ];

        return view('revenue', compact('adminRevenue', 'organizerRevenue', 'totalTransactions', 'recentTransactions'));
    }
}
