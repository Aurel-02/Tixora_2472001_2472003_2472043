<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function reject($id)
    {
        $event = \App\Models\Event::findOrFail($id);
        // $event->status = 'rejected';
        // $event->save();

        return back()->with('success','Event Rejected (Simulation - Column Status missing)');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin-editevent', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_event' => 'required|string|max:255',
            'id_kategori' => 'required|in:1,2,3',
            'waktu_pelaksanaan' => 'required|date_format:H:i',
            'tanggal_pelaksanaan' => 'required|date',
            'lokasi_event' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $event = Event::findOrFail($id);
        
        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('posters'), $fileName);
            $validated['poster'] = 'posters/' . $fileName;
        }

        $event->update($validated);

        return redirect()->route('admin.event.detail', $id)->with('success', 'Event updated successfully');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        // Delete related tickets manually to ensure clean state
        \Illuminate\Support\Facades\DB::table('tiket')->where('id_event', $id)->delete();
        $event->delete();

        return redirect('/admin/dashboard')->with('success', 'Event deleted successfully');
    }
}
