<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function loginPage()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $admin = DB::table('admin')->where('email', $request->email)->first();

        if (!$admin || $request->password != 'password') {
            return back()->with('error', 'Email atau password salah');
        }

        session(['admin' => $admin]);

        return redirect('/admin/dashboard');
    }

    public function logout()
    {
        session()->forget('admin');
        return redirect('/admin/login');
    }
}
