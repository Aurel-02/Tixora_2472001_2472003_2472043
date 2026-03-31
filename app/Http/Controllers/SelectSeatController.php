<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SelectSeatController extends Controller
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

    public function index($id)
    {
        $role = $this->currentRole();
        if (!$role) {
            return redirect('/login');
        }

        $event = Event::findOrFail($id);
        $tikets = DB::table('tiket')->where('id_event', $id)->get();
        
        $availableTypes = [];
        foreach ($tikets as $t) {
            $availableTypes[] = strtoupper(trim($t->jenis_tiket));
        }

        return view('select-seat', compact('event', 'tikets', 'availableTypes'));
    }
}
