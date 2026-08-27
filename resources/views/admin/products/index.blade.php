@extends('layouts.admin')

@section('title', 'Jewellery Catalog & Vault')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Vault Inventory & Jewellery Catalog</h1>
        <p class="page-subtitle">Manage hallmarked gold, solitaire diamond, and antique jewellery stocks.</p>
    </div>
    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
        <button type="button" class="btn-gold" onclick="openModal('addProductModal')">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Jewellery Item
        </button>
    </div>
</div>

<!-- Filters & Search Bar Card -->
<div class="gold-card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form method="GET" action="{{ route('admin.products.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)) auto; gap: 1rem; align-items: flex-end;">
        <div>
            <label class="form-label">Search Keyword / HUID</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, SKU, HUID..." class="form-control">
        </div>

        <div>
            <label class="form-label">Category</label>
            <select name="category" class="form-control">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="form-label">Metal Type</label>
            <select name="metal_type" class="form-control">
                <option value="">All Metals</option>
                @foreach($metalTypes as $metal)
                    <option value="{{ $metal }}" {{ request('metal_type') === $metal ? 'selected' : '' }}>{{ $metal }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="form-label">Stock Status</label>
            <select name="status" class="form-control">
                <option value="">All Statuses</option>
                <option value="in_stock" {{ request('status') === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                <option value="custom_order" {{ request('status') === 'custom_order' ? 'selected' : '' }}>Custom Order</option>
                <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>Sold</option>
            </select>
        </div>

        <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn-gold" style="padding: 0.65rem 1rem;">Filter</button>
            <a href="{{ route('admin.products.index') }}" class="btn-outline-gold" style="padding: 0.65rem 1rem;">Reset</a>
        </div>
    </form>
</div>

<!-- Products Table Card -->
<div class="gold-card">
    <div class="table-responsive">
        <table class="luxury-table">
            <thead>
                <tr>
                    <th>Item / SKU</th>
                    <th>Category & Metal</th>
                    <th>Purity & Hallmark HUID</th>
                    <th>Gross / Net Wt</th>
                    <th>Making (%)</th>
                    <th>Price (₹)</th>
                    <th>Stock / Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.85rem;">
                                <div style="width: 44px; height: 44px; border-radius: 8px; background: #fdfaf2; border: 1px solid var(--border-color); overflow: hidden; flex-shrink: 0;">
                                    @if($p->image_url)
                                        <img src="{{ $p->image_url }}" alt="{{ $p->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--gold-primary);">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="6 3 18 3 22 9 12 22 2 9 6 3"></polygon></svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: #1f1c18; font-size: 0.9rem;">{{ $p->name }}</div>
                                    <div style="font-family: monospace; color: var(--gold-primary); font-size: 0.75rem; font-weight: 600;">{{ $p->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #1f1c18;">{{ $p->category }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $p->metal_type }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: var(--gold-primary); font-size: 0.85rem;">{{ $p->purity }}</div>
                            @if($p->hallmark_huid)
                                <div class="hallmark-tag" style="margin-top: 0.2rem;">
                                    <span>BIS</span> {{ $p->hallmark_huid }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div style="font-size: 0.85rem; color: #1f1c18;">Gross: <strong>{{ number_format($p->gross_weight, 3) }}g</strong></div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">Net: {{ number_format($p->net_weight, 3) }}g</div>
                        </td>
                        <td>
                            <span style="color: var(--gold-primary); font-weight: 700;">{{ $p->making_charge_percent }}%</span>
                        </td>
                        <td>
                            <div style="font-size: 1rem; font-weight: 800; color: #1f1c18; font-family: var(--font-heading);">
                                ₹{{ number_format($p->calculated_price, 2) }}
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="badge badge-{{ $p->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $p->status)) }}
                                </span>
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                                Qty: {{ $p->stock_quantity }}
                            </div>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.5rem;">
                                <button type="button" class="btn-outline-gold btn-sm" onclick="openEditModal({{ $p->id }}, {{ json_encode($p) }})">
                                    Edit
                                </button>
                                <form method="POST" action="{{ route('admin.products.destroy', $p->id) }}" onsubmit="return confirm('Remove this jewellery item from vault catalog?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-outline-gold btn-sm" style="color: #f87171; border-color: rgba(239, 68, 68, 0.4);">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 3rem;">
                            No jewellery items matched your query criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $products->links() }}
    </div>
</div>

<!-- Modal: Add New Jewellery Item -->
<div class="modal-overlay" id="addProductModal">
    <div class="modal-card" style="max-width: 720px;">
        <div class="modal-header">
            <h3 class="modal-title">Add Jewellery Item to Vault</h3>
            <button type="button" class="modal-close" onclick="closeModal('addProductModal')">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.products.store') }}">
            @csrf
            <div class="modal-body">
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Jewellery Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Royal Bridal Polki Haram" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">SKU Code</label>
                        <input type="text" name="sku" class="form-control" value="BVJ-{{ strtoupper(Str::random(3)) }}-{{ rand(1000,9999) }}" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-control" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Metal Type</label>
                        <select name="metal_type" class="form-control" required>
                            @foreach($metalTypes as $metal)
                                <option value="{{ $metal }}">{{ $metal }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Purity</label>
                        <input type="text" name="purity" class="form-control" value="22K (916)" placeholder="e.g. 22K (916) or 18K" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Gross Wt (g)</label>
                        <input type="number" step="0.001" name="gross_weight" id="addGross" class="form-control" placeholder="0.000" oninput="estimateAddPrice()" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Net Wt (g)</label>
                        <input type="number" step="0.001" name="net_weight" id="addNet" class="form-control" placeholder="0.000" oninput="estimateAddPrice()" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Making Charge (%)</label>
                        <input type="number" step="0.01" name="making_charge_percent" id="addMaking" class="form-control" value="12.50" oninput="estimateAddPrice()" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Selling Price (₹)</label>
                        <input type="number" step="0.01" name="calculated_price" id="addPrice" class="form-control" placeholder="Calculated price" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" name="stock_quantity" class="form-control" value="1" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">BIS Hallmark HUID</label>
                        <input type="text" name="hallmark_huid" class="form-control" placeholder="e.g. BVJ88X19">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="in_stock">In Stock</option>
                            <option value="low_stock">Low Stock</option>
                            <option value="custom_order">Custom Order</option>
                            <option value="sold">Sold</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Image URL (Optional)</label>
                        <input type="url" name="image_url" class="form-control" placeholder="https://...">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description & Karigar Notes</label>
                    <textarea name="description" rows="2" class="form-control" placeholder="Detailed craftsmanship specifications..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline-gold" onclick="closeModal('addProductModal')">Cancel</button>
                <button type="submit" class="btn-gold">Add to Vault Catalog</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Jewellery Item -->
<div class="modal-overlay" id="editProductModal">
    <div class="modal-card" style="max-width: 720px;">
        <div class="modal-header">
            <h3 class="modal-title">Edit Jewellery Item Details</h3>
            <button type="button" class="modal-close" onclick="closeModal('editProductModal')">&times;</button>
        </div>
        <form id="editProductForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Jewellery Name</label>
                    <input type="text" name="name" id="editName" class="form-control" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" id="editCategory" class="form-control" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Metal Type</label>
                        <select name="metal_type" id="editMetal" class="form-control" required>
                            @foreach($metalTypes as $metal)
                                <option value="{{ $metal }}">{{ $metal }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Purity</label>
                        <input type="text" name="purity" id="editPurity" class="form-control" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Gross Wt (g)</label>
                        <input type="number" step="0.001" name="gross_weight" id="editGross" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Net Wt (g)</label>
                        <input type="number" step="0.001" name="net_weight" id="editNet" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Making Charge (%)</label>
                        <input type="number" step="0.01" name="making_charge_percent" id="editMaking" class="form-control" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Price (₹)</label>
                        <input type="number" step="0.01" name="calculated_price" id="editPrice" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" name="stock_quantity" id="editStock" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">BIS Hallmark HUID</label>
                        <input type="text" name="hallmark_huid" id="editHuid" class="form-control">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" id="editStatus" class="form-control" required>
                            <option value="in_stock">In Stock</option>
                            <option value="low_stock">Low Stock</option>
                            <option value="custom_order">Custom Order</option>
                            <option value="sold">Sold</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Image URL</label>
                        <input type="url" name="image_url" id="editImage" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline-gold" onclick="closeModal('editProductModal')">Cancel</button>
                <button type="submit" class="btn-gold">Update Jewellery Item</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const live22kRate = {{ optional($rates->firstWhere('metal_code', 'gold_22k'))->rate_per_gram ?? 6855 }};

    function estimateAddPrice() {
        const net = parseFloat(document.getElementById('addNet').value) || 0;
        const making = parseFloat(document.getElementById('addMaking').value) || 12.5;
        if (net > 0) {
            const metalVal = net * live22kRate;
            const makingVal = metalVal * (making / 100);
            const subtotal = metalVal + makingVal;
            const gst = subtotal * 0.03;
            document.getElementById('addPrice').value = Math.round(subtotal + gst);
        }
    }

    function openEditModal(id, product) {
        document.getElementById('editProductForm').action = '/admin/products/' + id;
        document.getElementById('editName').value = product.name;
        document.getElementById('editCategory').value = product.category;
        document.getElementById('editMetal').value = product.metal_type;
        document.getElementById('editPurity').value = product.purity;
        document.getElementById('editGross').value = product.gross_weight;
        document.getElementById('editNet').value = product.net_weight;
        document.getElementById('editMaking').value = product.making_charge_percent;
        document.getElementById('editPrice').value = product.calculated_price;
        document.getElementById('editStock').value = product.stock_quantity;
        document.getElementById('editHuid').value = product.hallmark_huid || '';
        document.getElementById('editStatus').value = product.status;
        document.getElementById('editImage').value = product.image_url || '';

        openModal('editProductModal');
    }
</script>
@endpush
@endsection
