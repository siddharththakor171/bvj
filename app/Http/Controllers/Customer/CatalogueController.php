<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\JewelryCategory;
use App\Models\JewelryInquiry;
use App\Models\JewelryProduct;
use App\Models\StoreSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogueController extends Controller
{
    /**
     * Display the luxury customer home page.
     */
    public function home(): View
    {
        $featuredProducts = JewelryProduct::where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        // If not enough featured products, fallback to recent products
        if ($featuredProducts->isEmpty()) {
            $featuredProducts = JewelryProduct::latest()->take(6)->get();
        }

        $heroProduct = $featuredProducts->first() ?? JewelryProduct::first();

        $topCategories = JewelryCategory::query()
            ->withCount('products')
            ->with('latestProduct')
            ->has('products')
            ->orderByDesc('products_count')
            ->orderBy('name')
            ->limit(8)
            ->get();

        return view('customer.home', compact(
            'featuredProducts',
            'heroProduct',
            'topCategories'
        ));
    }

    /**
     * Display the filterable jewelry catalogue.
     */
    public function index(Request $request): View
    {
        $query = JewelryProduct::query();

        // 1. Search Query (Name, SKU, Hallmark HUID, Stone type, Description)
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('hallmark_huid', 'like', "%{$search}%")
                    ->orWhere('stone_type', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 2. Category Filter
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // 3. Metal Type Filter
        if ($request->filled('metal_type')) {
            $query->where('metal_type', 'like', "%{$request->input('metal_type')}%");
        }

        // 4. Purity Filter
        if ($request->filled('purity')) {
            $query->where('purity', 'like', "%{$request->input('purity')}%");
        }

        // 5. Stock Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // 6. Price Range Filters
        if ($request->filled('min_price') && is_numeric($request->input('min_price'))) {
            $query->where('calculated_price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price') && is_numeric($request->input('max_price'))) {
            $query->where('calculated_price', '<=', (float) $request->input('max_price'));
        }

        // 7. Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('calculated_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('calculated_price', 'desc');
                break;
            case 'weight_asc':
                $query->orderBy('net_weight', 'asc');
                break;
            case 'weight_desc':
                $query->orderBy('net_weight', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = JewelryCategory::query()->orderBy('name')->pluck('name')->all();
        $metalTypes = JewelryProduct::distinct()->pluck('metal_type')->filter()->values()->toArray();
        $purities = JewelryProduct::distinct()->pluck('purity')->filter()->values()->toArray();

        return view('customer.catalogue', compact('products', 'categories', 'metalTypes', 'purities'));
    }

    /**
     * Display a specific jewellery item's complete craftsmanship details.
     */
    public function show(JewelryProduct $product): View
    {
        // WhatsApp link pre-filled with product name and SKU code
        $whatsappMessage = "Hello B V Jewellers, I would like to enquire about '{$product->name}' (SKU: {$product->sku}). Please share availability and live quotation.";
        $whatsappUrl = 'https://wa.me/919876543210?text='.urlencode($whatsappMessage);

        // Fetch related jewellery pieces from the same category
        $relatedProducts = JewelryProduct::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('customer.show', compact('product', 'whatsappUrl', 'relatedProducts'));
    }

    /**
     * Display curated categories and collections.
     */
    public function collections(): View
    {
        $categoriesWithCounts = JewelryCategory::query()
            ->withCount('products')
            ->with('latestProduct')
            ->has('products')
            ->orderByDesc('products_count')
            ->orderBy('name')
            ->get();

        return view('customer.collections', compact('categoriesWithCounts'));
    }

    /**
     * Display the About Us brand heritage page.
     */
    public function about(): View
    {
        return view('customer.about', ['storeSetting' => StoreSetting::firstOrFail()]);
    }

    /**
     * Display the Contact and Showroom visit information.
     */
    public function contact(): View
    {
        return view('customer.contact');
    }

    /**
     * Store customer VIP showroom consultation or product enquiry.
     */
    public function storeInquiry(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'customer_email' => ['nullable', 'email'],
            'interested_category' => ['nullable', 'string'],
            'budget_range' => ['nullable', 'string'],
            'message' => ['nullable', 'string'],
        ]);

        $validated['inquiry_number'] = 'INQ-'.date('Y').'-'.rand(100, 999);
        $validated['interested_category'] = $validated['interested_category'] ?? 'General Showroom Consultation';
        $validated['status'] = 'new';

        JewelryInquiry::create($validated);

        return back()->with('success', 'Thank you for contacting B V JEWELLERS. Our diamond & bullion specialist will connect with you shortly.');
    }
}
