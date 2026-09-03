<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show admin profile and store settings.
     */
    public function show(): View
    {
        $user = Auth::user();
        $storeSetting = StoreSetting::firstOrFail();

        return view('admin.profile.show', compact('user', 'storeSetting'));
    }

    /**
     * Update admin profile details.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username,'.$user->id],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update(collect($validated)->only(['name', 'username', 'email', 'phone'])->all());

        return back()->with('success', 'Admin profile details updated successfully!');
    }

    public function updateCertificate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'establishment' => ['required', 'string', 'max:255'],
            'tagline' => ['required', 'string', 'max:255'],
            'bis_certificate' => ['required', 'string', 'max:255'],
            'bis_note' => ['required', 'string', 'max:255'],
            'gstin' => ['required', 'string', 'max:255'],
            'gst_note' => ['required', 'string', 'max:255'],
        ]);

        StoreSetting::firstOrFail()->update(collect($validated)->only([
            'establishment', 'tagline', 'bis_certificate', 'bis_note', 'gstin', 'gst_note',
        ])->all());

        return back()->with('success', 'Jeweller business certificate details updated successfully!');
    }
}
