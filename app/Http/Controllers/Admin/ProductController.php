<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JewelryProduct;
use App\Models\MetalRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of jewelry items with filters.
     */
    public function index(Request $request): View
    {
        $query = JewelryProduct::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('hallmark_huid', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('metal_type')) {
            $query->where('metal_type', 'like', "%{$request->input('metal_type')}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $rates = MetalRate::all();
        $categories = ['Necklaces', 'Rings', 'Bangles & Bracelets', 'Earrings', 'Pendants', 'Mangalsutras', 'Coins & Bars', 'Silverware'];
        $metalTypes = ['Gold', 'Diamond', 'Silver', 'Platinum', 'Polki & Kundan'];

        return view('admin.products.index', compact('products', 'rates', 'categories', 'metalTypes'));
    }

    /**
     * Store a newly created jewelry product in the database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:50', 'unique:jewelry_products,sku'],
            'category' => ['required', 'string'],
            'metal_type' => ['required', 'string'],
            'purity' => ['required', 'string'],
            'gross_weight' => ['required', 'numeric', 'min:0'],
            'net_weight' => ['required', 'numeric', 'min:0'],
            'stone_weight_carat' => ['nullable', 'numeric', 'min:0'],
            'stone_type' => ['nullable', 'string', 'max:255'],
            'making_charge_percent' => ['required', 'numeric', 'min:0'],
            'making_charge_fixed' => ['nullable', 'numeric', 'min:0'],
            'calculated_price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'hallmark_huid' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:in_stock,low_stock,custom_order,sold'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url'],
            'is_featured' => ['boolean'],
        ]);

        $validated['stone_weight_carat'] = $validated['stone_weight_carat'] ?? 0;
        $validated['making_charge_fixed'] = $validated['making_charge_fixed'] ?? 0;
        $validated['is_featured'] = $request->has('is_featured');

        JewelryProduct::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Jewellery item '.$validated['sku'].' added to vault inventory successfully!');
    }

    /**
     * Update the specified jewelry product.
     */
    public function update(Request $request, JewelryProduct $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'metal_type' => ['required', 'string'],
            'purity' => ['required', 'string'],
            'gross_weight' => ['required', 'numeric', 'min:0'],
            'net_weight' => ['required', 'numeric', 'min:0'],
            'stone_weight_carat' => ['nullable', 'numeric', 'min:0'],
            'stone_type' => ['nullable', 'string', 'max:255'],
            'making_charge_percent' => ['required', 'numeric', 'min:0'],
            'making_charge_fixed' => ['nullable', 'numeric', 'min:0'],
            'calculated_price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'hallmark_huid' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:in_stock,low_stock,custom_order,sold'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url'],
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Jewellery item '.$product->sku.' updated successfully!');
    }

    /**
     * Remove the specified jewelry product from inventory.
     */
    public function destroy(JewelryProduct $product): RedirectResponse
    {
        $sku = $product->sku;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Jewellery item '.$sku.' removed from catalog.');
    }
}
