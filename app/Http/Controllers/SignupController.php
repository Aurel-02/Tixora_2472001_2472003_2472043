<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SignupController extends Controller
{
    public function show()
    {
        return view('signup');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user',
            'no_telp' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:Organizer,Buyer',
        ]);

        DB::statement('CALL sp_add_user(?, ?, ?, ?, ?)', [
            $request->nama_lengkap,
            $request->email,
            $request->no_telp,
            Hash::make($request->password),
            $request->role
        ]);

        return redirect('/login')->with('success', 'Signup berhasil! Silakan login menggunakan akun baru Anda.');
    }
}
