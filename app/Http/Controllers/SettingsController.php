<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = 'profile';
        if ($request->is('admin/settings/password')) {
            $activeTab = 'password';
        }

        return view('settings.index', [
            'user' => Auth::user(),
            'activeTab' => $activeTab
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'language' => 'required|in:en,hi',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'language' => $request->language,
        ]);

        // Update session if the language was changed
        session(['locale' => $request->language]);

        return response()->json([
            'success' => 'Profile updated successfully',
            'reload' => true // Signal to frontend that a reload might be needed to apply language immediately
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['errors' => ['current_password' => ['Current password is incorrect']]], 422);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json(['success' => 'Password changed successfully']);
    }
}
