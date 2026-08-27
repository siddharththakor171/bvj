<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JewelryOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display listing of jewelry customer orders and custom making requests.
     */
    public function index(Request $request): View
    {
        $query = JewelryOrder::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Store a new custom making / sales order.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'customer_email' => ['nullable', 'email'],
            'customer_city' => ['nullable', 'string', 'max:100'],
            'order_type' => ['required', 'string'],
            'items_summary' => ['required', 'string'],
            'total_weight' => ['nullable', 'numeric', 'min:0'],
            'subtotal_amount' => ['required', 'numeric', 'min:0'],
            'making_charges_total' => ['nullable', 'numeric', 'min:0'],
            'gst_amount' => ['nullable', 'numeric', 'min:0'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'advance_paid' => ['required', 'numeric', 'min:0'],
            'delivery_due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $orderNumber = 'BVJ-ORD-'.date('Y').'-'.rand(1000, 9999);
        $total = (float) $validated['total_amount'];
        $advance = (float) $validated['advance_paid'];

        $validated['order_number'] = $orderNumber;
        $validated['balance_due'] = max(0, $total - $advance);
        $validated['status'] = 'in_workshop';

        JewelryOrder::create($validated);

        return redirect()->route('admin.orders.index')
            ->with('success', "Order #{$orderNumber} created successfully!");
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, JewelryOrder $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_workshop,hallmarking,ready_for_pickup,completed,cancelled'],
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', "Order #{$order->order_number} status updated to ".ucfirst(str_replace('_', ' ', $validated['status'])));
    }
}
