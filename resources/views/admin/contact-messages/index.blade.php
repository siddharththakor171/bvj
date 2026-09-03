@extends('layouts.admin')

@section('title', 'Contact Messages')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Contact Messages</h1>
        <p class="page-subtitle">View and follow up on enquiries submitted from the showroom contact form.</p>
    </div>
</div>

<div class="gold-card">
    <form method="GET" action="{{ route('admin.contact-messages.index') }}" style="display: flex; gap: 1rem; align-items: flex-end; margin-bottom: 1.5rem;">
        <div class="form-group" style="margin: 0; max-width: 220px;">
            <label class="form-label" for="status">Filter by status</label>
            <select name="status" id="status" class="form-control">
                <option value="">All messages</option>
                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="appointment_booked" {{ request('status') === 'appointment_booked' ? 'selected' : '' }}>Appointment booked</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>
        <button type="submit" class="btn-gold">Filter</button>
        <a href="{{ route('admin.contact-messages.index') }}" class="btn-outline-gold">Reset</a>
    </form>

    <div class="table-responsive">
        <table class="luxury-table">
            <thead>
                <tr>
                    <th>Received</th>
                    <th>Customer</th>
                    <th>Contact</th>
                    <th>Interest</th>
                    <th>Message</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                    <tr>
                        <td style="white-space: nowrap;">{{ $message->created_at->format('d M Y, h:i A') }}</td>
                        <td style="font-weight: 700;">{{ $message->customer_name }}</td>
                        <td>
                            <a href="tel:{{ $message->customer_phone }}">{{ $message->customer_phone }}</a>
                            @if($message->customer_email)
                                <br><a href="mailto:{{ $message->customer_email }}">{{ $message->customer_email }}</a>
                            @endif
                        </td>
                        <td>{{ $message->interested_category }}</td>
                        <td style="max-width: 280px;">{{ $message->message ?: 'No message provided.' }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.contact-messages.status', $message) }}">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="form-control" style="min-width: 150px;">
                                    <option value="new" {{ $message->status === 'new' ? 'selected' : '' }}>New</option>
                                    <option value="contacted" {{ $message->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                    <option value="appointment_booked" {{ $message->status === 'appointment_booked' ? 'selected' : '' }}>Appointment booked</option>
                                    <option value="closed" {{ $message->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-muted);">No contact messages found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $messages->links() }}
    </div>
</div>
@endsection
