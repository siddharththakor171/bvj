<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JewelryInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    /**
     * Display listing of customer VIP inquiries & consultations.
     */
    public function index(Request $request): View
    {
        $query = JewelryInquiry::query();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $inquiries = $query->latest()->paginate(10)->withQueryString();

        return view('admin.inquiries.index', compact('inquiries'));
    }

    /**
     * Store new customer inquiry.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'customer_email' => ['nullable', 'email'],
            'interested_category' => ['required', 'string'],
            'budget_range' => ['nullable', 'string'],
            'message' => ['nullable', 'string'],
        ]);

        $validated['inquiry_number'] = 'INQ-'.date('Y').'-'.rand(100, 999);
        $validated['status'] = 'new';

        JewelryInquiry::create($validated);

        return back()->with('success', 'Customer VIP consultation inquiry logged successfully!');
    }

    /**
     * Update inquiry status.
     */
    public function updateStatus(Request $request, JewelryInquiry $inquiry): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,contacted,appointment_booked,converted,closed'],
        ]);

        $inquiry->update(['status' => $validated['status']]);

        return back()->with('success', "Inquiry #{$inquiry->inquiry_number} status updated.");
    }
}
