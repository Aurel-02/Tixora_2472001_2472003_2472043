<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('login-page');
    }

    public function authenticate(Request $request)
    {
        if (empty($request->email) || empty($request->password)) {
            return back()->withErrors([
                'loginError' => 'Email dan password harus diisi'
            ])->withInput();
        }

        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user && $user->password === $request->password) {
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'loginError' => 'Email atau password salah',
        ])->withInput();
    }
}
