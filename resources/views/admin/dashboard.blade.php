@extends('layouts.admin')

@section('title', 'Executive Dashboard')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Jewellery Vault & Executive Dashboard</h1>
        <p class="page-subtitle">Welcome back, {{ Auth::user()->name }}. Real-time bullion metrics and jewellery operations overview.</p>
    </div>
    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
        <button type="button" class="btn-outline-gold" onclick="openModal('quickRateModal')">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
            Update Live Rates
        </button>
        <a href="{{ route('admin.products.index') }}" class="btn-gold">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Jewellery Item
        </a>
    </div>
</div>

<!-- Daily Metal & Bullion Rate Board -->
<div style="margin-bottom: 1.75rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <div class="live-dot"></div>
            <span style="font-size: 0.78rem; font-weight: 700; color: var(--gold-primary); text-transform: uppercase; letter-spacing: 0.1em;">Today's Live Bullion Board</span>
        </div>
        <span style="font-size: 0.72rem; color: var(--text-muted);">Updated: {{ date('d M Y') }} &bull; Market Standard</span>
    </div>

    <div class="rates-grid">
        @foreach($rates as $rate)
            <div class="rate-box">
                <div class="rate-box-header">
                    <span class="rate-metal-name">{{ $rate->metal_name }}</span>
                    <span class="rate-purity">{{ $rate->purity }}</span>
                </div>
                <div class="rate-price">
                    ₹{{ number_format($rate->rate_per_gram, 2) }}
                    <span class="rate-unit">/ {{ $rate->unit }}</span>
                </div>
                <div class="rate-trend-badge trend-{{ $rate->trend }}">
                    @if($rate->trend === 'up')
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>
                        +₹{{ number_format(abs($rate->rate_per_gram - $rate->previous_rate), 0) }} vs yesterday
                    @elseif($rate->trend === 'down')
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        -₹{{ number_format(abs($rate->rate_per_gram - $rate->previous_rate), 0) }} vs yesterday
                    @else
                        <span>&bull;</span> Steady Market
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Key Performance Stat Cards Grid -->
<div class="stats-grid">
    <!-- Stat 1: Total Vault Valuation -->
    <div class="gold-card stat-card">
        <div class="stat-info">
            <div class="stat-label">Total Vault Valuation</div>
            <div class="stat-value">₹{{ number_format($totalInventoryValue / 100000, 2) }} <span style="font-size: 1rem; color: var(--gold-primary);">Lakhs</span></div>
            <div class="stat-meta">
                <span style="color: var(--color-success); font-weight: 600;">&#9650; 8.4%</span> across {{ $totalProducts }} catalog items
            </div>
        </div>
        <div class="stat-icon-wrapper">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="6 3 18 3 22 9 12 22 2 9 6 3"></polygon>
            </svg>
        </div>
    </div>

    <!-- Stat 2: Active Gold Bullion Stock -->
    <div class="gold-card stat-card">
        <div class="stat-info">
            <div class="stat-label">Vault Gold Weight</div>
            <div class="stat-value">{{ number_format($totalGoldWeight, 1) }} <span style="font-size: 1rem; color: var(--gold-primary);">g</span></div>
            <div class="stat-meta">
                <span>Silver Vault: {{ number_format($totalSilverWeight, 0) }} g</span>
            </div>
        </div>
        <div class="stat-icon-wrapper">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
            </svg>
        </div>
    </div>

    <!-- Stat 3: Custom Bridal Orders -->
    <div class="gold-card stat-card">
        <div class="stat-info">
            <div class="stat-label">Custom Orders In-Workshop</div>
            <div class="stat-value">{{ $activeOrders }}</div>
            <div class="stat-meta">
                <span style="color: var(--gold-primary); font-weight: 600;">Karigar Stage: Casting & Polishing</span>
            </div>
        </div>
        <div class="stat-icon-wrapper">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <path d="M16 10a4 4 0 0 1-8 0"></path>
            </svg>
        </div>
    </div>

    <!-- Stat 4: VIP Inquiries -->
    <div class="gold-card stat-card">
        <div class="stat-info">
            <div class="stat-label">VIP Inquiries & Consultations</div>
            <div class="stat-value">{{ $pendingInquiries }}</div>
            <div class="stat-meta">
                <span style="color: var(--color-success); font-weight: 600;">Bridal & Solitaire requests</span>
            </div>
        </div>
        <div class="stat-icon-wrapper">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
    </div>
</div>

<!-- Main Dashboard Grid (Charts & Tables) -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.75rem; margin-bottom: 1.75rem;" class="dashboard-split-grid">
    <!-- Left Column: Sales Trend & Category Distribution -->
    <div class="gold-card">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
            <div>
                <h3 style="font-size: 1.1rem; color: #1f1c18;">Monthly Sales & Bullion Turnover</h3>
                <p style="font-size: 0.78rem; color: var(--text-muted);">Gold, Diamond, & Polki volume in INR</p>
            </div>
            <span class="badge badge-in_stock">Growth +14.2%</span>
        </div>

        <!-- Custom Luxury SVG Chart (Light Mode) -->
        <div style="width: 100%; height: 220px; position: relative; margin-bottom: 1rem;">
            <svg viewBox="0 0 600 200" style="width: 100%; height: 100%; overflow: visible;">
                <defs>
                    <linearGradient id="goldAreaGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#d4af37" stop-opacity="0.3"/>
                        <stop offset="100%" stop-color="#d4af37" stop-opacity="0.0"/>
                    </linearGradient>
                    <linearGradient id="goldLineGrad" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="0%" stop-color="#b8860b"/>
                        <stop offset="100%" stop-color="#784d09"/>
                    </linearGradient>
                </defs>

                <!-- Grid lines -->
                <line x1="0" y1="40" x2="600" y2="40" stroke="#f0ebd9" stroke-dasharray="4"/>
                <line x1="0" y1="90" x2="600" y2="90" stroke="#f0ebd9" stroke-dasharray="4"/>
                <line x1="0" y1="140" x2="600" y2="140" stroke="#f0ebd9" stroke-dasharray="4"/>
                <line x1="0" y1="190" x2="600" y2="190" stroke="#e6dfcc"/>

                <!-- Area Fill -->
                <path d="M 0,160 Q 100,120 200,140 T 400,60 T 600,30 L 600,190 L 0,190 Z" fill="url(#goldAreaGrad)"/>
                
                <!-- Stroke Line -->
                <path d="M 0,160 Q 100,120 200,140 T 400,60 T 600,30" fill="none" stroke="url(#goldLineGrad)" stroke-width="3" stroke-linecap="round"/>

                <!-- Data Dots -->
                <circle cx="0" cy="160" r="4" fill="#b8860b"/>
                <circle cx="100" cy="130" r="4" fill="#b8860b"/>
                <circle cx="200" cy="140" r="4" fill="#b8860b"/>
                <circle cx="300" cy="95" r="4" fill="#b8860b"/>
                <circle cx="400" cy="60" r="4" fill="#b8860b"/>
                <circle cx="500" cy="45" r="4" fill="#b8860b"/>
                <circle cx="600" cy="30" r="5" fill="#ffffff" stroke="#b8860b" stroke-width="3"/>
            </svg>
        </div>

        <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: var(--text-muted); border-top: 1px solid var(--border-subtle); padding-top: 0.75rem; font-weight: 600;">
            <span>Apr</span><span>May</span><span>Jun</span><span>Jul</span><span>Aug</span><span>Sep (Peak Bridal)</span>
        </div>
    </div>

    <!-- Right Column: Inventory Category Breakdown -->
    <div class="gold-card">
        <h3 style="font-size: 1.1rem; color: #1f1c18; margin-bottom: 0.25rem;">Vault Breakdown</h3>
        <p style="font-size: 0.78rem; color: var(--text-muted); margin-bottom: 1.25rem;">Inventory distribution by department</p>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @foreach($categoriesBreakdown as $cat)
                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.82rem; margin-bottom: 0.35rem;">
                        <span style="color: #292524; font-weight: 600;">{{ $cat->category }}</span>
                        <span style="color: var(--gold-primary); font-weight: 700;">₹{{ number_format($cat->total_val / 1000, 0) }}k ({{ $cat->count }})</span>
                    </div>
                    <div style="width: 100%; height: 7px; background: #f5f1e6; border-radius: 999px; overflow: hidden;">
                        <div style="width: {{ min(100, ($cat->total_val / ($totalInventoryValue ?: 1)) * 100) }}%; height: 100%; background: var(--gold-gradient); border-radius: 999px;"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Lower Section: Recent Orders & Inquiries -->
<div style="display: grid; grid-template-columns: 3fr 2fr; gap: 1.75rem;" class="dashboard-lower-grid">
    <!-- Recent Custom Orders Table -->
    <div class="gold-card">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
            <div>
                <h3 style="font-size: 1.1rem; color: #1f1c18;">Recent Customer Orders</h3>
                <p style="font-size: 0.78rem; color: var(--text-muted);">Custom bridal commissions and ready stock purchases</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn-outline-gold btn-sm">View All Orders</a>
        </div>

        <div class="table-responsive">
            <table class="luxury-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Summary</th>
                        <th>Total (₹)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td style="font-family: monospace; color: var(--gold-primary); font-weight: 700;">{{ $order->order_number }}</td>
                            <td>
                                <div style="font-weight: 700; color: #1f1c18;">{{ $order->customer_name }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $order->customer_phone }}</div>
                            </td>
                            <td style="font-size: 0.8rem; color: #44403c; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $order->items_summary }}</td>
                            <td style="font-weight: 800; color: #1f1c18;">₹{{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $order->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">No orders registered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent VIP Consultations & Inquiries -->
    <div class="gold-card">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
            <div>
                <h3 style="font-size: 1.1rem; color: #1f1c18;">VIP Consultations</h3>
                <p style="font-size: 0.78rem; color: var(--text-muted);">High-value bridal & diamond appointment requests</p>
            </div>
            <a href="{{ route('admin.inquiries.index') }}" class="btn-outline-gold btn-sm">Manage</a>
        </div>

        <div style="display: flex; flex-direction: column; gap: 0.85rem;">
            @forelse($recentInquiries as $inq)
                <div style="background: #fbf9f4; border: 1px solid var(--border-subtle); border-radius: 8px; padding: 0.85rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                        <span style="font-weight: 700; color: #1f1c18; font-size: 0.9rem;">{{ $inq->customer_name }}</span>
                        <span class="badge badge-{{ $inq->status }}">{{ ucfirst(str_replace('_', ' ', $inq->status)) }}</span>
                    </div>
                    <div style="font-size: 0.78rem; color: var(--gold-primary); font-weight: 600; margin-bottom: 0.3rem;">
                        Interest: {{ $inq->interested_category }} (Budget: {{ $inq->budget_range ?? 'Unspecified' }})
                    </div>
                    <div style="font-size: 0.78rem; color: #57534e; line-height: 1.4;">
                        "{{ Str::limit($inq->message, 80) }}"
                    </div>
                </div>
            @empty
                <div style="text-align: center; color: var(--text-muted); padding: 1.5rem;">No active inquiries.</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal: Quick Update Live Bullion Rates -->
<div class="modal-overlay" id="quickRateModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
                Update Live Bullion Rates
            </h3>
            <button type="button" class="modal-close" onclick="closeModal('quickRateModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1.25rem;">
                Enter today's opening market rate per gram. 10g rate and pricing multipliers will be updated across all jewellery estimates automatically.
            </p>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($rates as $r)
                    <form method="POST" action="{{ route('admin.rates.update', $r->id) }}" style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; background: #fdfbf7; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-subtle);">
                        @csrf
                        @method('PUT')
                        <div style="flex: 1;">
                            <div style="font-weight: 700; color: #1f1c18; font-size: 0.88rem;">{{ $r->metal_name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">Current: ₹{{ number_format($r->rate_per_gram, 2) }}/{{ $r->unit }}</div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="number" step="0.01" name="rate_per_gram" value="{{ $r->rate_per_gram }}" class="form-control" style="width: 120px; padding: 0.4rem 0.6rem; font-size: 0.85rem;" required>
                            <button type="submit" class="btn-gold btn-sm">Update</button>
                        </div>
                    </form>
                @endforeach
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-outline-gold btn-sm" onclick="closeModal('quickRateModal')">Close</button>
        </div>
    </div>
</div>

<style>
@media (max-width: 1024px) {
    .dashboard-split-grid, .dashboard-lower-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
