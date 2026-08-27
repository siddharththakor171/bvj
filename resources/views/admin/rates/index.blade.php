@extends('layouts.admin')

@section('title', 'Bullion & Live Rates')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Daily Bullion & Live Metal Rates</h1>
        <p class="page-subtitle">Configure daily market rates and simulate real-time jewellery retail valuations.</p>
    </div>
</div>

<!-- Bullion Rates Grid with inline update capability -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    @foreach($rates as $rate)
        <div class="gold-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div>
                    <h3 style="font-size: 1.1rem; color: #1f1c18;">{{ $rate->metal_name }}</h3>
                    <span style="font-size: 0.75rem; color: var(--gold-primary); font-weight: 600;">Purity: {{ $rate->purity }}</span>
                </div>
                <span class="badge badge-in_stock">Active</span>
            </div>

            <div style="background: #fbf9f4; border: 1px solid var(--border-subtle); border-radius: 8px; padding: 1rem; margin-bottom: 1.25rem;">
                <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.78rem; color: var(--text-muted);">Rate per {{ $rate->unit }}:</span>
                    <span style="font-size: 1.5rem; font-weight: 800; color: #1f1c18; font-family: var(--font-heading);">
                        ₹{{ number_format($rate->rate_per_gram, 2) }}
                    </span>
                </div>
                @if($rate->rate_per_10g)
                    <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-muted); border-top: 1px solid var(--border-subtle); padding-top: 0.5rem;">
                        <span>Rate per 10 Grams / Tola:</span>
                        <span style="color: var(--gold-primary); font-weight: 700;">₹{{ number_format($rate->rate_per_10g, 2) }}</span>
                    </div>
                @endif
            </div>

            <!-- Update Form -->
            <form method="POST" action="{{ route('admin.rates.update', $rate->id) }}">
                @csrf
                @method('PUT')
                <div style="display: flex; gap: 0.75rem;">
                    <input 
                        type="number" 
                        step="0.01" 
                        name="rate_per_gram" 
                        value="{{ $rate->rate_per_gram }}" 
                        class="form-control" 
                        placeholder="New rate per {{ $rate->unit }}" 
                        required
                    >
                    <button type="submit" class="btn-gold" style="white-space: nowrap;">Update Rate</button>
                </div>
            </form>
        </div>
    @endforeach
</div>

<!-- Interactive Jewelry Price Estimator Calculator -->
<div class="gold-card">
    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
        <div class="stat-icon-wrapper" style="width: 42px; height: 42px;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="4" y="2" width="16" height="20" rx="2"></rect>
                <line x1="8" y1="6" x2="16" y2="6"></line>
                <line x1="16" y1="14" x2="16" y2="18"></line>
                <path d="M16 10h.01"></path>
                <path d="M12 10h.01"></path>
                <path d="M8 10h.01"></path>
                <path d="M12 14h.01"></path>
                <path d="M8 14h.01"></path>
                <path d="M12 18h.01"></path>
                <path d="M8 18h.01"></path>
            </svg>
        </div>
        <div>
            <h2 style="font-size: 1.25rem; color: #1f1c18;">Counter Price Estimator & Billing Simulator</h2>
            <p style="font-size: 0.8rem; color: var(--text-muted);">Simulate instant customer quotations with live metal rates, making charges, and statutory 3% GST.</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;" class="calc-grid">
        <!-- Calculator Inputs -->
        <div>
            <div class="form-group">
                <label class="form-label">Select Metal & Purity</label>
                <select id="calcMetalSelect" class="form-control" onchange="recalcPrice()">
                    @foreach($rates as $r)
                        <option value="{{ $r->rate_per_gram }}" data-name="{{ $r->metal_name }}">{{ $r->metal_name }} (₹{{ number_format($r->rate_per_gram, 0) }}/g)</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Net Metal Weight (Grams)</label>
                <input type="number" step="0.001" id="calcWeight" class="form-control" value="18.500" oninput="recalcPrice()">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Making Charge (%)</label>
                    <input type="number" step="0.1" id="calcMakingPercent" class="form-control" value="12.5" oninput="recalcPrice()">
                </div>
                <div class="form-group">
                    <label class="form-label">Stone / Diamond (₹)</label>
                    <input type="number" id="calcStoneVal" class="form-control" value="0" oninput="recalcPrice()">
                </div>
            </div>
        </div>

        <!-- Valuation Breakdown Card -->
        <div style="background: #fbf9f4; border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <h4 style="font-size: 0.95rem; color: var(--gold-primary); margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.08em; font-weight: 700;">Quotation Breakdown</h4>

                <div style="display: flex; flex-direction: column; gap: 0.65rem; font-size: 0.88rem;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Metal Value:</span>
                        <span id="outMetalVal" style="color: #1f1c18; font-weight: 700;">₹0.00</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Making Charges:</span>
                        <span id="outMakingVal" style="color: #1f1c18; font-weight: 700;">₹0.00</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Stone / Diamond Value:</span>
                        <span id="outStoneVal" style="color: #1f1c18; font-weight: 700;">₹0.00</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Hallmarking BIS Fee:</span>
                        <span style="color: #1f1c18; font-weight: 700;">₹45.00</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-top: 1px dashed var(--border-subtle); padding-top: 0.5rem;">
                        <span style="color: var(--text-muted);">GST (3%):</span>
                        <span id="outGstVal" style="color: #1f1c18; font-weight: 700;">₹0.00</span>
                    </div>
                </div>
            </div>

            <div style="border-top: 1px solid var(--border-color); padding-top: 1rem; margin-top: 1rem;">
                <div style="font-size: 0.75rem; color: var(--gold-primary); text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700;">Estimated Retail Price</div>
                <div id="outTotalPrice" style="font-size: 2rem; font-weight: 800; color: #1f1c18; font-family: var(--font-heading);">₹0.00</div>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .calc-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>

@push('scripts')
<script>
    function recalcPrice() {
        const rate = parseFloat(document.getElementById('calcMetalSelect').value) || 0;
        const weight = parseFloat(document.getElementById('calcWeight').value) || 0;
        const makingPercent = parseFloat(document.getElementById('calcMakingPercent').value) || 0;
        const stoneVal = parseFloat(document.getElementById('calcStoneVal').value) || 0;
        const hallmarkFee = 45.00;

        const metalVal = rate * weight;
        const makingVal = metalVal * (makingPercent / 100);
        const subtotal = metalVal + makingVal + stoneVal + hallmarkFee;
        const gstVal = subtotal * 0.03;
        const total = subtotal + gstVal;

        document.getElementById('outMetalVal').textContent = '₹' + metalVal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('outMakingVal').textContent = '₹' + makingVal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('outStoneVal').textContent = '₹' + stoneVal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('outGstVal').textContent = '₹' + gstVal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('outTotalPrice').textContent = '₹' + Math.round(total).toLocaleString('en-IN');
    }

    document.addEventListener('DOMContentLoaded', recalcPrice);
</script>
@endpush
@endsection
