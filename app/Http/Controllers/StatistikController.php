<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    public function index(Request $request)
    {
        $role = $this->currentRole();
        $adminId = session('login_admin.id');

        if (!$role) {
            return redirect('/login');
        }

        // Scope Events based on role
        if ($role === 'admin' || $role === '1') {
            $events = Event::orderBy('tanggal_pelaksanaan', 'desc')->get();
        } else {
            // Organizer scope
            if (!$adminId) return redirect('/login');
            $events = Event::where('id_user', $adminId)->get();
        }

        $overallTotal = 0;
        $overallSold = 0;
        $eventChartData = [];
        
        foreach ($events as $event) {
            $tikets = DB::table('tiket')->where('id_event', $event->id_event)->get();
            $eventTotal = 0;
            $eventSold = 0;
            $eventSold7Days = 0;
            $eventRevenue = 0;
            $categories = [];

            foreach ($tikets as $t) {
                // Total Sold (Historical)
                $sold = DB::table('detail_transaksi')
                    ->where('id_tiket', $t->id_tiket)
                    ->sum('jumlah_beli') ?? 0;
                
                // Sold in last 7 Days
                $sold7Days = DB::table('detail_transaksi')
                    ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
                    ->where('detail_transaksi.id_tiket', $t->id_tiket)
                    ->whereIn('transaksi.status_transaksi', ['lunas', 'Selesai'])
                    ->where('transaksi.tanggal_transaksi', '>=', now()->subDays(7))
                    ->sum('detail_transaksi.jumlah_beli') ?? 0;

                $categories[] = [
                    'label' => $t->jenis_tiket,
                    'sold' => (int)$sold
                ];

                $eventTotal += (int)$t->kuota;
                $eventSold += (int)$sold;
                $eventSold7Days += (int)$sold7Days;
                $eventRevenue += (int)$sold * (int)$t->harga;
            }
            
            $overallTotal += $eventTotal;
            $overallSold += $eventSold;

            $eventChartData[] = [
                'name' => $event->nama_event,
                'sold' => $eventSold,
                'sold_7days' => $eventSold7Days,
                'quota' => $eventTotal,
                'revenue' => $eventRevenue,
                'categories' => $categories
            ];
        }
        $overallAvailable = $overallTotal - $overallSold;
        
        // Aggregate Global Category Data
        $globalCategoriesRaw = [];
        foreach ($eventChartData as $event) {
            foreach ($event['categories'] as $cat) {
                $label = trim($cat['label']);
                if (!isset($globalCategoriesRaw[$label])) {
                    $globalCategoriesRaw[$label] = 0;
                }
                $globalCategoriesRaw[$label] += $cat['sold'];
            }
        }
        
        $globalCategories = [];
        foreach ($globalCategoriesRaw as $label => $sold) {
            $globalCategories[] = [
                'label' => $label,
                'sold' => $sold
            ];
        }

        $selectedEvent = null;
        $ticketStats = [];
        $selectedTotal = 0;
        $selectedSold = 0;
        $selectedAvailable = 0;

        if ($request->has('id')) {
            $query = Event::where('id_event', $request->id);
            
            // If organizer, further restrict to their own events
            if ($role !== 'admin' && $role !== '1') {
                $query->where('id_user', $adminId);
            }
            
            $selectedEvent = $query->firstOrFail();
            
            $tikets = DB::table('tiket')->where('id_event', $selectedEvent->id_event)->get();
            foreach ($tikets as $t) {
                $sold = DB::table('detail_transaksi')
                    ->where('id_tiket', $t->id_tiket)
                    ->sum('jumlah_beli') ?? 0;
                    
                $ticketStats[] = (object)[
                    'id_tiket' => $t->id_tiket,
                    'jenis_tiket' => $t->jenis_tiket,
                    'harga' => $t->harga,
                    'kuota' => $t->kuota,
                    'terjual' => $sold,
                    'sisa' => (int)$t->kuota - (int)$sold,
                ];

                $selectedTotal += (int)$t->kuota;
                $selectedSold += (int)$sold;
            }
            $selectedAvailable = $selectedTotal - $selectedSold;

            // Detail buyer list for export
            $buyerList = DB::table('transaksi')
                ->join('user', 'transaksi.id_user', '=', 'user.id_user')
                ->join('detail_transaksi', 'transaksi.id_transaksi', '=', 'detail_transaksi.id_transaksi')
                ->join('tiket', 'detail_transaksi.id_tiket', '=', 'tiket.id_tiket')
                ->where('tiket.id_event', $selectedEvent->id_event)
                ->whereIn('transaksi.status_transaksi', ['lunas', 'Selesai'])
                ->select(
                    'user.nama_user as Nama Buyer',
                    'user.email as Email',
                    'user.no_telp as No Telp',
                    'tiket.jenis_tiket as Kategori Tiket',
                    'transaksi.id_transaksi as ID Transaksi',
                    'detail_transaksi.jumlah_beli as Jumlah'
                )
                ->get();
        } else {
            $buyerList = collect([]);
        }

        return view('statistikpenjualan', compact(
            'events', 
            'overallTotal', 
            'overallSold', 
            'overallAvailable', 
            'selectedEvent', 
            'ticketStats',
            'selectedTotal',
            'selectedSold',
            'selectedAvailable',
            'eventChartData',
            'globalCategories',
            'role',
            'buyerList'
        ));
    }

    private function currentRole()
    {
        if (Auth::check()) {
            return strtolower(trim(Auth::user()->role));
        }

        $adminSession = session('login_admin');
        if (is_array($adminSession) && isset($adminSession['role'])) {
            return strtolower(trim($adminSession['role']));
        }

        return null;
    }
}
