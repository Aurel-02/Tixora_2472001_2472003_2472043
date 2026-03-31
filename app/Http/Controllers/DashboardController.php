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
        
        return view('event-detail', compact('event'));
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
                        'location' => $event->lokasi_event ?? 'Lokasi belum diset'
                    ];
                } else {
                    $frontendData[$slug]['artists'][] = [
                        'id' => $event->id_event,
                        'name' => $event->nama_event,
                        'desc' => $tanggal ? $tanggal->format('d M Y') : 'TBD'
                    ];
                }
            }
        }

        return $frontendData;
    }
}
