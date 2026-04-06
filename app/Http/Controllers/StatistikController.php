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
        $adminId = session('login_admin.id');
        if (!$adminId) {
            return redirect('/login');
        }

        $events = Event::where('id_user', $adminId)->get();

        $overallTotal = 0;
        $overallSold = 0;
        $eventChartData = [];
        
        foreach ($events as $event) {
            $tikets = DB::table('tiket')->where('id_event', $event->id_event)->get();
            $eventTotal = 0;
            $eventSold = 0;
            $categories = [];

            foreach ($tikets as $t) {
                $sold = DB::table('detail_transaksi')
                    ->where('id_tiket', $t->id_tiket)
                    ->sum('jumlah_beli') ?? 0;
                
                $categories[] = [
                    'label' => $t->jenis_tiket,
                    'sold' => (int)$sold
                ];

                $eventTotal += (int)$t->kuota;
                $eventSold += (int)$sold;
            }
            
            $overallTotal += $eventTotal;
            $overallSold += $eventSold;

            $eventChartData[] = [
                'name' => $event->nama_event,
                'sold' => $eventSold,
                'quota' => $eventTotal,
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
            $selectedEvent = Event::where('id_user', $adminId)->where('id_event', $request->id)->firstOrFail();
            
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
        }

        return view('statistik-penjualan-organizer', compact(
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
            'globalCategories'
        ));
    }
}
