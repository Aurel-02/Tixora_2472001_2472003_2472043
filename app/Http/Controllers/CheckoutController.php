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
        $hasWaiting = false;

        DB::beginTransaction();
        try {
            foreach ($tickets as $ticketId => $qty) {
                if ($qty <= 0) continue;

                $res = DB::select('CALL sp_checkout_ticket(?, ?, ?, ?)', [
                    $userId,
                    $ticketId,
                    (int)$qty,
                    $paymentMethod
                ]);

                if (!empty($res)) {
                    $status = $res[0]->status ?? 'ERROR';
                    $message = $res[0]->pesan ?? 'Unknown error occurred.';
                    
                    if ($status === 'WAITING') {
                        $hasWaiting = true;
                    }
                    
                    $results[] = [
                        'status' => $status,
                        'message' => $message
                    ];
                }
            }

            DB::commit();

            if ($hasWaiting) {
                return redirect()->route('my-tickets')->with('success', 'Checkout processed! Some tickets are in the Waiting List.');
            }

            return redirect()->route('my-tickets')->with('success', 'Checkout successful! Your tickets are ready.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Process Error: ' . $e->getMessage(), [
                'user_id' => $userId,
                'tickets' => $tickets,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/dashboard')->with('error', 'Failed to process checkout: ' . $e->getMessage());
        }
    }
}
