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
        $tickets = $request->input('tickets', []);
        $userId = Auth::id();

        DB::beginTransaction();
        try {
            // 1. Create Transaction Header
            // sp_buat_pesanan(p_id_user, p_metode_pembayaran, OUT p_id_transaksi)
            DB::statement('CALL sp_buat_pesanan(?, ?, @id_baru)', [$userId, $paymentMethod]);
            $res = DB::selectOne('SELECT @id_baru as id');
            $newOrderId = $res ? $res->id : null;

            if (!$newOrderId) {
                throw new \Exception('Failed to generate transaction ID.');
            }

            // 2. Add Transaction Items
            // sp_tambah_item(p_id_transaksi, p_id_tiket, p_qty)
            foreach ($tickets as $id => $qty) {
                if ($qty > 0) {
                    DB::statement('CALL sp_tambah_item(?, ?, ?)', [$newOrderId, $id, $qty]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Redirect to dashboard with error to avoid MethodNotAllowed on back()
            return redirect('/dashboard')->with('error', 'Checkout failed: ' . $e->getMessage());
        }

        return redirect('/dashboard')->with('success', 'Payment successful using ' . strtoupper($paymentMethod) . '! Your tickets will be sent to your email.');
    }
}
