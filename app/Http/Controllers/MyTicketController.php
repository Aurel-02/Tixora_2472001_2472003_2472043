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
                'transaksi.status_transaksi',
                'transaksi.tanggal_transaksi',
                'transaksi.id_transaksi'
            )
            ->get();

        return view('my-tickets', compact('tickets'));
    }
}
