<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Event;

class RevenueController extends Controller
{
    public function index()
    {
        $rawRole = auth()->check() ? auth()->user()->role : (session()->has('login_admin') ? session('login_admin.role') : '3');
        $role = strtolower(trim($rawRole));

        $adminRevenue = 0;
        $organizerRevenue = 0;
        $totalTransactions = 0;
        $totalUangMasuk = 0;
        $totalJatahAdmin = 0;
        $totalJatahOrganizer = 0;
        $recentTransactions = [];

        if ($role == '1' || $role == 'admin') {
            $totals = DB::table('detail_transaksi')
                ->where('status_item', 'berhasil')
                ->select(DB::raw('SUM(subtotal) as total_gross, SUM(jumlah_beli) as total_sold'))
                ->first();

            $totalGross = (float)($totals->total_gross ?? 0);
            $totalSoldCount = (int)($totals->total_sold ?? 0);
            
            $totalUangMasuk = $totalGross;
            $totalJatahAdmin = $totalSoldCount * 5000;
            $totalJatahOrganizer = $totalGross - $totalJatahAdmin;

            $adminRevenue = $totalJatahAdmin;
            $organizerRevenue = $totalJatahOrganizer;
            $totalTransactions = $totalSoldCount;

            $transactions = DB::table('detail_transaksi')
                ->join('tiket', 'detail_transaksi.id_tiket', '=', 'tiket.id_tiket')
                ->join('event', 'tiket.id_event', '=', 'event.id_event')
                ->select('detail_transaksi.*', 'event.nama_event', 'tiket.jenis_tiket')
                ->orderBy('detail_transaksi.id_detail', 'desc')
                ->limit(10)
                ->get();

            foreach ($transactions as $tx) {
                $recentTransactions[] = [
                    'id' => $tx->kode_QR ?? ('TX-' . str_pad($tx->id_detail, 4, '0', STR_PAD_LEFT)),
                    'event' => $tx->nama_event . ' (' . $tx->jenis_tiket . ')',
                    'amount' => (float)$tx->subtotal,
                    'status' => ($tx->status_item == 'berhasil') ? 'Berhasil' : 'Gagal',
                ];
            }
        } elseif ($role == '2' || $role == 'organizer') {
            $organizerId = session('login_admin.id') ?? auth()->id();
            
            $events = Event::where('id_user', $organizerId)->get();
            $totalTransactionsCount = 0;
            
            foreach ($events as $event) {
                $revenueData = DB::table('tiket')
                    ->join('detail_transaksi', 'tiket.id_tiket', '=', 'detail_transaksi.id_tiket')
                    ->where('tiket.id_event', $event->id_event)
                    ->where('detail_transaksi.status_item', 'berhasil')
                    ->select(DB::raw('SUM(detail_transaksi.subtotal) as total_kotor, SUM(detail_transaksi.jumlah_beli) as total_sold'))
                    ->first();
                
                $totalKotor = (float)($revenueData->total_kotor ?? 0);
                $totalTiket = (int)($revenueData->total_sold ?? 0);
                $feeAdmin = $totalTiket * 5000;
                $pendapatanBersih = max(0, $totalKotor - $feeAdmin);
                
                $totalUangMasuk += $totalKotor;
                $totalJatahAdmin += $feeAdmin;
                $totalJatahOrganizer += $pendapatanBersih;
                
                $totalTransactionsCount += $totalTiket;
            }

            $transactions = DB::table('detail_transaksi')
                ->join('tiket', 'detail_transaksi.id_tiket', '=', 'tiket.id_tiket')
                ->join('event', 'tiket.id_event', '=', 'event.id_event')
                ->where('event.id_user', $organizerId)
                ->select('detail_transaksi.*', 'event.nama_event', 'tiket.jenis_tiket')
                ->orderBy('detail_transaksi.id_detail', 'desc')
                ->limit(10)
                ->get();

            foreach ($transactions as $tx) {
                $recentTransactions[] = [
                    'id' => $tx->kode_QR ?? ('TX-' . str_pad($tx->id_detail, 4, '0', STR_PAD_LEFT)),
                    'event' => $tx->nama_event . ' (' . $tx->jenis_tiket . ')',
                    'amount' => (float)$tx->subtotal,
                    'status' => ($tx->status_item == 'berhasil') ? 'Berhasil' : 'Gagal',
                ];
            }
        }

        return view('revenue', compact(
            'adminRevenue', 
            'organizerRevenue', 
            'totalTransactions', 
            'recentTransactions', 
            'role',
            'totalUangMasuk',
            'totalJatahAdmin',
            'totalJatahOrganizer'
        ));
    }
}
