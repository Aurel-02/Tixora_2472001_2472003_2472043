<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function landing()
    {
        $events = Event::orderBy('id_event', 'desc')->get();
        return view('landing', compact('events'));
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

    public function index()
    {
        $role = $this->currentRole();
        if (!$role) {
            return redirect('/login');
        }

        $frontendData = $this->prepareFrontendData();
        return view('dashboard', compact('frontendData'));
    }

    public function adminDashboard()
    {
        $role = $this->currentRole();

        if ($role !== '1' && $role !== 'admin') {
            return redirect('/login');
        }

        // Coment out because column 'status' not found in database
        // $pending = Event::where('status', 'pending')->get();
        // $approved = Event::where('status', 'approved')->get();
        // $rejected = Event::where('status', 'rejected')->get();

        $pending = collect();
        $approved = Event::all(); // Show all for now
        $rejected = collect();

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

        foreach ($approved as $event) {
            $catKey = $kategoriMap[$event->id_kategori] ?? 'indonesia';
            $eventsByCategory[$catKey][] = $event;
        }

        return view('admindashboard', compact(
            'pending',
            'approved',
            'rejected',
            'eventsByCategory'
        ));
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

        // Available events for management (no organizer assigned)
        $availableEvents = Event::whereNull('id_user')->get();
        $myRequests = DB::table('permohonan_events')
            ->where('id_user', $adminId)
            ->pluck('status', 'id_event')
            ->toArray();

        return view('organizerdashboard', compact('eventsByCategory', 'availableEvents', 'myRequests'));
    }

    public function requestEventManagement($id)
    {
        $adminId = session('login_admin.id');
        if (!$adminId) return back();

        $exists = DB::table('permohonan_events')
            ->where('id_event', $id)
            ->where('id_user', $adminId)
            ->where('status', 'pending')
            ->exists();

        if (!$exists) {
            DB::table('permohonan_events')->insert([
                'id_event' => $id,
                'id_user' => $adminId,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Notify Admin (ID 1 as fallback or handle differently)
            DB::table('notifikasi')->insert([
                'id_user' => 1,
                'pesan' => "Organizer mengajukan pendaftaran untuk event #" . $id,
                'is_read' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return back()->with('success', 'Permohonan pendaftaran telah dikirim.');
    }

    public function adminNotifications()
    {
        $role = $this->currentRole();
        if ($role !== 'admin' && $role !== '1') return redirect('/login');

        $requests = DB::table('permohonan_events')
            ->join('event', 'permohonan_events.id_event', '=', 'event.id_event')
            ->join('user', 'permohonan_events.id_user', '=', 'user.id_user')
            ->where('permohonan_events.status', 'pending')
            ->select('permohonan_events.*', 'event.nama_event', 'user.nama_lengkap as organizer_name')
            ->orderBy('permohonan_events.created_at', 'desc')
            ->get();

        return view('admin-notifikasi', compact('requests'));
    }

    public function approveEventManagement($id)
    {
        $request = DB::table('permohonan_events')->where('id_permohonan', $id)->first();
        if (!$request) return back();

        DB::transaction(function() use ($request, $id) {
            // Assign organizer to event
            DB::table('event')->where('id_event', $request->id_event)->update([
                'id_user' => $request->id_user
            ]);

            // Update permohonan status
            DB::table('permohonan_events')->where('id_permohonan', $id)->update([
                'status' => 'approved',
                'updated_at' => now()
            ]);

            // Notify Organizer
            DB::table('notifikasi')->insert([
                'id_user' => $request->id_user,
                'pesan' => "Pendaftaran event Anda telah DISETUJUI oleh Admin.",
                'is_read' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        });

        return back()->with('success', 'Pendaftaran disetujui.');
    }

    public function rejectEventManagement($id)
    {
        $request = DB::table('permohonan_events')->where('id_permohonan', $id)->first();
        if (!$request) return back();

        DB::table('permohonan_events')->where('id_permohonan', $id)->update([
            'status' => 'rejected',
            'updated_at' => now()
        ]);

        // Notify Organizer
        DB::table('notifikasi')->insert([
            'id_user' => $request->id_user,
            'pesan' => "Pendaftaran event Anda telah DITOLAK oleh Admin.",
            'is_read' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Pendaftaran ditolak.');
    }

    public function showEvent($id)
    {
        $role = $this->currentRole();
        if (!$role) {
            return redirect('/login');
        }

        $event = Event::findOrFail($id);

        $totalTickets = (int)DB::table('tiket')->where('id_event', $id)->sum('kuota');

        $tikets = DB::table('tiket')->where('id_event', $id)->get();
        $ticketsSold = 0;
        foreach ($tikets as $t) {
            $ticketsSold += (int)collect(DB::select("SELECT hitung_tiket_terjual(?) as sold", [$t->id_tiket]))->first()->sold;
        }
        $ticketsAvailable = 0;
        foreach ($tikets as $t) {
            $soldForType = (int)collect(DB::select("SELECT hitung_tiket_terjual(?) as sold", [$t->id_tiket]))->first()->sold;
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

        $tikets = DB::table('tiket')->where('id_event', $id)->get();

        $ticketStats = [];
        $totalTickets = 0;
        $ticketsSold = 0;

        foreach ($tikets as $t) {
            $sold = (int)collect(DB::select("SELECT hitung_tiket_terjual(?) as sold", [$t->id_tiket]))->first()->sold;
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

        return view('organizer-detailevent', compact('event', 'ticketStats', 'totalTickets', 'ticketsSold', 'ticketsAvailable'));
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

        // $allEvents = Event::where('status','approved')->get();
        $allEvents = Event::all(); // Show all for now

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

    public function showAdminEventDetail($id)
    {
        $role = $this->currentRole();
        if ($role !== '1' && $role !== 'admin') {
            return redirect('/login');
        }

        $event = Event::findOrFail($id);
        $tikets = DB::table('tiket')->where('id_event', $id)->get();

        $ticketStats = [];
        $totalTickets = 0;
        $ticketsSold = 0;

        foreach ($tikets as $t) {
            $sold = (int)collect(DB::select("SELECT hitung_tiket_terjual(?) as sold", [$t->id_tiket]))->first()->sold;
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

        return view('admin-detailevent', compact('event', 'ticketStats', 'totalTickets', 'ticketsSold', 'ticketsAvailable'));
    }
}

