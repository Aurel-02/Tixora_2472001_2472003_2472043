<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
                $res = DB::select('CALL sp_checkout_ticket(?, ?, ?, ?)', [
                    $userId,
                    $ticketId,
                    (int)$qty,
                    $paymentMethod
                ]);

                if (!empty($res)) {
                    $status = $res[0]->status ?? 'FAILED';
                    $message = $res[0]->pesan ?? 'Unknown error occurred.';
                    
                    if ($status === 'SUCCESS') {
                        $results[] = [
                            'ticket_id' => $ticketId,
                            'message' => $message
                        ];
                    } else if ($status === 'WAITING') {
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
