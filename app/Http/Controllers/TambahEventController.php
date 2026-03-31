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
            'tickets.*.jenis_tiket' => 'nullable|in:REGULER,VIP,VVIP',
            'tickets.*.harga' => 'nullable|numeric|min:0',
            'tickets.*.kuota' => 'nullable|integer|min:0',
        ]);

        $adminId = session('login_admin.id');

        $ticketData = $request->input('tickets', []);
        $formattedTickets = [];
        foreach ($ticketData as $ticket) {
            if (!empty($ticket['jenis_tiket']) && isset($ticket['harga']) && isset($ticket['kuota'])) {
                $formattedTickets[] = [
                    'jenis' => $ticket['jenis_tiket'],
                    'harga' => (int) $ticket['harga'],
                    'kuota' => (int) $ticket['kuota'],
                ];
            }
        }
        $ticketsJson = json_encode($formattedTickets);

        try {
            DB::statement("CALL sp_tambah_event_lengkap(?, ?, ?, ?, ?, ?, ?, ?)", [
                $validated['id_kategori'],
                $validated['nama_event'],
                $validated['waktu_pelaksanaan'],
                $validated['deskripsi'],
                $validated['lokasi_event'],
                $validated['tanggal_pelaksanaan'],
                $adminId,
                $ticketsJson
            ]);

            return redirect()->route('organizerdashboard')->with('success', 'Event berhasil disimpan');
        } catch (\Illuminate\Database\QueryException $e) {
            $errorMessage = $e->getMessage();
            if (str_contains($errorMessage, 'Event sudah ada pada tanggal, waktu, dan lokasi yang sama')) {
                return redirect()->back()
                    ->withErrors(['error' => 'Gagal: Event sudah ada pada tanggal, waktu, dan lokasi yang sama!'])
                    ->withInput();
            }

            return redirect()->back()
                ->withErrors(['error' => 'Gagal menambahkan event: Database Error.'])
                ->withInput();
        }
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
