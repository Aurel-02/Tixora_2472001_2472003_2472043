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
        $usersBuilder = User::whereIn('role', ['buyer', 'organizer', '2', '3'])
                     ->orderByRaw("FIELD(role, 'organizer', '2', 'buyer', '3')")
                     ->orderBy('nama_lengkap')
                     ->get()
                     ->map(function($u) {
                         return (object)[
                             'id_user' => $u->id_user,
                             'nama_lengkap' => $u->nama_lengkap,
                             'email' => $u->email,
                             'role' => $u->role,
                             'status' => $u->status,
                             'photo_profile' => $u->photo_profile,
                             'no_telp' => $u->no_telp,
                             'created_at' => $u->created_at,
                         ];
                     });

        $admins = DB::table('admin')->get()->map(function($a) {
            return (object)[
                'id_user' => $a->id,
                'nama_lengkap' => $a->nama,
                'email' => $a->email,
                'role' => 'admin',
                'status' => 'active', 
                'photo_profile' => null,
                'created_at' => $a->created_at ?? null,
            ];
        });
        
        $users = collect($admins)->merge($usersBuilder);

        // Hitung stats
        $totalUsers    = $users->count();
        $activeUsers   = $users->where('status', 'active')->count();
        $inactiveUsers = $users->where('status', 'inactive')->count();
        $organizerCount = $users->filter(fn($u) => in_array($u->role, ['organizer', '2']))->count();

        return view('admin.users.index', compact(
            'users', 'totalUsers', 'activeUsers', 'inactiveUsers', 'organizerCount'
        ));
    }

    private function checkRestrictions($user, $action)
    {
        // 1. Pengecekan event yang di pegang oleh Organizer
        if (in_array(strtolower($user->role), ['organizer', '2'])) {
            $eventsCount = DB::table('event')
                ->where('id_user', $user->id_user)
                ->count();

            if ($eventsCount > 0) {
                return "Akun \"{$user->nama_lengkap}\" tidak dapat {$action} karena organizer ini masih memiliki / mengelola event.";
            }
        }

        // 2. Pengecekan tiket checkin (untuk buyer/user yang sudah beli tiket)
        $uncheckedCount = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('tiket', 'detail_transaksi.id_tiket', '=', 'tiket.id_tiket')
            ->where('transaksi.id_user', $user->id_user)
            ->where('detail_transaksi.status_item', 'berhasil')
            ->where('detail_transaksi.checked_in', 0)
            ->count();

        if ($uncheckedCount > 0) {
            return "Akun \"{$user->nama_lengkap}\" tidak dapat {$action} karena masih memiliki tiket yang belum di-scan QR.";
        }

        return null;
    }

    /**
     * Nonaktifkan akun user.
     */
    public function deactivate($id)
    {
        $user = User::where('id_user', $id)
                    ->whereIn('role', ['buyer', 'organizer', '2', '3'])
                    ->firstOrFail();

        $error = $this->checkRestrictions($user, 'dinonaktifkan');
        if ($error) {
            return redirect()->route('admin.users.index')->with('error', $error);
        }

        $user->timestamps = false;
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

        $user->timestamps = false;
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

        $error = $this->checkRestrictions($user, 'dihapus');
        if ($error) {
            return redirect()->route('admin.users.index')->with('error', $error);
        }

        $nama = $user->nama_lengkap;
        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', "Akun \"{$nama}\" berhasil dihapus secara permanen.");
    }
}
