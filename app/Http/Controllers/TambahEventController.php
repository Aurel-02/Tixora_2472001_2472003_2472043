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

        if ($role !== 'admin' && $role !== '1') {
            return redirect('/login');
        }

        return view('admin-tambahevent');
    }

    public function store(Request $request)
    {
        $role = $this->currentRole();
        if ($role !== 'admin' && $role !== '1') {
            return redirect('/login');
        }

        $validated = $request->validate([
            'nama_event' => 'required|string|max:255',
            'id_kategori' => 'required|in:1,2,3',
            'waktu_pelaksanaan' => 'required|date_format:H:i',
            'tanggal_pelaksanaan' => 'required|date',
            'lokasi_event' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tickets' => 'nullable|array',
            'tickets.*.jenis_tiket' => 'nullable|in:REGULER,VIP,VVIP',
            'tickets.*.harga' => 'nullable|numeric|min:0',
            'tickets.*.kuota' => 'nullable|integer|min:0',
        ]);

        $adminId = session('login_admin.id');

        // Handle file upload
        $posterPath = null;
        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('posters'), $fileName);
            $posterPath = 'posters/' . $fileName;
        }

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

        $exists = Event::where('nama_event', $validated['nama_event'])
            ->where('tanggal_pelaksanaan', $validated['tanggal_pelaksanaan'])
            ->where('waktu_pelaksanaan', $validated['waktu_pelaksanaan'])
            ->where('lokasi_event', $validated['lokasi_event'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['error' => 'Gagal: Event sudah ada pada tanggal, waktu, dan lokasi yang sama!'])
                ->withInput();
        }

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

            // Update poster path after creation
            if ($posterPath) {
                $lastEvent = Event::where('id_user', $adminId)
                    ->where('nama_event', $validated['nama_event'])
                    ->orderBy('id_event', 'desc')
                    ->first();
                
                if ($lastEvent) {
                    $lastEvent->update(['poster' => $posterPath]);
                }
            }

            return redirect()->route('admin.dashboard')->with('success', 'Event berhasil disimpan');
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
