<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BuyerNotificationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        if (!$userId) {
            return redirect('/login');
        }

        $notifications = DB::table('notifikasi')
            ->where('id_user', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadCount = $notifications->where('is_read', 0)->count();
        if ($unreadCount > 0) {
            DB::table('notifikasi')
                ->where('id_user', $userId)
                ->where('is_read', 0)
                ->update(['is_read' => 1]);
        }

        return view('buyer-notification', compact('notifications'));
    }
}
