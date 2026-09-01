@extends('layouts.customer')

@section('title', 'Jewellery Catalogue & Vault Inventory')
@section('meta_description', 'Browse the official jewellery catalogue of B V JEWELLERS. Real-time hallmarked gold jewellery, solitaire diamond rings, and temple ornaments.')

@section('content')

<!-- Catalogue Header Banner -->
<div style="background: radial-gradient(circle at 50% 50%, rgba(212, 175, 55, 0.08) 0%, rgba(18, 17, 14, 0.98) 100%), var(--onyx-deep); color: #ffffff; padding: 3.5rem 0; border-bottom: 1px solid var(--onyx-border);">
    <div class="container-luxury" style="text-align: center;">
        <span class="section-subtitle" style="color: var(--gold-bright);">BVJ Master Collection</span>
        <h1 class="section-title" style="color: #ffffff; font-size: 2.75rem; margin-bottom: 0.5rem;">
            Jewellery Catalogue & Vault
        </h1>
        <p class="section-desc" style="max-width: 600px; margin: 0 auto; color: #c4bfa5;">
            Browse authentic creations currently in our showroom inventory. Every item is 100% BIS hallmarked and crafted to exacting bullion standards.
        </p>
    </div>
</div>

<div class="container-luxury" style="padding-top: 3rem; padding-bottom: 5rem;">
    <div class="catalogue-layout">
        <!-- Filter Sidebar -->
        <aside class="filter-sidebar">
            <!-- Mobile Toggle Button -->
            <button type="button" class="filter-toggle-btn" id="filterToggleBtn" onclick="toggleFilterSidebar()">
                <span style="display:inline-flex;align-items:center;gap:0.4rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    Refine Vault
                </span>
                <svg class="chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </button>

            <div class="filter-sidebar-body" id="filterSidebarBody">
            <div class="filter-header">
                <span class="filter-header-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    Refine Vault
                </span>
                <a href="{{ route('catalogue.index') }}" class="filter-reset-link">Reset Filters</a>
            </div>

            <form method="GET" action="{{ route('catalogue.index') }}" id="filterForm">
                <!-- Search -->
                <div class="filter-group">
                    <label class="filter-group-label">Search Keyword / SKU</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="e.g. Polki, Ring, HUID..." class="luxury-input">
                </div>

                <!-- Category -->
                <div class="filter-group">
                    <label class="filter-group-label">Jewellery Category</label>
                    <select name="category" class="luxury-input" onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Metal Type -->
                <div class="filter-group">
                    <label class="filter-group-label">Metal / Craft Type</label>
                    <select name="metal_type" class="luxury-input" onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Metal Types</option>
                        @foreach($metalTypes as $metal)
                            <option value="{{ $metal }}" {{ request('metal_type') === $metal ? 'selected' : '' }}>{{ $metal }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Purity -->
                @if(!empty($purities))
                    <div class="filter-group">
                        <label class="filter-group-label">Gold / Metal Purity</label>
                        <select name="purity" class="luxury-input" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Purities</option>
                            @foreach($purities as $pur)
                                <option value="{{ $pur }}" {{ request('purity') === $pur ? 'selected' : '' }}>{{ $pur }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Stock Status -->
                <div class="filter-group">
                    <label class="filter-group-label">Availability Status</label>
                    <select name="status" class="luxury-input" onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Statuses</option>
                        <option value="in_stock" {{ request('status') === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="custom_order" {{ request('status') === 'custom_order' ? 'selected' : '' }}>Custom Order</option>
                        <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>Sold</option>
                    </select>
                </div>

                <!-- Price Range -->
                <div class="filter-group">
                    <label class="filter-group-label">Price Range (₹)</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min ₹" class="luxury-input">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max ₹" class="luxury-input">
                    </div>
                </div>

                <!-- Hidden Sort Field -->
                <input type="hidden" name="sort" id="sortInput" value="{{ request('sort', 'newest') }}">

                <button type="submit" class="btn-gold-primary" style="width: 100%; padding: 0.75rem; font-size: 0.82rem;">
                    Apply Filters
                </button>
            </form>
            </div>{{-- /filter-sidebar-body --}}
        </aside>

        <!-- Main Product Results Area -->
        <main>
            <!-- Top Controls Bar -->
            <div class="catalogue-top-bar">
                <div class="results-count-text">
                    Showing <strong>{{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }}</strong> of <strong>{{ $products->total() }}</strong> jewellery creations
                    @if(request('category'))
                        &bull; in <span style="color: var(--gold-primary); font-weight: 700;">{{ request('category') }}</span>
                    @endif
                </div>

                <div class="sort-select-wrap">
                    <label for="sortDropdown">Sort By:</label>
                    <select id="sortDropdown" class="luxury-input" style="width: auto; padding: 0.45rem 0.85rem; font-size: 0.82rem;" onchange="updateSort(this.value)">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest Additions</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="weight_asc" {{ request('sort') === 'weight_asc' ? 'selected' : '' }}>Weight: Light to Heavy</option>
                        <option value="weight_desc" {{ request('sort') === 'weight_desc' ? 'selected' : '' }}>Weight: Heavy to Light</option>
                    </select>
                </div>
            </div>

            <!-- Active Filter Chips -->
            @if(request()->hasAny(['search', 'category', 'metal_type', 'purity', 'status', 'min_price', 'max_price']))
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1.5rem; align-items: center;">
                    <span style="font-size: 0.76rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Active Filters:</span>
                    @if(request('search'))
                        <span class="filter-chip active">Keyword: "{{ request('search') }}"</span>
                    @endif
                    @if(request('category'))
                        <span class="filter-chip active">{{ request('category') }}</span>
                    @endif
                    @if(request('metal_type'))
                        <span class="filter-chip active">{{ request('metal_type') }}</span>
                    @endif
                    @if(request('purity'))
                        <span class="filter-chip active">{{ request('purity') }}</span>
                    @endif
                    @if(request('status'))
                        <span class="filter-chip active">Status: {{ ucfirst(str_replace('_', ' ', request('status'))) }}</span>
                    @endif
                    @if(request('min_price') || request('max_price'))
                        <span class="filter-chip active">₹{{ number_format(request('min_price', 0)) }} - ₹{{ number_format(request('max_price', 0)) }}</span>
                    @endif
                    <a href="{{ route('catalogue.index') }}" style="font-size: 0.75rem; color: var(--gold-primary); font-weight: 700; text-decoration: underline; margin-left: 0.5rem;">
                        Clear All
                    </a>
                </div>
            @endif

            <!-- Products Grid -->
            <div class="products-luxury-grid">
                @forelse($products as $item)
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
                    <div style="grid-column: 1 / -1; background: var(--bg-surface); border: 1px solid var(--gold-border-light); border-radius: var(--radius-md); padding: 4rem 2rem; text-align: center;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 1rem auto; color: var(--gold-primary);"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <h3 style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">
                            No Vault Items Found
                        </h3>
                        <p style="color: var(--text-secondary); max-width: 440px; margin: 0 auto 1.5rem auto; font-size: 0.88rem;">
                            We could not find any jewellery items matching your search criteria. Try modifying your filter options or clearing the search query.
                        </p>
                        <a href="{{ route('catalogue.index') }}" class="btn-gold-primary" style="padding: 0.65rem 1.5rem; font-size: 0.82rem;">
                            Reset All Filters
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="pagination-lux">
                {{ $products->links() }}
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
    function updateSort(val) {
        const sortInput = document.getElementById('sortInput');
        const filterForm = document.getElementById('filterForm');
        if (sortInput && filterForm) {
            sortInput.value = val;
            filterForm.submit();
        }
    }
</script>
@endpush

@endsection
