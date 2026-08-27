<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JewelryInquiry;
use App\Models\JewelryOrder;
use App\Models\JewelryProduct;
use App\Models\MetalRate;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Executive Jewelry Dashboard.
     */
    public function index(): View
    {
        $rates = MetalRate::all();
        $rate24k = $rates->firstWhere('metal_code', 'gold_24k');
        $rate22k = $rates->firstWhere('metal_code', 'gold_22k');
        $rateSilver = $rates->firstWhere('metal_code', 'silver_999');
        $rateDiamond = $rates->firstWhere('metal_code', 'diamond_carat');

        // Key metrics
        $totalProducts = JewelryProduct::count();
        $totalInventoryValue = JewelryProduct::sum('calculated_price');
        $totalGoldWeight = JewelryProduct::where('metal_type', 'like', '%Gold%')->sum('net_weight');
        $totalSilverWeight = JewelryProduct::where('metal_type', 'like', '%Silver%')->sum('net_weight');

        $activeOrders = JewelryOrder::whereIn('status', ['pending', 'in_workshop', 'hallmarking', 'ready_for_pickup'])->count();
        $totalRevenue = JewelryOrder::where('status', '!=', 'cancelled')->sum('total_amount');
        $pendingInquiries = JewelryInquiry::whereIn('status', ['new', 'contacted', 'appointment_booked'])->count();

        // Recent Data
        $recentOrders = JewelryOrder::latest()->take(5)->get();
        $recentInquiries = JewelryInquiry::latest()->take(4)->get();
        $featuredProducts = JewelryProduct::where('is_featured', true)->take(4)->get();

        // Category breakdown
        $categoriesBreakdown = JewelryProduct::selectRaw('category, count(*) as count, sum(calculated_price) as total_val')
            ->groupBy('category')
            ->get();

        return view('admin.dashboard', compact(
            'rates',
            'rate24k',
            'rate22k',
            'rateSilver',
            'rateDiamond',
            'totalProducts',
            'totalInventoryValue',
            'totalGoldWeight',
            'totalSilverWeight',
            'activeOrders',
            'totalRevenue',
            'pendingInquiries',
            'recentOrders',
            'recentInquiries',
            'featuredProducts',
            'categoriesBreakdown'
        ));
    }
}
