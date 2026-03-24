<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('login-page');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $admin = DB::table('admin')->where('email', $credentials['email'])->first();

        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            return back()->withErrors(['loginError' => 'Email atau password salah'])->withInput();
        }

        session([ 'admin' => [
            'id' => $admin->id_admin,
            'name' => $admin->nama,
            'role' => $admin->role,
            'email' => $admin->email,
        ]]);

        if ($admin->role === 'organizer') {
            return redirect('/organizerdashboard');
        }

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin');
        return redirect('/login-page');
    }
}
