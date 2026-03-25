<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        Event::create($request->all());
        return redirect('/admin/events');
    }

    public function edit($id)
    {
        $event = Event::find($id);
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        Event::find($id)->update($request->all());
        return redirect('/admin/events');
    }

    public function destroy($id)
    {
        Event::find($id)->delete();
        return redirect('/admin/events');
    }
}
