<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }

        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user,email,' . $user->id_user . ',id_user',
            'no_telp' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|string|min:6|confirmed',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo_profile')) {
            $file = $request->file('photo_profile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('profile_photos'), $filename);
            
            // Delete old photo if exists
            if ($user->photo_profile && file_exists(public_path($user->photo_profile))) {
                unlink(public_path($user->photo_profile));
            }
            
            $user->photo_profile = 'profile_photos/' . $filename;
        }

        if ($request->filled('current_password')) {
            try {
                $isValid = Hash::check($request->current_password, $user->password);
            } catch (\Exception $e) {
                $isValid = ($request->current_password === $user->password);
            }
            
            if (!$isValid) {
                return back()->withErrors(['current_password' => 'Current password does not match.'])->withInput();
            }
        }

        $user->nama_lengkap = $request->nama_lengkap;
        $user->email = $request->email;
        $user->no_telp = $request->no_telp;

        if ($request->filled('new_password')) {
            $user->password = $request->new_password;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }

    public function editPassword()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }

        return view('profile-password');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        try {
            $isValid = Hash::check($request->current_password, $user->password);
        } catch (\Exception $e) {
            $isValid = ($request->current_password === $user->password);
        }

        if (!$isValid) {
            return back()->withErrors(['current_password' => 'Current password does not match.'])->withInput();
        }

        $user->password = $request->new_password;
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Password updated successfully!');
    }
}
