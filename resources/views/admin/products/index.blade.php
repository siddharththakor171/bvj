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
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" id="addProductForm">
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
                        <label class="form-label">Featured Item?</label>
                        <div style="padding: 0.65rem 0; display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="is_featured" id="addFeatured" style="width:16px;height:16px;accent-color:var(--gold-primary);">
                            <label for="addFeatured" style="font-size:0.85rem;color:#59554f;margin:0;">Mark as Featured Masterpiece</label>
                        </div>
                    </div>
                </div>

                {{-- ── Image Upload Section ── --}}
                <div class="form-group">
                    <label class="form-label">Jewellery Photo</label>
                    <div class="img-upload-zone" id="addUploadZone" onclick="document.getElementById('addImageFile').click()">
                        <div class="img-upload-placeholder" id="addUploadPlaceholder">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            <div style="margin-top: 0.75rem; font-weight: 700; color: #1f1c18; font-size: 0.9rem;">Drag & drop image here</div>
                            <div style="font-size: 0.78rem; color: #8c867e; margin-top: 0.25rem;">or click to browse &bull; JPG, PNG, WebP &bull; max 5 MB</div>
                        </div>
                        <img id="addImagePreview" class="img-upload-preview" src="" alt="Preview" style="display:none;">
                    </div>
                    <input type="file" name="product_image" id="addImageFile" accept="image/jpeg,image/png,image/webp" style="display:none;" onchange="handleImageFileSelect(this, 'addImagePreview', 'addUploadPlaceholder', 'addClearBtn')">
                    <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                        <button type="button" class="btn-outline-gold btn-sm" onclick="document.getElementById('addImageFile').click()" style="display:flex;align-items:center;gap:0.4rem;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            Upload from Device
                        </button>
                        <button type="button" class="btn-outline-gold btn-sm" onclick="openCamera('addCameraModal', 'addImageFile', 'addImagePreview', 'addUploadPlaceholder', 'addClearBtn')" style="display:flex;align-items:center;gap:0.4rem;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                            Take Photo
                        </button>
                        <button type="button" id="addClearBtn" class="btn-outline-gold btn-sm" onclick="clearImage('addImageFile', 'addImagePreview', 'addUploadPlaceholder', 'addClearBtn')" style="display:none;color:#f87171;border-color:rgba(239,68,68,0.4);">
                            ✕ Remove Photo
                        </button>
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
        <form id="editProductForm" method="POST" action="" enctype="multipart/form-data">
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
                        <label class="form-label">Featured Item?</label>
                        <div style="padding: 0.65rem 0; display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="is_featured" id="editFeatured" style="width:16px;height:16px;accent-color:var(--gold-primary);">
                            <label for="editFeatured" style="font-size:0.85rem;color:#59554f;margin:0;">Mark as Featured Masterpiece</label>
                        </div>
                    </div>
                </div>

                {{-- ── Image Upload Section (Edit) ── --}}
                <div class="form-group">
                    <label class="form-label">Jewellery Photo</label>
                    <div class="img-upload-zone" id="editUploadZone" onclick="document.getElementById('editImageFile').click()">
                        <div class="img-upload-placeholder" id="editUploadPlaceholder">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            <div style="margin-top: 0.75rem; font-weight: 700; color: #1f1c18; font-size: 0.9rem;">Drag & drop image here</div>
                            <div style="font-size: 0.78rem; color: #8c867e; margin-top: 0.25rem;">or click to browse &bull; JPG, PNG, WebP &bull; max 5 MB</div>
                        </div>
                        <img id="editImagePreview" class="img-upload-preview" src="" alt="Preview" style="display:none;">
                    </div>
                    <input type="file" name="product_image" id="editImageFile" accept="image/jpeg,image/png,image/webp" style="display:none;" onchange="handleImageFileSelect(this, 'editImagePreview', 'editUploadPlaceholder', 'editClearBtn')">
                    <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                        <button type="button" class="btn-outline-gold btn-sm" onclick="document.getElementById('editImageFile').click()" style="display:flex;align-items:center;gap:0.4rem;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            Upload from Device
                        </button>
                        <button type="button" class="btn-outline-gold btn-sm" onclick="openCamera('editCameraModal', 'editImageFile', 'editImagePreview', 'editUploadPlaceholder', 'editClearBtn')" style="display:flex;align-items:center;gap:0.4rem;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                            Take Photo
                        </button>
                        <button type="button" id="editClearBtn" class="btn-outline-gold btn-sm" onclick="clearImage('editImageFile', 'editImagePreview', 'editUploadPlaceholder', 'editClearBtn')" style="display:none;color:#f87171;border-color:rgba(239,68,68,0.4);">
                            ✕ Remove Photo
                        </button>
                    </div>
                    <p id="editCurrentImgNote" style="font-size:0.75rem;color:#8c867e;margin-top:0.35rem;display:none;">Current image will be kept if no new photo is selected.</p>
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
        document.getElementById('editFeatured').checked = !!product.is_featured;

        // Reset image uploader
        clearImage('editImageFile', 'editImagePreview', 'editUploadPlaceholder', 'editClearBtn');

        // Show current image note if product has one
        const note = document.getElementById('editCurrentImgNote');
        if (product.image_url) {
            // Show current image as preview
            const preview = document.getElementById('editImagePreview');
            const placeholder = document.getElementById('editUploadPlaceholder');
            preview.src = product.image_url;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
            note.style.display = 'block';
        } else {
            note.style.display = 'none';
        }

        openModal('editProductModal');
    }

    /* ── Image Upload Helpers ── */

    // Handle drag events on upload zone
    document.querySelectorAll('.img-upload-zone').forEach(function(zone) {
        zone.addEventListener('dragover', function(e) {
            e.preventDefault();
            zone.classList.add('dragging');
        });
        zone.addEventListener('dragleave', function() {
            zone.classList.remove('dragging');
        });
        zone.addEventListener('drop', function(e) {
            e.preventDefault();
            zone.classList.remove('dragging');
            const file = e.dataTransfer.files[0];
            if (!file || !file.type.startsWith('image/')) return;
            // Determine which zone was dropped on
            const isEdit = zone.id === 'editUploadZone';
            const fileInput = document.getElementById(isEdit ? 'editImageFile' : 'addImageFile');
            // Assign file to the hidden input via DataTransfer
            const dt = new DataTransfer();
            dt.items.add(file);
            fileInput.files = dt.files;
            handleImageFileSelect(fileInput,
                isEdit ? 'editImagePreview' : 'addImagePreview',
                isEdit ? 'editUploadPlaceholder' : 'addUploadPlaceholder',
                isEdit ? 'editClearBtn' : 'addClearBtn'
            );
        });
    });

    function handleImageFileSelect(input, previewId, placeholderId, clearBtnId) {
        const file = input.files[0];
        if (!file) return;
        if (file.size > 5 * 1024 * 1024) {
            alert('Image too large! Maximum allowed size is 5 MB.');
            input.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);
            const clearBtn = document.getElementById(clearBtnId);
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
            clearBtn.style.display = 'inline-flex';
        };
        reader.readAsDataURL(file);
    }

    function clearImage(fileInputId, previewId, placeholderId, clearBtnId) {
        const input = document.getElementById(fileInputId);
        const preview = document.getElementById(previewId);
        const placeholder = document.getElementById(placeholderId);
        const clearBtn = document.getElementById(clearBtnId);
        if (input) input.value = '';
        if (preview) { preview.src = ''; preview.style.display = 'none'; }
        if (placeholder) placeholder.style.display = 'flex';
        if (clearBtn) clearBtn.style.display = 'none';
    }

    /* ── Camera Helpers ── */
    let activeCameraStream = null;

    function openCamera(modalId, fileInputId, previewId, placeholderId, clearBtnId) {
        const modal = document.getElementById(modalId);
        const camPrefix = modalId === 'addCameraModal' ? 'add' : 'edit';
        const permMsg = document.getElementById(camPrefix + 'CamPermissionMsg');
        const liveWrap = document.getElementById(camPrefix + 'CamLiveWrap');

        // Store context on modal element for use in requestCamera/capturePhoto
        modal._ctx = { fileInputId, previewId, placeholderId, clearBtnId };
        permMsg.style.display = 'block';
        liveWrap.style.display = 'none';

        openModal(modalId);

        // Auto-request camera (browser will show permission prompt)
        requestCamera(modalId, fileInputId, previewId, placeholderId, clearBtnId);
    }

    function requestCamera(modalId, fileInputId, previewId, placeholderId, clearBtnId) {
        const camPrefix = modalId === 'addCameraModal' ? 'add' : 'edit';
        const permMsg = document.getElementById(camPrefix + 'CamPermissionMsg');
        const liveWrap = document.getElementById(camPrefix + 'CamLiveWrap');
        const video = document.getElementById(camPrefix + 'CamVideo');

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Camera not supported in this browser. Please use a modern browser like Chrome or Edge.');
            return;
        }

        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment', width: { ideal: 1920 }, height: { ideal: 1080 } }, audio: false })
            .then(function(stream) {
                activeCameraStream = stream;
                video.srcObject = stream;
                permMsg.style.display = 'none';
                liveWrap.style.display = 'block';
            })
            .catch(function(err) {
                permMsg.style.display = 'block';
                liveWrap.style.display = 'none';
                if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                    alert('Camera permission was denied. Please click the camera icon in your browser address bar and allow camera access, then try again.');
                } else if (err.name === 'NotFoundError') {
                    alert('No camera device found. Please connect a camera and try again.');
                } else {
                    alert('Could not start camera: ' + err.message);
                }
            });
    }

    function capturePhoto(modalId, videoId, canvasId, fileInputId, previewId, placeholderId, clearBtnId) {
        const video = document.getElementById(videoId);
        const canvas = document.getElementById(canvasId);
        canvas.width = video.videoWidth || 1280;
        canvas.height = video.videoHeight || 720;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        canvas.toBlob(function(blob) {
            const fileName = 'jewellery-photo-' + Date.now() + '.jpg';
            const file = new File([blob], fileName, { type: 'image/jpeg' });
            const dt = new DataTransfer();
            dt.items.add(file);
            const fileInput = document.getElementById(fileInputId);
            fileInput.files = dt.files;

            // Show preview
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);
            const clearBtn = document.getElementById(clearBtnId);
            preview.src = canvas.toDataURL('image/jpeg', 0.92);
            preview.style.display = 'block';
            placeholder.style.display = 'none';
            clearBtn.style.display = 'inline-flex';

            // Close camera
            closeCamera(modalId);
        }, 'image/jpeg', 0.92);
    }

    function closeCamera(modalId) {
        if (activeCameraStream) {
            activeCameraStream.getTracks().forEach(track => track.stop());
            activeCameraStream = null;
        }
        const camPrefix = modalId === 'addCameraModal' ? 'add' : 'edit';
        const video = document.getElementById(camPrefix + 'CamVideo');
        if (video) video.srcObject = null;
        closeModal(modalId);
    }
</script>
@endpush
@endsection
