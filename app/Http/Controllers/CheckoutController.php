<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketMail;

class CheckoutController extends Controller
{
    /**
     * Process the checkout using sp_checkout_ticket.
     */
    public function process(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $userId = Auth::id();
        $paymentMethod = $request->input('payment_method');
        $tickets = $request->input('tickets', []); 
        
        if (empty($tickets)) {
            return redirect('/dashboard')->with('error', 'No tickets selected for checkout.');
        }

        $results = [];
        $errors = [];

        foreach ($tickets as $ticketId => $qty) {
            if ($qty <= 0) continue;

            try {
                $tiketInfo = DB::table('tiket')
                    ->join('event', 'tiket.id_event', '=', 'event.id_event')
                    ->where('tiket.id_tiket', $ticketId)
                    ->select('event.nama_event')
                    ->first();
                $eventName = $tiketInfo ? $tiketInfo->nama_event : 'Event';

                // 1. Buat kode unik di Laravel
                $randomCode = 'TIX-' . strtoupper(Str::random(10));

                // 2. Kirim 5 parameter (tambah $randomCode di akhir)
                $res = DB::select('CALL sp_checkout_ticket(?, ?, ?, ?, ?)', [
                    $userId,
                    $ticketId,
                    (int)$qty,
                    $paymentMethod,
                    $randomCode // Parameter ke-5
                ]);

                if (!empty($res)) {
                    $status = $res[0]->status ?? 'FAILED';
                    $message = $res[0]->pesan ?? 'Unknown error occurred.';
                    
                    if ($status === 'SUCCESS') {
                        // Di sini kamu bisa panggil fungsi kirim email pakai $randomCode
                        if(Auth::user()->email) {
                            Mail::to(Auth::user()->email)->send(new TicketMail($randomCode, $eventName));
                        }

                        DB::table('notifikasi')->insert([
                            'id_user' => $userId,
                            'pesan' => "Pembelian tiket event $eventName berhasil",
                            'is_read' => 0,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        $results[] = [
                            'ticket_id' => $ticketId,
                            'message' => $message
                        ];
                    } else if ($status === 'WAITING') {
                        DB::table('notifikasi')->insert([
                            'id_user' => $userId,
                            'pesan' => "Anda masuk waiting list untuk tiket event $eventName",
                            'is_read' => 0,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        $results[] = [
                            'ticket_id' => $ticketId,
                            'message' => $message // Pesan "Anda masuk daftar tunggu"
                        ];
                    } else {
                        $errors[] = "Ticket ID $ticketId: $message";
                    }
                }
            } catch (\Exception $e) {
                Log::error('Individual Ticket Checkout Error: ' . $e->getMessage(), [
                    'user_id' => $userId,
                    'ticket_id' => $ticketId,
                    'qty' => $qty
                ]);
                $errors[] = "Failed to process ticket ID $ticketId.";
            }
        }

        if (!empty($errors)) {
            $errorMsg = implode(' | ', $errors);
            if (empty($results)) {
                return redirect('/dashboard')->with('error', $errorMsg);
            }
            return redirect()->route('my-tickets')->with('warning', 'Some tickets failed: ' . $errorMsg);
        }

        return redirect()->route('my-tickets')->with('success', 'Checkout successful! Your tickets are ready.');
    }
}
