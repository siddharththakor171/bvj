@extends('layouts.admin')

@section('title', 'VIP Inquiries & Consultations')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">VIP Client Consultations & Inquiries</h1>
        <p class="page-subtitle">Track private lounge viewings, bridal consultations, and bespoke jewellery requests.</p>
    </div>
    <div>
        <button type="button" class="btn-gold" onclick="openModal('addInquiryModal')">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Log New Consultation
        </button>
    </div>
</div>

<!-- Inquiries Grid / Table -->
<div class="gold-card">
    <div class="table-responsive">
        <table class="luxury-table">
            <thead>
                <tr>
                    <th>Inquiry #</th>
                    <th>Client Name & Phone</th>
                    <th>Interest & Category</th>
                    <th>Budget Range</th>
                    <th>Notes / Consultation Request</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inquiries as $inq)
                    <tr>
                        <td style="font-family: monospace; color: var(--gold-light); font-weight: 600;">
                            {{ $inq->inquiry_number }}
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #fff;">{{ $inq->customer_name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $inq->customer_phone }}</div>
                        </td>
                        <td>
                            <span style="color: var(--gold-light); font-weight: 600;">{{ $inq->interested_category }}</span>
                        </td>
                        <td>
                            <span style="color: #fff; font-weight: 600;">{{ $inq->budget_range ?? 'Not specified' }}</span>
                        </td>
                        <td style="font-size: 0.82rem; color: #cbd5e1; max-width: 250px;">
                            "{{ $inq->message }}"
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.inquiries.status', $inq->id) }}">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="form-control" style="padding: 0.3rem 0.5rem; font-size: 0.75rem; width: auto; background: rgba(10,12,16,0.9); border-color: var(--border-color);">
                                    <option value="new" {{ $inq->status === 'new' ? 'selected' : '' }}>New Lead</option>
                                    <option value="contacted" {{ $inq->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                    <option value="appointment_booked" {{ $inq->status === 'appointment_booked' ? 'selected' : '' }}>Appointment Booked</option>
                                    <option value="converted" {{ $inq->status === 'converted' ? 'selected' : '' }}>Converted to Order</option>
                                    <option value="closed" {{ $inq->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 3rem;">No VIP inquiries currently recorded.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $inquiries->links() }}
    </div>
</div>

<!-- Modal: Add Inquiry -->
<div class="modal-overlay" id="addInquiryModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title">Log VIP Client Consultation</h3>
            <button type="button" class="modal-close" onclick="closeModal('addInquiryModal')">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.inquiries.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Client Full Name</label>
                    <input type="text" name="customer_name" class="form-control" placeholder="e.g. Princess Shobhana Devi" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="customer_phone" class="form-control" placeholder="+91 98765 00000" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email (Optional)</label>
                        <input type="email" name="customer_email" class="form-control" placeholder="client@example.com">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Interested Category</label>
                        <input type="text" name="interested_category" class="form-control" placeholder="e.g. Polki Bridal Set, Solitaire Ring" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Budget Range</label>
                        <input type="text" name="budget_range" class="form-control" placeholder="e.g. ₹5,00,000 - ₹10,00,000">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Consultation Notes & Preferences</label>
                    <textarea name="message" rows="3" class="form-control" placeholder="Client requested private vault appointment on weekend for bridal jewellery selection..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline-gold" onclick="closeModal('addInquiryModal')">Cancel</button>
                <button type="submit" class="btn-gold">Save Consultation Lead</button>
            </div>
        </form>
    </div>
</div>
@endsection
