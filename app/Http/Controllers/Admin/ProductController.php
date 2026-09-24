<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JewelryCategory;
use App\Models\JewelryProduct;
use App\Services\LiveMetalRateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of jewelry items with filters.
     */
    public function index(Request $request, LiveMetalRateService $liveMetalRates): View
    {
        $this->syncExistingProductCategories();

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
        $rates = $liveMetalRates->currentRates();
        $categories = JewelryCategory::query()->orderBy('name')->pluck('name');
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
            'sku' => ['nullable', 'string', 'max:50', 'unique:jewelry_products,sku'],
            'category' => ['required', 'exists:jewelry_categories,name'],
            'metal_type' => ['nullable', 'string'],
            'purity' => ['nullable', 'string'],
            'gross_weight' => ['nullable', 'numeric', 'min:0'],
            'net_weight' => ['nullable', 'numeric', 'min:0'],
            'stone_weight_carat' => ['nullable', 'numeric', 'min:0'],
            'stone_type' => ['nullable', 'string', 'max:255'],
            'making_charge_percent' => ['nullable', 'numeric', 'min:0'],
            'making_charge_fixed' => ['nullable', 'numeric', 'min:0'],
            'calculated_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'hallmark_huid' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'in:in_stock,low_stock,sold'],
            'description' => ['nullable', 'string'],
            'product_image' => ['nullable', 'image', 'mimes:jpeg,png,webp,jpg', 'max:5120'],
            'is_featured' => ['boolean'],
        ]);

        do {
            $sku = 'BVJ-'.Str::upper(Str::random(3)).'-'.random_int(1000, 9999);
        } while (JewelryProduct::where('sku', $sku)->exists());

        $validated['sku'] = $validated['sku'] ?? $sku;
        $validated['metal_type'] = $validated['metal_type'] ?? 'Gold';
        $validated['purity'] = $validated['purity'] ?? '22K (916)';
        $validated['gross_weight'] = $validated['gross_weight'] ?? 0;
        $validated['net_weight'] = $validated['net_weight'] ?? 0;
        $validated['stone_weight_carat'] = $validated['stone_weight_carat'] ?? 0;
        $validated['making_charge_percent'] = $validated['making_charge_percent'] ?? 12.5;
        $validated['making_charge_fixed'] = $validated['making_charge_fixed'] ?? 0;
        $validated['calculated_price'] = $validated['calculated_price'] ?? 0;
        $validated['stock_quantity'] = $validated['stock_quantity'] ?? 1;
        $validated['status'] = $validated['status'] ?? 'in_stock';
        $validated['is_featured'] = $request->has('is_featured');

        // Handle image upload
        if ($request->hasFile('product_image')) {
            $path = $request->file('product_image')->store('products', 'public');
            $validated['image_url'] = Storage::url($path);
        }
        unset($validated['product_image']);

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
            'category' => ['required', 'exists:jewelry_categories,name'],
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
            'status' => ['required', 'in:in_stock,low_stock,sold'],
            'description' => ['nullable', 'string'],
            'product_image' => ['nullable', 'image', 'mimes:jpeg,png,webp,jpg', 'max:5120'],
        ]);

        $validated['is_featured'] = $request->has('is_featured');

        // Handle image upload — delete old file if it was stored locally
        if ($request->hasFile('product_image')) {
            // Delete previous local image if applicable
            if ($product->image_url && str_starts_with($product->image_url, '/storage/')) {
                $oldPath = str_replace('/storage/', 'public/', $product->image_url);
                Storage::delete($oldPath);
            }
            $path = $request->file('product_image')->store('products', 'public');
            $validated['image_url'] = Storage::url($path);
        }
        unset($validated['product_image']);

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

    private function syncExistingProductCategories(): void
    {
        JewelryProduct::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->each(fn (string $name) => JewelryCategory::firstOrCreate(['name' => $name], ['slug' => Str::slug($name)]));
    }
}
