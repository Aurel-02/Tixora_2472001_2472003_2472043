<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    /**
     * Tampilkan daftar semua user (buyer & organizer saja, tanpa admin).
     */
    public function index()
    {
        $users = User::whereIn('role', ['buyer', 'organizer', '2', '3'])
                     ->orderByRaw("FIELD(role, 'organizer', 'buyer')")
                     ->orderBy('nama_lengkap')
                     ->get();

        // Hitung stats
        $totalUsers    = $users->count();
        $activeUsers   = $users->where('status', 'active')->count();
        $inactiveUsers = $users->where('status', 'inactive')->count();
        $organizerCount = $users->filter(fn($u) => in_array($u->role, ['organizer', '2']))->count();

        return view('admin-usermanagement', compact(
            'users', 'totalUsers', 'activeUsers', 'inactiveUsers', 'organizerCount'
        ));
    }

    /**
     * Nonaktifkan akun user.
     */
    public function deactivate($id)
    {
        $user = User::where('id_user', $id)
                    ->whereIn('role', ['buyer', 'organizer', '2', '3'])
                    ->firstOrFail();

        $user->update(['status' => 'inactive']);

        return redirect()->route('admin.users.index')
                         ->with('success', "Akun \"{$user->nama_lengkap}\" berhasil dinonaktifkan.");
    }

    /**
     * Aktifkan kembali akun user.
     */
    public function activate($id)
    {
        $user = User::where('id_user', $id)
                    ->whereIn('role', ['buyer', 'organizer', '2', '3'])
                    ->firstOrFail();

        $user->update(['status' => 'active']);

        return redirect()->route('admin.users.index')
                         ->with('success', "Akun \"{$user->nama_lengkap}\" berhasil diaktifkan kembali.");
    }

    /**
     * Hapus akun user 
    
     */
    public function destroy($id)
    {
        $user = User::where('id_user', $id)
                    ->whereIn('role', ['buyer', 'organizer', '2', '3'])
                    ->firstOrFail();

        // Pengecekan tiket checkin
        $uncheckedCount = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('transaksi.id_user', $user->id_user)
            ->where('detail_transaksi.status_item', 'berhasil')
            ->where('detail_transaksi.checked_in', 0)
            ->count();

        if ($uncheckedCount > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', "Akun \"{$user->nama_lengkap}\" tidak dapat dihapus karena masih memiliki {$uncheckedCount} tiket yang belum di-check-in.");
        }

        // Pengecekan event yang di pegang oleh Organizer
        if (in_array($user->role, ['organizer', '2'])) {
            $activeEventsCount = \App\Models\Event::where('id_user', $user->id_user)
                ->whereDate('tanggal_pelaksanaan', '>=', now()->toDateString())
                ->count();

            if ($activeEventsCount > 0) {
                return redirect()->route('admin.users.index')
                    ->with('error', "Akun \"{$user->nama_lengkap}\" tidak dapat dihapus karena masih memiliki {$activeEventsCount} event yang sedang atau akan berlangsung.");
            }
        }

        $nama = $user->nama_lengkap;
        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', "Akun \"{$nama}\" berhasil dihapus secara permanen.");
    }
}
