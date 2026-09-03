<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\JewelryInquiry;
use App\Models\JewelryProduct;
use App\Models\StoreSetting;
use App\Services\LiveMetalRateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CatalogueController extends Controller
{
    /**
     * Display the luxury customer home page.
     */
    public function home(LiveMetalRateService $liveMetalRates): View
    {
        $featuredProducts = JewelryProduct::where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        // If not enough featured products, fallback to recent products
        if ($featuredProducts->isEmpty()) {
            $featuredProducts = JewelryProduct::latest()->take(6)->get();
        }

        $latestProducts = JewelryProduct::latest()->take(8)->get();
        $heroProduct = $featuredProducts->first() ?? JewelryProduct::first();
        $rates = $liveMetalRates->currentRates();

        // Group categories with product count from Vault database
        $categoryCounts = JewelryProduct::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        $defaultCategories = [
            'Necklaces' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600&auto=format&fit=crop&q=80',
            'Rings' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=600&auto=format&fit=crop&q=80',
            'Bangles & Bracelets' => 'https://images.unsplash.com/photo-1611591475836-9e19bbd2a762?w=600&auto=format&fit=crop&q=80',
            'Earrings' => 'https://images.unsplash.com/photo-1630019852942-f89202989a59?w=600&auto=format&fit=crop&q=80',
            'Mangalsutras' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=600&auto=format&fit=crop&q=80',
            'Coins & Bars' => 'https://images.unsplash.com/photo-1610375461246-83df859d849d?w=600&auto=format&fit=crop&q=80',
            'Silverware' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=600&auto=format&fit=crop&q=80',
            'Pendants' => 'https://images.unsplash.com/photo-1599643477877-530eb83abc8e?w=600&auto=format&fit=crop&q=80',
        ];

        return view('customer.home', compact(
            'featuredProducts',
            'latestProducts',
            'heroProduct',
            'rates',
            'categoryCounts',
            'defaultCategories'
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

        // Get distinct categories and metal types present in the Vault
        $categories = JewelryProduct::distinct()->pluck('category')->filter()->values()->toArray();
        if (empty($categories)) {
            $categories = ['Necklaces', 'Rings', 'Bangles & Bracelets', 'Earrings', 'Pendants', 'Mangalsutras', 'Coins & Bars', 'Silverware'];
        }

        $metalTypes = JewelryProduct::distinct()->pluck('metal_type')->filter()->values()->toArray();
        if (empty($metalTypes)) {
            $metalTypes = ['Gold', 'Diamond', 'Silver', 'Platinum', 'Polki & Kundan'];
        }

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
        $categoriesWithCounts = JewelryProduct::select('category', DB::raw('count(*) as count'), DB::raw('MAX(image_url) as sample_image'))
            ->groupBy('category')
            ->get();

        $defaultImages = [
            'Necklaces' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600&auto=format&fit=crop&q=80',
            'Rings' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=600&auto=format&fit=crop&q=80',
            'Bangles & Bracelets' => 'https://images.unsplash.com/photo-1611591475836-9e19bbd2a762?w=600&auto=format&fit=crop&q=80',
            'Earrings' => 'https://images.unsplash.com/photo-1630019852942-f89202989a59?w=600&auto=format&fit=crop&q=80',
            'Mangalsutras' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=600&auto=format&fit=crop&q=80',
            'Coins & Bars' => 'https://images.unsplash.com/photo-1610375461246-83df859d849d?w=600&auto=format&fit=crop&q=80',
            'Silverware' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=600&auto=format&fit=crop&q=80',
            'Pendants' => 'https://images.unsplash.com/photo-1599643477877-530eb83abc8e?w=600&auto=format&fit=crop&q=80',
        ];

        return view('customer.collections', compact('categoriesWithCounts', 'defaultImages'));
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
     * Return current gold and silver rates for live page updates.
     */
    public function liveRates(LiveMetalRateService $liveMetalRates): JsonResponse
    {
        return response()->json([
            'rates' => $liveMetalRates->currentRates()->map(fn ($rate): array => [
                'metal_code' => $rate->metal_code,
                'rate_per_gram' => (float) $rate->rate_per_gram,
                'trend' => $rate->trend,
            ])->values(),
            'updated_at' => now()->toIso8601String(),
        ]);
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
