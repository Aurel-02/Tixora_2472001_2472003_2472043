<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MyTicketController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $tickets = DB::table('transaksi')
            ->join('detail_transaksi', 'transaksi.id_transaksi', '=', 'detail_transaksi.id_transaksi')
            ->join('tiket', 'detail_transaksi.id_tiket', '=', 'tiket.id_tiket')
            ->join('event', 'tiket.id_event', '=', 'event.id_event')
            ->where('transaksi.id_user', $userId)
            ->orderBy('transaksi.tanggal_transaksi', 'desc')
            ->select(
                'event.nama_event',
                'event.tanggal_pelaksanaan',
                'event.lokasi_event',
                'event.poster',
                'tiket.jenis_tiket',
                'detail_transaksi.jumlah_beli',
                'detail_transaksi.kode_QR',
                'detail_transaksi.status_item',
                'detail_transaksi.id_detail',
                'transaksi.status_transaksi',
                'transaksi.tanggal_transaksi',
                'transaksi.id_transaksi'
            )
            ->get();

        $historyTickets = $tickets->filter(function($t) {
            return strtolower(trim($t->status_item)) === 'berhasil';
        });

        $waitingTickets = $tickets->filter(function($t) {
            return in_array(strtolower(trim($t->status_item)), ['pending', 'waiting']);
        });

        $canceledTickets = $tickets->filter(function($t) {
            return in_array(strtolower(trim($t->status_item)), ['batal', 'cancel']);
        });

        return view('my-tickets', compact('historyTickets', 'waitingTickets', 'canceledTickets'));
    }

    public function cancel($id)
    {
        $userId = Auth::id();

        $detail = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('detail_transaksi.id_detail', $id)
            ->where('transaksi.id_user', $userId)
            ->select('detail_transaksi.*', 'transaksi.id_transaksi')
            ->first();

        if (!$detail) {
            return back()->with('error', 'Ticket not found or unauthorized.');
        }

        DB::beginTransaction();
        try {
            DB::table('detail_transaksi')
                ->where('id_detail', $id)
                ->update(['status_item' => 'cancel']);
            DB::table('transaksi')
                ->where('id_transaksi', $detail->id_transaksi)
                ->update(['status_transaksi' => 'batal']);

            DB::commit();
            return back()->with('success', 'Ticket canceled successfully. It has been moved to the Canceled tab.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel ticket: ' . $e->getMessage());
        }
    }
}
