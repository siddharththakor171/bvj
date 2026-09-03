@extends('layouts.customer')

@section('title', 'Luxury Jewellery Showroom & Bullion Vault')
@section('meta_description', 'Explore handcrafted 22K/24K BIS hallmarked gold jewellery, solitaire diamonds, uncut polki bridal sets, and investment bullion at B V JEWELLERS.')

@section('content')

<!-- Hero Section -->
<section class="hero-luxury">
    <div class="container-luxury hero-grid">
        <!-- Hero Text Content -->
        <div>
            <div class="hero-badge-pill">
                <span class="sparkle">&#10022;</span>
                Heritage Karigar Atelier Since 1984
            </div>
            
            <h1 class="hero-title">
                Timeless Elegance & <span class="gold-accent">Pure Bullion Trust</span>
            </h1>
            
            <p class="hero-description">
                Explore our curated vault of 22K 916 BIS Hallmarked bridal harams, certified solitaire diamond rings, handcrafted antique polki sets, and 999 fine investment bullion bars.
            </p>
            
            <div class="hero-cta-group">
                <a href="{{ route('catalogue.index') }}" class="btn-gold-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="6 3 18 3 22 9 12 22 2 9 6 3"></polygon></svg>
                    Explore Vault Collection
                </a>
            </div>

            <!-- Trust Metrics -->
            <div class="hero-trust-metrics">
                <div class="trust-metric-item">
                    <div class="number">1984</div>
                    <div class="label">Legacy of Purity</div>
                </div>
                <div class="trust-metric-item">
                    <div class="number">100%</div>
                    <div class="label">BIS HUID Hallmarked</div>
                </div>
                <div class="trust-metric-item">
                    <div class="number">VVS1 / EF</div>
                    <div class="label">Certified Solitaires</div>
                </div>
            </div>
        </div>

        <!-- Hero Showcase Card -->
        <div class="hero-showcase-wrap">
            @if($heroProduct)
                <div class="hero-main-card">
                    <div class="floating-hallmark-badge">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <span>BIS Hallmark Verified</span>
                    </div>

                    @if($heroProduct->image_url)
                        <img src="{{ $heroProduct->image_url }}" alt="{{ $heroProduct->name }}" class="hero-card-image">
                    @else
                        <div class="hero-card-image" style="display: flex; align-items: center; justify-content: center; background: #2a2824; color: var(--gold-bright);">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="6 3 18 3 22 9 12 22 2 9 6 3"></polygon></svg>
                        </div>
                    @endif

                    <div class="hero-card-overlay">
                        <div>
                            <div class="hero-card-tag">{{ $heroProduct->category }} &bull; {{ $heroProduct->purity }}</div>
                            <div class="hero-card-name">{{ $heroProduct->name }}</div>
                        </div>
                        <div>
                            <div class="hero-card-price">₹{{ number_format($heroProduct->calculated_price, 2) }}</div>
                            <a href="{{ route('catalogue.show', $heroProduct->sku) }}" style="font-size: 0.75rem; color: #fef08a; text-decoration: underline; display: block; text-align: right; margin-top: 0.2rem;">
                                View Details &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Live Bullion Rate Board -->
<div class="container-luxury">
    <div class="bullion-widget-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #10b981; box-shadow: 0 0 8px #10b981;"></span>
                <span style="font-family: var(--font-heading); font-size: 0.95rem; font-weight: 700; color: var(--text-primary); letter-spacing: 0.06em; text-transform: uppercase;">
                    Daily Live Bullion & Precious Metal Rates
                </span>
            </div>
            <span style="font-size: 0.76rem; color: var(--text-muted);">
                BIS Standard Daily Benchmarks &bull; Updated: {{ date('d M Y') }}
            </span>
        </div>

        <div class="bullion-grid">
            @foreach($rates as $r)
                <div class="bullion-item" data-live-rate="{{ $r->metal_code }}">
                    <div class="bullion-item-header">
                        <span class="bullion-metal-title">{{ $r->metal_name }}</span>
                        <span class="bullion-purity-tag">{{ $r->purity }}</span>
                    </div>
                    <div class="bullion-item-price" data-live-rate-value>
                        ₹{{ number_format($r->rate_per_gram, 2) }}
                        <span class="unit">/ {{ $r->unit }}</span>
                    </div>
                    <div class="bullion-trend trend-{{ $r->trend }}">
                        @if($r->trend === 'up')
                            &#9650; +₹{{ number_format(abs($r->rate_per_gram - $r->previous_rate), 0) }} vs yesterday
                        @elseif($r->trend === 'down')
                            &#9660; -₹{{ number_format(abs($r->rate_per_gram - $r->previous_rate), 0) }} vs yesterday
                        @else
                            &bull; Stable Market Rate
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Curated Categories Section -->
<section class="section-luxury">
    <div class="container-luxury">
        <div class="section-header">
            <span class="section-subtitle">Curated Creations</span>
            <h2 class="section-title">Explore by Jewellery Category</h2>
            <p class="section-desc">
                From regal bridal harams to certified solitaire rings and 24K pure bullion, discover bespoke pieces straight from our vault.
            </p>
            <div class="gold-divider-center"></div>
        </div>

        <div class="category-cards-grid">
            @foreach($defaultCategories as $catName => $catImg)
                <a href="{{ route('catalogue.index', ['category' => $catName]) }}" class="category-card">
                    <img src="{{ $catImg }}" alt="{{ $catName }}" class="category-card-img" loading="lazy">
                    <div class="category-card-overlay">
                        <span class="category-card-count">
                            {{ $categoryCounts[$catName] ?? 0 }} {{ Str::plural('Piece', $categoryCounts[$catName] ?? 0) }} in Vault
                        </span>
                        <h3 class="category-card-name">{{ $catName }}</h3>
                        <span class="category-card-link-text">
                            View Collection
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Vault Collection Section -->
<section class="section-luxury-alt">
    <div class="container-luxury">
        <div class="section-header">
            <span class="section-subtitle">Vault Highlights</span>
            <h2 class="section-title">Featured Masterpieces</h2>
            <p class="section-desc">
                Handcrafted masterpieces currently available in our showroom vault with complete transparency on purity, hallmark, and metal weights.
            </p>
            <div class="gold-divider-center"></div>
        </div>

        <div class="products-luxury-grid">
            @forelse($featuredProducts as $item)
                <div class="jewel-card">
                    <div class="jewel-card-image-wrap">
                        <div class="jewel-card-badge-top">
                            @if($item->is_featured)
                                <span class="jewel-badge badge-featured">&#10022; Featured</span>
                            @endif
                            @if($item->hallmark_huid)
                                <span class="jewel-badge badge-hallmark">BIS {{ $item->hallmark_huid }}</span>
                            @endif
                        </div>

                        <span class="jewel-status-pill status-{{ $item->status }}">
                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                        </span>

                        @if($item->image_url)
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="jewel-card-image" loading="lazy">
                        @else
                            <div class="jewel-card-fallback-image">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="6 3 18 3 22 9 12 22 2 9 6 3"></polygon></svg>
                                <span style="font-size: 0.72rem; margin-top: 0.5rem; text-transform: uppercase; font-weight: 700;">{{ $item->category }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="jewel-card-body">
                        <div class="jewel-meta-row">
                            <span>{{ $item->category }}</span>
                            <span class="jewel-sku-code">{{ $item->sku }}</span>
                        </div>

                        <h3 class="jewel-card-title">
                            <a href="{{ route('catalogue.show', $item->sku) }}">{{ $item->name }}</a>
                        </h3>

                        <div class="jewel-specs-snippet">
                            <span><strong>Purity:</strong> {{ $item->purity }}</span>
                            <span>&bull;</span>
                            <span><strong>Net:</strong> {{ number_format($item->net_weight, 2) }}g</span>
                        </div>

                        <div class="jewel-card-footer">
                            <div class="jewel-price-block">
                                <span class="jewel-price-label">Price Est.</span>
                                <span class="jewel-price-value">₹{{ number_format($item->calculated_price, 2) }}</span>
                            </div>

                            <div class="jewel-card-actions">
                                <a href="{{ route('catalogue.show', $item->sku) }}" class="btn-card-details">
                                    Details
                                </a>
                                @php
                                    $msg = "Hello B V Jewellers, I would like to enquire about '{$item->name}' (SKU: {$item->sku}).";
                                    $waUrl = "https://wa.me/919876543210?text=" . urlencode($msg);
                                @endphp
                                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="btn-card-whatsapp" title="WhatsApp Enquiry">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--text-muted);">
                    No jewellery pieces currently listed in vault.
                </div>
            @endforelse
        </div>

        <div style="text-align: center; margin-top: 3.5rem;">
            <a href="{{ route('catalogue.index') }}" class="btn-gold-primary">
                View Entire Vault Catalogue ({{ \App\Models\JewelryProduct::count() }} Pieces) &rarr;
            </a>
        </div>
    </div>
</section>

<!-- Brand Heritage & Hallmark Assurance Section -->
<section class="section-luxury-dark">
    <div class="container-luxury">
        <div class="section-header">
            <span class="section-subtitle" style="color: var(--gold-bright);">The BVJ Promise</span>
            <h2 class="section-title">The Standard of Gold Purity & Precision</h2>
            <p class="section-desc">
                Four decades of craftsmanship, ethical bullion sourcing, and 100% laser hallmarking transparency.
            </p>
            <div class="gold-divider-center"></div>
        </div>

        <div class="about-feature-grid">
            <div class="about-feature-card" style="background: var(--onyx-card); border-color: var(--onyx-border);">
                <div class="about-icon-wrap" style="background: rgba(212, 175, 55, 0.12); border-color: rgba(212, 175, 55, 0.3);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                </div>
                <h3 class="about-feature-title" style="color: #ffffff;">100% BIS Hallmarked (HUID)</h3>
                <p class="about-feature-desc" style="color: #a8a29e;">
                    Every single gold jewellery item comes laser-engraved with a unique 6-digit BIS Hallmark Unique Identification (HUID) verifiable on the BIS Care Portal.
                </p>
            </div>

            <div class="about-feature-card" style="background: var(--onyx-card); border-color: var(--onyx-border);">
                <div class="about-icon-wrap" style="background: rgba(212, 175, 55, 0.12); border-color: rgba(212, 175, 55, 0.3);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="6 3 18 3 22 9 12 22 2 9 6 3"></polygon></svg>
                </div>
                <h3 class="about-feature-title" style="color: #ffffff;">Certified Natural Solitaires</h3>
                <p class="about-feature-desc" style="color: #a8a29e;">
                    Our diamond solitaires are ethically sourced and graded under strict VVS1-EF standards with certified grading dossiers and micro-pavé detailing.
                </p>
            </div>

            <div class="about-feature-card" style="background: var(--onyx-card); border-color: var(--onyx-border);">
                <div class="about-icon-wrap" style="background: rgba(212, 175, 55, 0.12); border-color: rgba(212, 175, 55, 0.3);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </div>
                <h3 class="about-feature-title" style="color: #ffffff;">Master Karigar Workshop</h3>
                <p class="about-feature-desc" style="color: #a8a29e;">
                    Our in-house master artisans specialize in Jadau, uncut Polki, antique temple finish, and bespoke bridal neckwear tailored to family heirloom traditions.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="section-luxury" style="background: linear-gradient(135deg, #fbf9f4 0%, #f4ede1 100%); border-top: 1px solid var(--gold-border); border-bottom: 1px solid var(--gold-border);">
    <div class="container-luxury" style="text-align: center; max-width: 800px;">
        <span class="section-subtitle">Personalized Showroom Consultation</span>
        <h2 class="section-title" style="font-size: 2.3rem;">Experience BVJ Craftsmanship in Person</h2>
        <p class="section-desc" style="margin-bottom: 2rem;">
            Whether you are curating a wedding trousseau, looking for bespoke temple jewellery, or acquiring assay-minted bullion bars, our private consultants are at your service.
        </p>

        <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
            <a href="https://wa.me/919876543210?text={{ urlencode('Hello B V Jewellers, I would like to schedule a private showroom consultation.') }}" target="_blank" rel="noopener noreferrer" class="btn-whatsapp-large">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                Connect on WhatsApp (+91 98765 43210)
            </a>
            <a href="{{ route('contact') }}" class="btn-gold-outline">
                Visit Showroom Location
            </a>
        </div>
    </div>
</section>

@endsection
