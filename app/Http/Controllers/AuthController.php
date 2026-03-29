<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = DB::table('user')->where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['loginError' => 'Email atau password salah'])->withInput();
        }

        session(['login_admin' => [
            'id' => $user->id_user,
            'name' => $user->nama_lengkap,
            'role' => $user->role,
            'email' => $user->email,
        ]]);

        $role = strtolower(trim($user->role ?? ''));
        
        if ($role == '2' || $role == 'organizer') {
            return redirect('/organizerdashboard');
        }

        if ($role == '3' || $role == 'buyer') {
            return redirect('/dashboard');
        }

        return redirect('/admin/dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('login_admin');
        return redirect('/login');
    }
}
