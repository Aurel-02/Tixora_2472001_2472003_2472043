<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Event;

class RevenueController extends Controller
{
    public function index()
    {
        $rawRole = auth()->check() ? auth()->user()->role : (session()->has('login_admin') ? session('login_admin.role') : '3');
        $role = strtolower(trim($rawRole));

        $totalUangMasuk = 0;
        $totalJatahAdmin = 0;
        $totalJatahOrganizer = 0;
        $totalTransactions = 0;
        $eventEarnings = [];

        if ($role == '1' || $role == 'admin') {
            // Admin View
            $events = Event::all();
            
            foreach ($events as $event) {
                // Admin logic calculation
                $rawSales = DB::table('detail_transaksi')
                    ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
                    ->join('tiket', 'detail_transaksi.id_tiket', '=', 'tiket.id_tiket')
                    ->where('tiket.id_event', $event->id_event)
                    ->where(function($q) {
                        $q->where('detail_transaksi.status_item', 'berhasil')
                          ->orWhereIn('transaksi.status_transaksi', ['lunas', 'Selesai']);
                    })
                    ->select(DB::raw('SUM(detail_transaksi.subtotal) as total, SUM(detail_transaksi.jumlah_beli) as tickets'))
                    ->first();
                
                $eventTotal = (float)($rawSales->total ?? 0);
                $eventTickets = (int)($rawSales->tickets ?? 0);
                $eventRev = $eventTotal * 0.90; // Calculate Admin's 90% immediately

                if ($eventTotal > 0 || $eventTickets > 0) {
                    $eventEarnings[] = [
                        'id' => $event->id_event,
                        'name' => $event->nama_event,
                        'total_sales' => $eventTotal,
                        'revenue' => $eventRev, // This is 90%
                        'tickets' => $eventTickets
                    ];
                    
                    $totalUangMasuk += $eventTotal;
                    $totalJatahAdmin += $eventRev;
                    $totalTransactions += $eventTickets;
                }
            }
            
            $totalJatahOrganizer = $totalUangMasuk - $totalJatahAdmin;

        } elseif ($role == '2' || $role == 'organizer') {
            // Organizer View: 10% Revenue per Event
            $organizerId = session('login_admin.id') ?? auth()->id();
            $events = Event::where('id_user', $organizerId)->get();

            // Total Jatah Organizer
            $totalJatahOrganizer = 0; // Initialize directly

            foreach ($events as $event) {
                // Hitung pendapatan organizer
                $rawSales = DB::table('detail_transaksi')
                    ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
                    ->join('tiket', 'detail_transaksi.id_tiket', '=', 'tiket.id_tiket')
                    ->where('tiket.id_event', $event->id_event)
                    ->where(function($q) {
                        $q->where('detail_transaksi.status_item', 'berhasil')
                          ->orWhereIn('transaksi.status_transaksi', ['lunas', 'Selesai']);
                    })
                    ->select(DB::raw('SUM(detail_transaksi.subtotal) as total, SUM(detail_transaksi.jumlah_beli) as tickets'))
                    ->first();
                
                $eventTotal = (float)($rawSales->total ?? 0);
                $eventTickets = (int)($rawSales->tickets ?? 0);
                $eventRev = $eventTotal * 0.10; // 10% for Organizer

                if ($eventTotal > 0 || $eventTickets > 0) {
                    $eventEarnings[] = [
                        'id' => $event->id_event,
                        'name' => $event->nama_event,
                        'total_sales' => $eventTotal,
                        'revenue' => $eventRev,
                        'tickets' => $eventTickets
                    ];
                    $totalUangMasuk += $eventTotal;
                    $totalJatahOrganizer += $eventRev; // Accumulate organizer share securely
                    $totalTransactions += $eventTickets;
                }
            }
            
            $totalJatahAdmin = $totalUangMasuk - $totalJatahOrganizer;
        }

        $recentTransactions = $eventEarnings; 

        return view('revenue', compact(
            'role',
            'totalUangMasuk',
            'totalJatahAdmin',
            'totalJatahOrganizer',
            'totalTransactions',
            'eventEarnings'
        ));
    }

    public function exportPdf()
    {
        $rawRole = auth()->check() ? auth()->user()->role : (session()->has('login_admin') ? session('login_admin.role') : '3');
        $role = strtolower(trim($rawRole));

        if ($role != '1' && $role != 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $categories = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('tiket', 'detail_transaksi.id_tiket', '=', 'tiket.id_tiket')
            ->join('event', 'tiket.id_event', '=', 'event.id_event')
            ->where(function($q) {
                $q->where('detail_transaksi.status_item', 'berhasil')
                  ->orWhereIn('transaksi.status_transaksi', ['lunas', 'Selesai']);
            })
            ->select(
                'event.nama_event',
                'tiket.jenis_tiket',
                'tiket.harga',
                DB::raw('SUM(detail_transaksi.jumlah_beli) as total_sold'),
                DB::raw('SUM(detail_transaksi.subtotal) as gross_sales')
            )
            ->groupBy('event.id_event', 'event.nama_event', 'tiket.id_tiket', 'tiket.jenis_tiket', 'tiket.harga')
            ->orderBy('event.nama_event', 'asc')
            ->get();

        $reportData = [];
        $totalGrossAll = 0;
        $totalAdminAll = 0;
        $totalOrganizerAll = 0;

        foreach ($categories as $cat) {
            $gross = (float)$cat->gross_sales;
            $adminShare = $gross * 0.90;
            $orgShare = $gross * 0.10;

            if ($gross > 0 || (int)$cat->total_sold > 0) {
                $reportData[$cat->nama_event][] = [
                    'jenis_tiket' => $cat->jenis_tiket,
                    'harga' => (float)$cat->harga,
                    'total_sold' => (int)$cat->total_sold,
                    'gross_sales' => $gross,
                    'admin_share' => $adminShare,
                    'organizer_share' => $orgShare
                ];

                $totalGrossAll += $gross;
                $totalAdminAll += $adminShare;
                $totalOrganizerAll += $orgShare;
            }
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.revenue-report', compact(
            'reportData', 
            'totalGrossAll', 
            'totalAdminAll', 
            'totalOrganizerAll'
        ));

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Revenue_Report_' . date('Y-m-d_H-i') . '.pdf');
    }
}
