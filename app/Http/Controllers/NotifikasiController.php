<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use Carbon\Carbon;

class NotifikasiController extends Controller
{
    public function organizer()
    {
        $adminId = session('login_admin.id');
        if (!$adminId) {
            return redirect('/login');
        }

        $ticketsBought = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('tiket', 'detail_transaksi.id_tiket', '=', 'tiket.id_tiket')
            ->join('event', 'tiket.id_event', '=', 'event.id_event')
            ->join('user', 'transaksi.id_user', '=', 'user.id_user')
            ->where('event.id_user', $adminId)
            ->select(
                'detail_transaksi.jumlah_beli',
                'tiket.jenis_tiket',
                'user.nama_lengkap',
                'event.nama_event',
                'transaksi.tanggal_transaksi'
            )
            ->orderBy('transaksi.tanggal_transaksi', 'desc')
            ->get();

        $notifications = collect();

        foreach ($ticketsBought as $tb) {
            $date = $tb->tanggal_transaksi ?? \Carbon\Carbon::now();
            $isNew = Carbon::parse($date)->diffInDays(Carbon::now()) < 1;
            $badge = $isNew ? '<span class="notif-badge">Baru</span>' : '';
            $unreadClass = $isNew ? 'unread' : '';

            $desc = 'User <strong>' . htmlspecialchars($tb->nama_lengkap ?? 'Guest') . '</strong> baru saja membeli <strong>' . $tb->jumlah_beli . ' Tiket ' . $tb->jenis_tiket . '</strong> untuk event <strong>' . htmlspecialchars($tb->nama_event) . '</strong>.';

            $notifications->push((object)[
                'type' => 'ticket',
                'title' => 'Tiket Berhasil Terjual ' . $badge,
                'desc' => $desc,
                'icon' => '<i class="ph ph-ticket"></i>',
                'icon_class' => '',
                'unread_class' => $unreadClass,
                'time' => Carbon::parse($date)->diffForHumans(),
                'raw_time' => Carbon::parse($date)->timestamp
            ]);
        }

        // Fetch system notifications from notifikasi table
        $systemNotifs = DB::table('notifikasi')
            ->where('id_user', $adminId)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($systemNotifs as $sn) {
            $date = $sn->created_at ?? \Carbon\Carbon::now();
            $badge = ($sn->is_read == 0) ? '<span class="notif-badge">Baru</span>' : '';
            $unreadClass = ($sn->is_read == 0) ? 'unread' : '';

            $title = 'Pesan Sistem';
            $icon = '<i class="ph ph-bell-ringing"></i>';

            if (stripos($sn->pesan, 'diterima') !== false || stripos($sn->pesan, 'disetujui') !== false) {
                $title = 'Horeee! Permintaan Disetujui 🎉';
                $icon = '<i class="ph ph-confetti"></i>';
            } elseif (stripos($sn->pesan, 'ditolak') !== false) {
                $title = 'Ups! Permintaan Ditolak 😔';
                $icon = '<i class="ph ph-x-circle"></i>';
            }

            $notifications->push((object)[
                'type' => 'system',
                'title' => $title . ' ' . $badge,
                'desc' => $sn->pesan,
                'icon' => $icon,
                'icon_class' => 'system-notif',
                'unread_class' => $unreadClass,
                'time' => Carbon::parse($date)->diffForHumans(),
                'raw_time' => Carbon::parse($date)->timestamp
            ]);
        }

        // Mark all true system notifications as read
        DB::table('notifikasi')->where('id_user', $adminId)->where('is_read', 0)->update(['is_read' => 1]);

        $notifications = $notifications->sortByDesc('raw_time')->values();

        return view('notification', compact('notifications'));
    }
}
