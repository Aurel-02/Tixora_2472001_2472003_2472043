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
        $isAdmin = false;

        if (!$user && session()->has('login_admin')) {
            $adminId = session('login_admin.id');
            $user = \Illuminate\Support\Facades\DB::table('admin')->where('id', $adminId)->first();
            $isAdmin = true;
            
            // Map 'nama' to 'nama_lengkap' for view consistency
            if ($user) {
                $user->nama_lengkap = $user->nama;
            }
        }

        if (!$user) {
            return redirect('/login');
        }

        return view('profile', compact('user', 'isAdmin'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $isAdmin = false;
        $adminId = null;

        if (!$user && session()->has('login_admin')) {
            $adminId = session('login_admin.id');
            $user = \Illuminate\Support\Facades\DB::table('admin')->where('id', $adminId)->first();
            $isAdmin = true;
        }

        if (!$user) {
            return redirect('/login');
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:' . ($isAdmin ? 'admin' : 'user') . ',email,' . ($isAdmin ? $adminId : $user->id_user) . ',' . ($isAdmin ? 'id' : 'id_user'),
            'no_telp' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|string|min:6|confirmed',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo_profile')) {
            $file = $request->file('photo_profile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('profile_photos'), $filename);
            $photoPath = 'profile_photos/' . $filename;
            
            // Delete old photo
            $oldPhoto = $isAdmin ? null : $user->photo_profile; // Assuming admin table doesn't have photo_profile yet based on migration
            if ($oldPhoto && file_exists(public_path($oldPhoto))) {
                unlink(public_path($oldPhoto));
            }
        }

        $newPassword = null;
        if ($request->filled('current_password')) {
            $currentStoredPassword = $isAdmin ? $user->password : $user->password;
            try {
                $isValid = Hash::check($request->current_password, $currentStoredPassword);
            } catch (\Exception $e) {
                $isValid = ($request->current_password === $currentStoredPassword);
            }
            
            if (!$isValid) {
                return back()->withErrors(['current_password' => 'Current password does not match.'])->withInput();
            }

            if ($request->filled('new_password')) {
                $newPassword = Hash::make($request->new_password);
            }
        }

        if ($isAdmin) {
            $updateData = [
                'nama' => $request->nama_lengkap,
                'email' => $request->email,
            ];
            if ($newPassword) {
                $updateData['password'] = $newPassword;
            }
            \Illuminate\Support\Facades\DB::table('admin')->where('id', $adminId)->update($updateData);
            
            // Update session
            session(['login_admin' => array_merge(session('login_admin'), [
                'name' => $request->nama_lengkap,
                'email' => $request->email,
            ])]);
        } else {
            $user->nama_lengkap = $request->nama_lengkap;
            $user->email = $request->email;
            $user->no_telp = $request->no_telp;
            if ($photoPath) {
                $user->photo_profile = $photoPath;
            }
            if ($newPassword) {
                $user->password = $newPassword;
            }
            $user->save();
        }

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }

    public function editPassword()
    {
        $user = Auth::user();
        $isAdmin = false;

        if (!$user && session()->has('login_admin')) {
            $adminId = session('login_admin.id');
            $user = \Illuminate\Support\Facades\DB::table('admin')->where('id', $adminId)->first();
            $isAdmin = true;
        }

        if (!$user) {
            return redirect('/login');
        }

        return view('profile-password', compact('isAdmin'));
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $isAdmin = false;
        $adminId = null;

        if (!$user && session()->has('login_admin')) {
            $adminId = session('login_admin.id');
            $user = \Illuminate\Support\Facades\DB::table('admin')->where('id', $adminId)->first();
            $isAdmin = true;
        }

        if (!$user) {
            return redirect('/login');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $currentStoredPassword = $isAdmin ? $user->password : $user->password;
        try {
            $isValid = Hash::check($request->current_password, $currentStoredPassword);
        } catch (\Exception $e) {
            $isValid = ($request->current_password === $currentStoredPassword);
        }

        if (!$isValid) {
            return back()->withErrors(['current_password' => 'Current password does not match.'])->withInput();
        }

        if ($isAdmin) {
            \Illuminate\Support\Facades\DB::table('admin')->where('id', $adminId)->update(['password' => Hash::make($request->new_password)]);
        } else {
            $user->password = $request->new_password;
            $user->save();
        }

        return redirect()->route('profile.edit')->with('success', 'Password updated successfully!');
    }
}
