<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'full_name' => 'nullable|string|max:255',
            'nim' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'university' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|integer|min:2020|max:2030',
        ]);

        // Update user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update or create profile
        $profileData = [
            'full_name' => $request->full_name,
            'nim' => $request->nim,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'university' => $request->university,
            'major' => $request->major,
            'graduation_year' => $request->graduation_year,
        ];

        if ($user->profile) {
            $user->profile->update($profileData);
        } else {
            $user->profile()->create(array_merge($profileData, ['user_id' => $user->id]));
        }

        return redirect()->route('profile.show')->with('success', 'Profile berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')->with('success', 'Password berhasil diperbarui!');
    }
}
