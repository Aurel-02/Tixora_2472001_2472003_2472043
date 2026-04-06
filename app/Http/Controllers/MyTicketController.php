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

        // 1. Ambil detail tiket, pastikan join ke tabel 'tiket' untuk dapat 'id_event'
        $detail = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('tiket', 'detail_transaksi.id_tiket', '=', 'tiket.id_tiket') 
            ->join('event', 'tiket.id_event', '=', 'event.id_event')
            ->where('detail_transaksi.id_detail', $id)
            ->where('transaksi.id_user', $userId)
            ->select('detail_transaksi.*', 'transaksi.id_transaksi', 'tiket.id_event', 'event.nama_event')
            ->first();

        if (!$detail) {
            return back()->with('error', 'Tiket tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            // 2. Update status tiket yang dibatalkan
            DB::table('detail_transaksi')
                ->where('id_detail', $id)
                ->update(['status_item' => 'cancel']);

            DB::table('transaksi')
                ->where('id_transaksi', $detail->id_transaksi)
                ->update(['status_transaksi' => 'batal']);

            // 3. PANGGIL SP PROSES ANTREAN (Penting!)
            // Parameter sesuai SP: (p_id_event, p_id_tiket)
            // Lakukan looping sebanyak tiket yang dicancel agar antrean diproses tepat sesuai jumlah
            for ($i = 0; $i < $detail->jumlah_beli; $i++) {
                // Cek siapa di antrean pertama sebelum SP mengubah tabel
                $antrian = DB::table('antrian')->where('id_tiket', $detail->id_tiket)->orderBy('waktu_antri', 'asc')->first();
                if ($antrian) {
                    DB::table('notifikasi')->insert([
                        'id_user' => $antrian->id_user,
                        'pesan' => "Selamat! Tiket event {$detail->nama_event} berhasil didapatkan dari waiting list.",
                        'is_read' => 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                DB::statement("CALL sp_ProsesAntreanDetail(?, ?)", [
                    $detail->id_event, 
                    $detail->id_tiket
                ]);
            }

            // Notifikasi untuk yang membatalkan
            DB::table('notifikasi')->insert([
                'id_user' => $userId,
                'pesan' => "Pembelian tiket event {$detail->nama_event} berhasil dibatalkan",
                'is_read' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();
            return back()->with('success', 'Tiket berhasil dibatalkan dan antrean otomatis diproses!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan tiket: ' . $e->getMessage());
        }
    }
}
