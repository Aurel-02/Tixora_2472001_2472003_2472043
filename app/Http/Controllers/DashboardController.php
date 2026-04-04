<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
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

    public function index()
    {
        $role = $this->currentRole();
        if (!$role) {
            return redirect('/login');
        }

        $frontendData = $this->prepareFrontendData();
        return view('dashboard', compact('frontendData'));
    }

    public function organizer()
    {
        $role = $this->currentRole();

        if ($role === 'buyer' || $role === '3') {
            return redirect('/dashboard');
        }

        if ($role !== 'organizer' && $role !== '2') {
            return redirect('/login');
        }

        $adminId = session('login_admin.id');
        if (!$adminId) {
            return redirect('/dashboard');
        }

        $events = Event::where('id_user', $adminId)->get();

        $kategoriMap = [
            1 => 'indonesia',
            2 => 'western',
            3 => 'kpop',
        ];

        $eventsByCategory = [
            'indonesia' => [],
            'western' => [],
            'kpop' => [],
        ];

        foreach ($events as $event) {
            $catKey = $kategoriMap[$event->id_kategori] ?? 'indonesia';
            $eventsByCategory[$catKey][] = $event;
        }

        return view('organizerdashboard', compact('eventsByCategory'));
    }


    public function showEvent($id)
    {
        $role = $this->currentRole();
        if (!$role) {
            return redirect('/login');
        }

        $event = Event::findOrFail($id);
        
        $totalTickets = (int)\Illuminate\Support\Facades\DB::table('tiket')->where('id_event', $id)->sum('kuota');
        
        $ticketsSold = (int)\Illuminate\Support\Facades\DB::table('detail_transaksi')
            ->join('tiket', 'detail_transaksi.id_tiket', '=', 'tiket.id_tiket')
            ->where('tiket.id_event', $id)
            ->sum('detail_transaksi.jumlah_beli');
            
        $tikets = \Illuminate\Support\Facades\DB::table('tiket')->where('id_event', $id)->get();
        $ticketsAvailable = 0;
        foreach ($tikets as $t) {
            $soldForType = \Illuminate\Support\Facades\DB::table('detail_transaksi')
                ->where('id_tiket', $t->id_tiket)
                ->sum('jumlah_beli') ?? 0;
            $sisa = (int)$t->kuota - (int)$soldForType;
            if ($sisa > 0) {
                $ticketsAvailable += $sisa;
            }
        }

        return view('event-detail', compact('event', 'totalTickets', 'ticketsSold', 'ticketsAvailable'));
    }

    public function showEventOrganizer($id)
    {
        $role = $this->currentRole();
        if ($role !== 'organizer' && $role !== '2') {
            return redirect('/login');
        }

        $event = Event::findOrFail($id);
        
        $tikets = \Illuminate\Support\Facades\DB::table('tiket')
            ->where('id_event', $id)
            ->get();
            
        $ticketStats = [];
        $totalTickets = 0;
        $ticketsSold = 0;

        foreach ($tikets as $t) {
            $sold = \Illuminate\Support\Facades\DB::table('detail_transaksi')
                ->where('id_tiket', $t->id_tiket)
                ->sum('jumlah_beli') ?? 0;
                
            $available = (int)$t->kuota - (int)$sold;
            if ($available < 0) $available = 0;

            $ticketStats[] = (object)[
                'id_tiket' => $t->id_tiket,
                'jenis_tiket' => $t->jenis_tiket,
                'harga' => $t->harga,
                'kuota' => $t->kuota,
                'terjual' => $sold,
                'sisa' => $available,
            ];

        $totalTickets += (int)$t->kuota;
        $ticketsSold += $sold;
    }

        $ticketsAvailable = $totalTickets - $ticketsSold;
        if ($ticketsAvailable < 0) $ticketsAvailable = 0;

        return view('eventdetailorganizer', compact('event', 'ticketStats', 'totalTickets', 'ticketsSold', 'ticketsAvailable'));
    }

    public function bookEvent($id)
    {
        $role = $this->currentRole();
        if (!$role) {
            return redirect('/login');
        }

        $event = Event::findOrFail($id);
        
        return view('select-seat', compact('event'));
    }

    private function prepareFrontendData(): array
    {
        $now = Carbon::now();
        $threeMonthsLater = $now->copy()->addMonths(3);

        $kategoriMap = [
            1 => 'indonesia',
            2 => 'western',
            3 => 'kpop'
        ];

        $frontendData = [
            'indonesia' => ['artists' => [], 'events' => []],
            'western' => ['artists' => [], 'events' => []],
            'kpop' => ['artists' => [], 'events' => []]
        ];

        $allEvents = Event::all();

        foreach ($allEvents as $event) {
            $catId = $event->id_kategori;

            if (array_key_exists($catId, $kategoriMap)) {
                $slug = $kategoriMap[$catId];

                $tanggal = $event->tanggal_pelaksanaan ? Carbon::parse($event->tanggal_pelaksanaan) : null;
                $isUpcoming = $tanggal && $tanggal->isAfter($threeMonthsLater);

                if ($isUpcoming) {
                    $frontendData[$slug]['events'][] = [
                        'id' => $event->id_event,
                        'month' => strtoupper($tanggal->format('M')),
                        'day' => $tanggal->format('d'),
                        'name' => $event->nama_event,
                        'location' => $event->lokasi_event ?? 'Lokasi belum diset',
                        'poster' => $event->poster
                    ];
                } else {
                    $frontendData[$slug]['artists'][] = [
                        'id' => $event->id_event,
                        'name' => $event->nama_event,
                        'desc' => $tanggal ? $tanggal->format('d M Y') : 'TBD',
                        'poster' => $event->poster
                    ];
                }
            }
        }

        return $frontendData;
    }
}
