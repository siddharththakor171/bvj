<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordController extends Controller
{
    /**
     * Display the Change Password page.
     */
    public function showChangePasswordForm(): View
    {
        $user = Auth::user();

        return view('admin.password.change', compact('user'));
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'confirmed', Password::min(6)],
        ], [
            'current_password.current_password' => 'Your current password does not match our records.',
            'password.confirmed' => 'New password confirmation does not match.',
            'password.min' => 'The new password must be at least 6 characters long.',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('admin.password.change')
            ->with('success', 'Your password has been changed successfully! Keep your new credentials safe.');
    }
}
