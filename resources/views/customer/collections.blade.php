@extends('layouts.customer')

@section('title', 'Curated Jewellery Collections & Vault Categories')
@section('meta_description', 'Explore bespoke collections at B V JEWELLERS: Royal Bridal Harams, Solitaire Diamonds, Heritage Bangles, Pure Silverware, and Minted Bullion.')

@section('content')

<!-- Header Banner -->
<div style="background: radial-gradient(circle at 50% 50%, rgba(212, 175, 55, 0.08) 0%, rgba(18, 17, 14, 0.98) 100%), var(--onyx-deep); color: #ffffff; padding: 4rem 0; border-bottom: 1px solid var(--onyx-border);">
    <div class="container-luxury" style="text-align: center;">
        <span class="section-subtitle" style="color: var(--gold-bright);">Atelier Horizons</span>
        <h1 class="section-title" style="color: #ffffff; font-size: 2.85rem; margin-bottom: 0.75rem;">
            Curated Jewellery Collections
        </h1>
        <p class="section-desc" style="max-width: 620px; margin: 0 auto; color: #c4bfa5;">
            Explore our heritage categories crafted in 22K/24K hallmarked gold, certified diamonds, and pure investment bullion.
        </p>
    </div>
</div>

<div class="container-luxury" style="padding-top: 4rem; padding-bottom: 6rem;">
    <div class="category-cards-grid" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem;">
        @foreach($categoriesWithCounts as $cat)
            @php
                $img = $cat->sample_image ?: ($defaultImages[$cat->category] ?? 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=600&auto=format&fit=crop&q=80');
            @endphp
            <a href="{{ route('catalogue.index', ['category' => $cat->category]) }}" class="category-card" style="aspect-ratio: 3/4;">
                <img src="{{ $img }}" alt="{{ $cat->category }}" class="category-card-img" loading="lazy">
                <div class="category-card-overlay">
                    <span class="category-card-count">
                        {{ $cat->count }} {{ Str::plural('Piece', $cat->count) }} Currently in Vault
                    </span>
                    <h3 class="category-card-name" style="font-size: 1.4rem;">{{ $cat->category }}</h3>
                    <span class="category-card-link-text">
                        Explore {{ $cat->category }}
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </span>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Custom Atelier Inquiry Callout -->
    <div style="margin-top: 5rem; background: linear-gradient(135deg, #fbf9f4 0%, #f4ede1 100%); border: 1px solid var(--gold-border); border-radius: var(--radius-lg); padding: 3rem; text-align: center;">
        <span class="section-subtitle">Custom Karigar Atelier</span>
        <h2 class="section-title" style="font-size: 2rem; margin-bottom: 0.75rem;">Looking for a Custom Bespoke Creation?</h2>
        <p class="section-desc" style="max-width: 600px; margin: 0 auto 2rem auto;">
            Our master goldsmiths craft customized bridal sets, heirloom remodelings, and engraved diamond solitaires tailored specifically to your family heritage.
        </p>

        <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
            <button type="button" class="btn-gold-primary" onclick="openConsultationModal('Private Design Consultation')">
                Request Custom Bespoke Design
            </button>
            <a href="https://wa.me/919876543210?text={{ urlencode('Hello B V Jewellers, I would like to discuss a custom bespoke jewellery design.') }}" target="_blank" rel="noopener noreferrer" class="btn-whatsapp-large">
                Chat with Karigar Specialist
            </a>
        </div>
    </div>
</div>

@endsection
