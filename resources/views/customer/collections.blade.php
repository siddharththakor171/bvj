@extends('layouts.customer')

@section('title', 'Curated Jewellery Collections & Vault Categories')
@section('meta_description', 'Explore bespoke collections at B V JEWELLERS: Royal Bridal Harams, Solitaire Diamonds, Heritage Bangles, Pure Silverware, and Minted Bullion.')

@section('content')

<div class="container-luxury collections-content">
    <div class="category-cards-grid collections-grid">
        @foreach($categoriesWithCounts as $cat)
            @php
                $img = $cat->sample_image ?: ($defaultImages[$cat->category] ?? 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=600&auto=format&fit=crop&q=80');
            @endphp
            <a href="{{ route('catalogue.index', ['category' => $cat->category]) }}" class="category-card collections-card">
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
    <div class="collections-callout">
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
