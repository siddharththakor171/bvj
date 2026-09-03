<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JewelryInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $query = JewelryInquiry::query();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        $messages = $query->latest()->paginate(15)->withQueryString();

        return view('admin.contact-messages.index', compact('messages'));
    }

    public function updateStatus(Request $request, JewelryInquiry $inquiry): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,contacted,appointment_booked,closed'],
        ]);

        $inquiry->update(['status' => $validated['status']]);

        return back()->with('success', 'Contact message status updated.');
    }
}
