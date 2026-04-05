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

        $tanggal = $event->tanggal_pelaksanaan ? \Illuminate\Support\Carbon::parse($event->tanggal_pelaksanaan) : null;
        $now = \Illuminate\Support\Carbon::now();
        $threeMonthsLater = $now->copy()->addMonths(3);
        $isUpcoming = $tanggal && $tanggal->isAfter($threeMonthsLater);

        if ($isUpcoming) {
            return redirect()->route('event.detail', $id)->with('error', 'Tickets for this upcoming event are not available yet.');
        }

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

        $tanggal = $event->tanggal_pelaksanaan ? \Illuminate\Support\Carbon::parse($event->tanggal_pelaksanaan) : null;
        $now = \Illuminate\Support\Carbon::now();
        $threeMonthsLater = $now->copy()->addMonths(3);
        $isUpcoming = $tanggal && $tanggal->isAfter($threeMonthsLater);

        if ($isUpcoming) {
            return redirect()->route('event.detail', $id)->with('error', 'Tickets for this upcoming event are not available yet.');
        }

        $quantities = $request->input('quantities', []); // [id_tiket => qty]
        
        $selectedTickets = [];
        $totalAmount = 0;
        
        foreach ($quantities as $tiketId => $qty) {
            if ($qty > 0) {
                $tiket = DB::table('tiket')->where('id_tiket', $tiketId)->first();
                if ($tiket) {
                    $selectedTickets[] = [
                        'details' => $tiket,
                        'quantity' => (int) $qty
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
        $tickets = $request->input('tickets', []); // [id_tiket => qty]
        $userId = Auth::id();

        if (empty($tickets)) {
            return redirect('/dashboard')->with('error', 'No tickets selected.');
        }

        DB::beginTransaction();
        try {
            $firstTicketId = key($tickets);
            $ticketData = DB::table('tiket')->where('id_tiket', $firstTicketId)->first();
            if (!$ticketData) {
                throw new \Exception('Invalid ticket selection.');
            }
            $idEvent = $ticketData->id_event;

            $totalAmount = 0;
            foreach ($tickets as $id => $qty) {
                if ($qty > 0) {
                    $t = DB::table('tiket')->where('id_tiket', $id)->first();
                    if ($t) {
                        $totalAmount += $t->harga * $qty;
                    }
                }
            }

            $res = DB::selectOne('SELECT sp_buat_pesanan(?, ?, ?, ?) AS id', [
                $userId, 
                $idEvent, 
                $paymentMethod, 
                $totalAmount
            ]);
            $newOrderId = $res ? $res->id : null;

            if (!$newOrderId) {
                throw new \Exception('Failed to generate transaction ID.');
            }

            $hasWaiting = false;
            foreach ($tickets as $id => $qty) {
                if ($qty > 0) {
                    DB::statement('CALL sp_tambah_item(?, ?, ?)', [$newOrderId, $id, $qty]);
                    
                    $detail = DB::table('detail_transaksi')
                        ->where('id_transaksi', $newOrderId)
                        ->where('id_tiket', $id)
                        ->first();
                    if ($detail && $detail->status_item == 'waiting') {
                        $hasWaiting = true;
                    }
                }
            }
            DB::commit();

            $message = $hasWaiting 
                ? 'Order placed! Some tickets are in the Waiting List.' 
                : 'Payment successful using ' . strtoupper($paymentMethod) . '!';
            
            return redirect()->route('my-tickets')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Checkout failed: ' . $e->getMessage());
            return redirect('/dashboard')->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }
}
