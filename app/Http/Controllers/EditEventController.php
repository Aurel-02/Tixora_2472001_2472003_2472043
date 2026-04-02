<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EditEventController extends Controller
{
    /**
     * Update the event description.
     */
    public function updateDescription(Request $request, $id)
    {
        $request->validate([
            'deskripsi' => 'required|string',
        ]);

        $event = Event::findOrFail($id);
        
        $event->deskripsi = $request->deskripsi;
        $event->save();

        return redirect()->back()->with('success', 'Deskripsi event berhasil diperbarui.');
    }

    /**
     * Add more quota to a specific ticket category.
     */
    public function addQuota(Request $request, $id)
    {
        $request->validate([
            'id_tiket' => 'required|exists:tiket,id_tiket',
            'jumlah_tambah' => 'required|integer|min:1',
        ]);

        DB::table('tiket')
            ->where('id_tiket', $request->id_tiket)
            ->where('id_event', $id)
            ->increment('kuota', $request->jumlah_tambah);

        return redirect()->back()->with('success', 'Kuota tiket berhasil ditambahkan.');
    }
}
