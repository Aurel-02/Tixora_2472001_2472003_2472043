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

    public function checkout(Request $request, $id)
    {
        $role = $this->currentRole();
        if (!$role) {
            return redirect('/login');
        }

        $event = Event::findOrFail($id);
        $quantities = $request->input('quantities', []); // [id_tiket => qty]
        
        $selectedTickets = [];
        $totalAmount = 0;
        
        foreach ($quantities as $tiketId => $qty) {
            if ($qty > 0) {
                $tiket = DB::table('tiket')->where('id_tiket', $tiketId)->first();
                if ($tiket) {
                    $selectedTickets[] = [
                        'details' => $tiket,
                        'quantity' => (int)$qty
                    ];
                    $totalAmount += $tiket->harga * $qty;
                }
            }
        }

        if (empty($selectedTickets)) {
            return back()->with('error', 'Please select at least one ticket.');
        }

        return view('checkout', compact('event', 'selectedTickets', 'totalAmount'));
    }

    public function processPayment(Request $request)
    {
        $role = $this->currentRole();
        if (!$role) {
            return redirect('/login');
        }

        $paymentMethod = $request->input('payment_method');

        return redirect('/dashboard')->with('success', 'Payment successful using ' . strtoupper($paymentMethod) . '! Your tickets will be sent to your email.');
    }
}
