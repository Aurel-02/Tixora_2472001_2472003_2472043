<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventController extends Controller
{
    public function approve($id)
    {
        $event = \App\Models\Event::findOrFail($id);
        // $event->status = 'approved';
        // $event->save();

        return back()->with('success','Event Approved (Simulation - Column Status missing)');
    }

    public function reject($id)
    {
        $event = \App\Models\Event::findOrFail($id);
        // $event->status = 'rejected';
        // $event->save();

        return back()->with('success','Event Rejected (Simulation - Column Status missing)');
    }
}
