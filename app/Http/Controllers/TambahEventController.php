<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TambahEventController extends Controller
{
    public function create()
    {
        $role = $this->currentRole();

        if ($role === 'buyer' || $role === '3') {
            return redirect('/dashboard');
        }

        if ($role !== 'organizer' && $role !== '2') {
            return redirect('/login');
        }

        return view('tambahevent');
    }

    public function store(Request $request)
    {
        $role = $this->currentRole();
        if ($role !== 'organizer' && $role !== '2') {
            return redirect('/login');
        }

        $validated = $request->validate([
            'nama_event' => 'required|string|max:255',
            'id_kategori' => 'required|in:1,2,3',
            'waktu_pelaksanaan' => 'required|date_format:H:i',
            'tanggal_pelaksanaan' => 'required|date',
            'lokasi_event' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tickets' => 'nullable|array',
            'tickets.*.jenis_tiket' => 'nullable|in:biasa,VIP,VVIP',
            'tickets.*.harga' => 'nullable|numeric|min:0',
            'tickets.*.kuota' => 'nullable|integer|min:0',
        ]);

        $adminId = session('login_admin.id');
        $event = Event::create([
            'id_user' => $adminId,
            'id_kategori' => $validated['id_kategori'],
            'nama_event' => $validated['nama_event'],
            'waktu_pelaksanaan' => $validated['waktu_pelaksanaan'],
            'deskripsi' => $validated['deskripsi'],
            'lokasi_event' => $validated['lokasi_event'],
            'tanggal_pelaksanaan' => $validated['tanggal_pelaksanaan'],
        ]);

        $ticketData = $request->input('tickets', []);
        foreach ($ticketData as $ticket) {
            if (!empty($ticket['jenis_tiket']) && isset($ticket['harga']) && isset($ticket['kuota'])) {
                if ($ticket['harga'] !== '' && $ticket['kuota'] !== '') {
                    DB::table('tiket')->insert([
                        'id_event' => $event->id_event,
                        'jenis_tiket' => $ticket['jenis_tiket'],
                        'harga' => $ticket['harga'],
                        'kuota' => $ticket['kuota'],
                    ]);
                }
            }
        }

        return redirect()->route('organizerdashboard')->with('success', 'Event berhasil disimpan');
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
