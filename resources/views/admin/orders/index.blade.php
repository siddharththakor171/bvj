@extends('layouts.admin')

@section('title', 'Custom Orders & Workshop')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Custom Bridal Orders & Workshop Tracker</h1>
        <p class="page-subtitle">Track custom jewellery manufacturing, hallmarking milestones, and client deliveries.</p>
    </div>
    <div>
        <button type="button" class="btn-gold" onclick="openModal('addOrderModal')">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            New Custom Order
        </button>
    </div>
</div>

<!-- Filters -->
<div class="gold-card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form method="GET" action="{{ route('admin.orders.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <div style="flex: 2; min-width: 200px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Order #, Customer, Phone..." class="form-control">
        </div>
        <div style="flex: 1; min-width: 150px;">
            <select name="status" class="form-control">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_workshop" {{ request('status') === 'in_workshop' ? 'selected' : '' }}>In Workshop</option>
                <option value="hallmarking" {{ request('status') === 'hallmarking' ? 'selected' : '' }}>Hallmarking</option>
                <option value="ready_for_pickup" {{ request('status') === 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        <button type="submit" class="btn-gold">Filter</button>
        <a href="{{ route('admin.orders.index') }}" class="btn-outline-gold">Reset</a>
    </form>
</div>

<!-- Orders Table -->
<div class="gold-card">
    <div class="table-responsive">
        <table class="luxury-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer & Contact</th>
                    <th>Order Type & Items</th>
                    <th>Weight</th>
                    <th>Total / Advance</th>
                    <th>Balance Due</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                    <tr>
                        <td style="font-family: monospace; color: var(--gold-primary); font-weight: 700;">
                            {{ $o->order_number }}
                        </td>
                        <td>
                            <div style="font-weight: 700; color: #1f1c18;">{{ $o->customer_name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $o->customer_phone }} ({{ $o->customer_city ?? 'Local' }})</div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: var(--gold-primary); font-size: 0.82rem;">{{ $o->order_type }}</div>
                            <div style="font-size: 0.8rem; color: #44403c; max-width: 220px;">{{ $o->items_summary }}</div>
                        </td>
                        <td>
                            <span style="font-weight: 700; color: #1f1c18;">{{ $o->total_weight ? number_format($o->total_weight, 3) . 'g' : '-' }}</span>
                        </td>
                        <td>
                            <div style="font-weight: 800; color: #1f1c18;">₹{{ number_format($o->total_amount, 2) }}</div>
                            <div style="font-size: 0.75rem; color: var(--color-success); font-weight: 600;">Adv: ₹{{ number_format($o->advance_paid, 2) }}</div>
                        </td>
                        <td>
                            @if($o->balance_due > 0)
                                <span style="font-weight: 700; color: #dc2626;">₹{{ number_format($o->balance_due, 2) }}</span>
                            @else
                                <span style="color: var(--color-success); font-weight: 700;">Paid in Full</span>
                            @endif
                        </td>
                        <td style="font-size: 0.8rem; color: #44403c; font-weight: 500;">
                            {{ $o->delivery_due_date ? $o->delivery_due_date->format('d M Y') : 'Immediate' }}
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.orders.status', $o->id) }}" style="display: inline-block;">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="form-control" style="padding: 0.3rem 0.5rem; font-size: 0.75rem; width: auto; background: #ffffff; color: #1f1c18; border-color: var(--border-color);">
                                    <option value="pending" {{ $o->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_workshop" {{ $o->status === 'in_workshop' ? 'selected' : '' }}>In Workshop</option>
                                    <option value="hallmarking" {{ $o->status === 'hallmarking' ? 'selected' : '' }}>Hallmarking</option>
                                    <option value="ready_for_pickup" {{ $o->status === 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                                    <option value="completed" {{ $o->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $o->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 3rem;">No customer orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $orders->links() }}
    </div>
</div>

<!-- Modal: New Custom Order -->
<div class="modal-overlay" id="addOrderModal">
    <div class="modal-card" style="max-width: 680px;">
        <div class="modal-header">
            <h3 class="modal-title">Book New Custom Jewellery Order</h3>
            <button type="button" class="modal-close" onclick="closeModal('addOrderModal')">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.orders.store') }}">
            @csrf
            <div class="modal-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Customer Name</label>
                        <input type="text" name="customer_name" class="form-control" placeholder="e.g. Radhika Merchant" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="customer_phone" class="form-control" placeholder="+91 98765 43210" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Order Type</label>
                        <select name="order_type" class="form-control">
                            <option value="Custom Bridal Making">Custom Bridal Making</option>
                            <option value="Ready Stock Purchase">Ready Stock Purchase</option>
                            <option value="Gold Exchange & Remaking">Gold Exchange & Remaking</option>
                            <option value="Diamond Solitaire Mount">Diamond Solitaire Mount</option>
                            <option value="Repair & Polish">Repair & Polish</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Delivery Due Date</label>
                        <input type="date" name="delivery_due_date" class="form-control" value="{{ now()->addDays(14)->toDateString() }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Items Summary & Design Notes</label>
                    <textarea name="items_summary" rows="2" class="form-control" placeholder="e.g. 22K 916 Polki Choker with Russian Emerald drops, matching earrings" required></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Total Weight (g)</label>
                        <input type="number" step="0.001" name="total_weight" class="form-control" placeholder="0.000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Amount (₹)</label>
                        <input type="number" step="0.01" name="total_amount" class="form-control" placeholder="Total bill amount" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Advance Paid (₹)</label>
                        <input type="number" step="0.01" name="advance_paid" class="form-control" value="0.00" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline-gold" onclick="closeModal('addOrderModal')">Cancel</button>
                <button type="submit" class="btn-gold">Create Order</button>
            </div>
        </form>
    </div>
</div>
@endsection
