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

            // Panggil sp_buat_pesanan dengan parameter yang benar (3 param: user, metode, OUT id)
            DB::statement('CALL sp_buat_pesanan(?, ?, @new_id)', [$userId, $paymentMethod]);
            $res = DB::selectOne('SELECT @new_id AS id');
            $newOrderId = $res ? $res->id : null;

            if (!$newOrderId) {
                throw new \Exception('Failed to generate transaction ID.');
            }

            $hasWaiting = false;
            $hasSuccess = false;
            $totalQtyToScan = 0;

            foreach ($tickets as $id => $qty) {
                if ($qty > 0) {
                    $tiket = DB::table('tiket')->where('id_tiket', $id)->first();
                    $eventName = '';
                    if ($tiket) {
                        $eventRow = DB::table('event')->where('id_event', $tiket->id_event)->first();
                        $eventName = $eventRow ? $eventRow->nama_event : 'Event';
                    }

                    DB::statement('CALL sp_tambah_item(?, ?, ?)', [$newOrderId, $id, $qty]);

                    $detail = DB::table('detail_transaksi')
                        ->where('id_transaksi', $newOrderId)
                        ->where('id_tiket', $id)
                        ->first();

                    if ($detail && strtolower(trim($detail->status_item)) === 'waiting') {
                        $hasWaiting = true;
                        // Notifikasi masuk waiting list
                        DB::table('notifikasi')->insert([
                            'id_user'    => $userId,
                            'pesan'      => "Anda masuk waiting list untuk tiket event $eventName.",
                            'is_read'    => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } elseif ($detail && strtolower(trim($detail->status_item)) === 'berhasil') {
                        $hasSuccess = true;
                        $totalQtyToScan += (int)$qty;
                        // Notifikasi pembelian berhasil
                        DB::table('notifikasi')->insert([
                            'id_user'    => $userId,
                            'pesan'      => "Pembelian tiket event $eventName berhasil.",
                            'is_read'    => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();

            if ($hasSuccess && $totalQtyToScan > 0) {
                $message = $hasWaiting
                    ? 'Pembayaran berhasil! Beberapa tiket masuk Waiting List.'
                    : 'Pembayaran berhasil menggunakan ' . strtoupper($paymentMethod) . '!';
                return redirect()->route('face-scan.index', ['total' => $totalQtyToScan])
                    ->with('success', $message);
            }

            $message = $hasWaiting
                ? 'Order berhasil! Tiket kamu masuk Waiting List.'
                : 'Pembayaran berhasil menggunakan ' . strtoupper($paymentMethod) . '!';

            return redirect()->route('my-tickets')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Checkout failed: ' . $e->getMessage());
            return redirect('/dashboard')->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }
}
