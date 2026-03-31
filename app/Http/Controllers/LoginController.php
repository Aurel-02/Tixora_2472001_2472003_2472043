<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function show()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        if (empty($request->email) || empty($request->password)) {
            return back()->withErrors([
                'loginError' => 'Email dan password harus diisi'
            ])->withInput();
        }

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            $user = DB::table('admin')->where('email', $request->email)->first();
        }

        if ($user) {
            $passwordMatches = false;

            if (isset($user->password)) {
                $encryptedPassword = $user->password;

                if ($this->isBcryptHash($encryptedPassword)) {
                    $passwordMatches = Hash::check($request->password, $encryptedPassword);
                } else {
                    $passwordMatches = $request->password === $encryptedPassword;
                }
            }

            if ($passwordMatches) {
                $role = strtolower(trim($user->role ?? ''));

                if ($user instanceof \App\Models\User) {
                    Auth::login($user);
                    $request->session()->regenerate();

                    if ($role == '2' || $role == 'organizer') {
                        session(['login_admin' => [
                            'id' => $user->id_user,
                            'name' => $user->nama_lengkap,
                            'role' => $user->role,
                            'email' => $user->email,
                        ]]);
                    }
                } else {
                    session(['login_admin' => [
                        'id' => $user->id_admin,
                        'name' => $user->nama,
                        'role' => $user->role,
                        'email' => $user->email,
                    ]]);
                }
                
                if ($role == '2' || $role == 'organizer') {
                     return redirect('/organizerdashboard');
                } elseif ($role == '1' || $role == 'admin') {
                    return redirect('/admin/dashboard');
                } elseif ($role == '3' || $role == 'buyer') {
                    return redirect('/dashboard');
                }

                return redirect('/');
            }
        }

        return back()->withErrors([
            'loginError' => 'Email atau password salah',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->forget('login_admin');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function isBcryptHash(?string $hash): bool
    {
        if (!$hash || !is_string($hash)) {
            return false;
        }

        return str_starts_with($hash, '$2y$') || str_starts_with($hash, '$2a$') || str_starts_with($hash, '$2b$');
    }
}
