<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventController extends Controller
{
    public function reject($id)
    {
        $event = \App\Models\Event::findOrFail($id);
        // $event->status = 'rejected';
        // $event->save();

        return back()->with('success', 'Event Rejected (Simulation - Column Status missing)');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin-editevent', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_event'          => 'required|string|max:255',
            'id_kategori'         => 'required|in:1,2,3',
            'waktu_pelaksanaan'   => 'required|date_format:H:i',
            'tanggal_pelaksanaan' => 'required|date',
            'lokasi_event'        => 'required|string|max:255',
            'deskripsi'           => 'required|string',
            'poster'              => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $event = Event::findOrFail($id);

        if ($request->hasFile('poster')) {
            $file     = $request->file('poster');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('posters'), $fileName);
            $validated['poster'] = 'posters/' . $fileName;
        }

        $event->update($validated);

        return redirect()->route('admin.event.detail', $id)->with('success', 'Event updated successfully');
    }
    private function getDeletableStatus($event)
    {
        $now             = Carbon::now();
        $threeMonthsLater = $now->copy()->addMonths(3);
        $tanggal = $event->tanggal_pelaksanaan
            ? Carbon::parse($event->tanggal_pelaksanaan)
            : null;

        $isPast     = $tanggal && $tanggal->isPast();
        $isUpcoming = $tanggal && $tanggal->isAfter($threeMonthsLater);
        $isActive   = $tanggal && !$isPast && !$isUpcoming;

        // Hitung tiket terjual (status berhasil)
        $soldCount = DB::table('detail_transaksi')
            ->join('tiket', 'detail_transaksi.id_tiket', '=', 'tiket.id_tiket')
            ->where('tiket.id_event', $event->id_event)
            ->where('detail_transaksi.status_item', 'berhasil')
            ->count();

        if ($isActive && $soldCount > 0) {
            return [
                'can_delete' => false,
                'reason'     => "Event ini sedang dalam periode penjualan aktif dan sudah memiliki tiket yang terjual. Event hanya bisa dihapus setelah selesai atau jika belum ada penjualan.",
            ];
        }

        return ['can_delete' => true, 'reason' => ''];
    }

    public function checkDeletable($id)
    {
        $event  = Event::findOrFail($id);
        $status = $this->getDeletableStatus($event);
        return response()->json(array_merge($status, ['event_name' => $event->nama_event]));
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        $status = $this->getDeletableStatus($event);
        if (!$status['can_delete']) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => $status['reason']], 403);
            }
            return back()->with('error', $status['reason']);
        }

        $eventName = $event->nama_event;

        // Clean up any remaining records in tables that might lack cascade constraints
        DB::table('permohonan_events')->where('id_event', $id)->delete();

        // Perform hard delete to permanently remove from the database
        $event->forceDelete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'event_name' => $eventName]);
        }

        return redirect('/admin/dashboard')->with('success', "Event \"{$eventName}\" berhasil dihapus.");
    }
}
