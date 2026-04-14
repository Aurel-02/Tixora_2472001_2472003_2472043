<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Tampilkan daftar semua pengguna.
     */
    public function index()
    {
        // Ambil semua user kecuali yang sedang login (supaya admin tidak hapus dirinya sendiri)
        $users = User::orderByRaw("FIELD(role, 'admin', 'organizer', 'buyer')")
                     ->orderBy('nama_lengkap')
                     ->get();

        return view('admin-usermanagement', compact('users'));
    }

    /**
     * Nonaktifkan akun user (set status = inactive).
     */
    public function deactivate($id)
    {
        $user = User::where('id_user', $id)->firstOrFail();

        // Jangan izinkan nonaktifkan akun admin lain
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Akun admin tidak dapat dinonaktifkan.');
        }

        $user->update(['status' => 'inactive']);

        return redirect()->route('admin.users.index')
                         ->with('success', "Akun {$user->nama_lengkap} berhasil dinonaktifkan.");
    }

    /**
     * Aktifkan kembali akun user (set status = active).
     */
    public function activate($id)
    {
        $user = User::where('id_user', $id)->firstOrFail();
        $user->update(['status' => 'active']);

        return redirect()->route('admin.users.index')
                         ->with('success', "Akun {$user->nama_lengkap} berhasil diaktifkan kembali.");
    }

    /**
     * Hapus akun user secara permanen.
     */
    public function destroy($id)
    {
        $user = User::where('id_user', $id)->firstOrFail();

        // Jangan hapus akun admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Akun admin tidak dapat dihapus.');
        }

        $nama = $user->nama_lengkap;
        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', "Akun {$nama} berhasil dihapus secara permanen.");
    }
}
