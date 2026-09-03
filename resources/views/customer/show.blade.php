@extends('layouts.customer')

@section('title', $product->name . ' (' . $product->sku . ')')
@section('meta_description', 'View complete craftsmanship specifications for ' . $product->name . ' - 100% BIS Hallmarked ' . $product->purity . ' ' . $product->metal_type . ' jewellery at B V JEWELLERS.')

@section('content')

<div class="container-luxury" style="padding-top: 2.5rem; padding-bottom: 5rem;">
    <!-- Breadcrumbs -->
    <div class="product-breadcrumbs">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <a href="{{ route('catalogue.index') }}">Catalogue</a>
        <span>/</span>
        <a href="{{ route('catalogue.index', ['category' => $product->category]) }}">{{ $product->category }}</a>
        <span>/</span>
        <span style="color: var(--text-primary); font-weight: 600;">{{ $product->sku }}</span>
    </div>

    <!-- Product Details Grid -->
    <div class="product-detail-grid">
        <!-- Left: Gallery & Certifications -->
        <div>
            <div class="product-gallery-card">
                <div class="product-gallery-main">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" id="mainProductImage">
                    @else
                        <div class="jewel-card-fallback-image" style="height: 100%;">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="6 3 18 3 22 9 12 22 2 9 6 3"></polygon></svg>
                            <span style="font-size: 0.9rem; margin-top: 0.75rem; text-transform: uppercase; font-weight: 700;">{{ $product->category }}</span>
                        </div>
                    @endif
                </div>

                <div class="product-badges-row">
                    @if($product->is_featured)
                        <span class="jewel-badge badge-featured">&#10022; Featured Masterpiece</span>
                    @endif
                    @if($product->hallmark_huid)
                        <span class="jewel-badge badge-hallmark">BIS Hallmark: {{ $product->hallmark_huid }}</span>
                    @endif
                    <span class="jewel-badge" style="background: var(--bg-page); border: 1px solid var(--gold-border); color: var(--gold-primary);">
                        {{ $product->purity }}
                    </span>
                </div>
            </div>

            <!-- BIS Hallmark Authenticity Callout -->
            <div class="hallmark-cert-box" style="margin-top: 1.5rem;">
                <div class="hallmark-icon-crest">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L3 7v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-9-5zm-2 16l-4-4 1.41-1.41L10 15.17l6.59-6.59L18 10l-8 8z"/></svg>
                </div>
                <div>
                    <div class="hallmark-cert-title">Bureau of Indian Standards (BIS) Verified</div>
                    <div class="hallmark-cert-sub">
                        @if($product->hallmark_huid)
                            HUID: <strong style="font-family: monospace; color: var(--gold-primary);">{{ $product->hallmark_huid }}</strong> &bull;
                        @endif
                        100% XRF Laser Assay & Bullion Hallmarked.
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Specifications & Inquiries -->
        <div class="product-info-content">
            <div class="product-detail-sku">{{ $product->sku }}</div>
            <h1 class="product-detail-title">{{ $product->name }}</h1>

            <!-- Price & Availability Box -->
            <div class="product-detail-price-box">
                <div class="price-box-left">
                    <span class="est-label">Estimated Showroom Price</span>
                    <div class="price-amount">₹{{ number_format($product->calculated_price, 2) }}</div>
                    <span class="tax-note">Indicative market valuation (Incl. 3% GST & Making Charges)</span>
                </div>

                <div>
                    <span class="status-badge-large status-{{ $product->status }}">
                        {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                        @if($product->stock_quantity > 0)
                            ({{ $product->stock_quantity }} Available)
                        @endif
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="product-actions-grid">
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="btn-whatsapp-large" style="width: 100%;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    Enquire on WhatsApp
                </a>

                <a href="tel:+919876543210" class="btn-gold-outline" style="width: 100%;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    Call Showroom
                </a>
            </div>

            <div style="margin-bottom: 1.75rem;">
                <button type="button" class="btn-gold-primary" style="width: 100%;" onclick="openConsultationModal('{{ $product->category }}', 'Enquiry for: {{ addslashes($product->name) }} (SKU: {{ $product->sku }})')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Book VIP Showroom Viewing
                </button>
            </div>

            <!-- Complete Vault Specifications Table -->
            <div class="specs-card">
                <div class="specs-card-header">
                    Exact Vault Craftsmanship Specifications
                </div>
                <table class="specs-table">
                    <tbody>
                        <tr>
                            <td class="spec-label">Product / SKU Code</td>
                            <td class="spec-value" style="font-family: monospace; color: var(--gold-primary);">{{ $product->sku }}</td>
                        </tr>
                        <tr>
                            <td class="spec-label">Category</td>
                            <td class="spec-value">{{ $product->category }}</td>
                        </tr>
                        <tr>
                            <td class="spec-label">Metal / Craft Type</td>
                            <td class="spec-value">{{ $product->metal_type }}</td>
                        </tr>
                        <tr>
                            <td class="spec-label">Gold Purity & Fineness</td>
                            <td class="spec-value">{{ $product->purity }}</td>
                        </tr>
                        <tr>
                            <td class="spec-label">Gross Weight</td>
                            <td class="spec-value">{{ number_format($product->gross_weight, 3) }} Grams</td>
                        </tr>
                        <tr>
                            <td class="spec-label">Net Metal Weight</td>
                            <td class="spec-value">{{ number_format($product->net_weight, 3) }} Grams</td>
                        </tr>
                        @if((float)$product->stone_weight_carat > 0 || !empty($product->stone_type))
                            <tr>
                                <td class="spec-label">Stone / Diamond Specs</td>
                                <td class="spec-value">
                                    {{ $product->stone_type ?: 'Natural Diamonds' }}
                                    @if((float)$product->stone_weight_carat > 0)
                                        ({{ number_format($product->stone_weight_carat, 3) }} Carats)
                                    @endif
                                </td>
                            </tr>
                        @endif
                        @if($product->hallmark_huid)
                            <tr>
                                <td class="spec-label">BIS Hallmark HUID</td>
                                <td class="spec-value">
                                    <span style="font-family: monospace; font-weight: 800; color: #1e3a8a; background: #eff6ff; padding: 0.2rem 0.5rem; border-radius: 4px; border: 1px solid #bfdbfe;">
                                        {{ $product->hallmark_huid }}
                                    </span>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td class="spec-label">Making Charge Rate</td>
                            <td class="spec-value">
                                {{ $product->making_charge_percent }}%
                                @if((float)$product->making_charge_fixed > 0)
                                    + ₹{{ number_format($product->making_charge_fixed, 2) }} fixed
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="spec-label">Vault Inventory Status</td>
                            <td class="spec-value">
                                <span class="jewel-status-pill status-{{ $product->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Karigar Description / Craft Notes -->
            @if($product->description)
                <div class="product-desc-box">
                    <h4>Artisan Craftsmanship & Karigar Notes</h4>
                    <p>{{ $product->description }}</p>
                </div>
            @endif

            <!-- Showroom Visit Card -->
            <div style="background: var(--bg-surface); border: 1px solid var(--gold-border-light); border-radius: var(--radius-md); padding: 1.25rem; font-size: 0.84rem; color: var(--text-secondary);">
                <div style="font-weight: 700; color: var(--text-primary); margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.4rem;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    Showroom Viewing & Product Enquiries
                </div>
                <div>
                    Available for viewing at <strong>B V JEWELLERS Showroom, Zaveri Bazaar, Mumbai</strong>. Custom modifications in metal purity, size, or gemstone settings can be crafted at our atelier.
                </div>
            </div>
        </div>
    </div>

    <!-- Related Jewellery Pieces -->
    @if($relatedProducts->isNotEmpty())
        <div style="margin-top: 5rem; padding-top: 3.5rem; border-top: 1px solid var(--gold-border-light);">
            <div class="section-header" style="margin-bottom: 2.5rem;">
                <span class="section-subtitle">Similar Creations</span>
                <h2 class="section-title" style="font-size: 1.85rem;">More in {{ $product->category }}</h2>
                <div class="gold-divider-center"></div>
            </div>

            <div class="products-luxury-grid">
                @foreach($relatedProducts as $rel)
                    <div class="jewel-card">
                        <div class="jewel-card-image-wrap">
                            <span class="jewel-status-pill status-{{ $rel->status }}">
                                {{ ucfirst(str_replace('_', ' ', $rel->status)) }}
                            </span>

                            @if($rel->image_url)
                                <img src="{{ $rel->image_url }}" alt="{{ $rel->name }}" class="jewel-card-image" loading="lazy">
                            @else
                                <div class="jewel-card-fallback-image">
                                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="6 3 18 3 22 9 12 22 2 9 6 3"></polygon></svg>
                                </div>
                            @endif
                        </div>

                        <div class="jewel-card-body">
                            <div class="jewel-meta-row">
                                <span>{{ $rel->category }}</span>
                                <span class="jewel-sku-code">{{ $rel->sku }}</span>
                            </div>

                            <h3 class="jewel-card-title">
                                <a href="{{ route('catalogue.show', $rel->sku) }}">{{ $rel->name }}</a>
                            </h3>

                            <div class="jewel-specs-snippet">
                                <span><strong>Purity:</strong> {{ $rel->purity }}</span>
                                <span>&bull;</span>
                                <span><strong>Net:</strong> {{ number_format($rel->net_weight, 2) }}g</span>
                            </div>

                            <div class="jewel-card-footer">
                                <div class="jewel-price-block">
                                    <span class="jewel-price-label">Price Est.</span>
                                    <span class="jewel-price-value">₹{{ number_format($rel->calculated_price, 2) }}</span>
                                </div>

                                <div class="jewel-card-actions">
                                    <a href="{{ route('catalogue.show', $rel->sku) }}" class="btn-card-details">
                                        Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@endsection
