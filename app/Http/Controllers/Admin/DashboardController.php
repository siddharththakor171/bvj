<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JewelryProduct;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Executive Jewelry Dashboard.
     */
    public function index(): View
    {
        // Key metrics
        $totalProducts = JewelryProduct::count();
        $totalInventoryValue = JewelryProduct::sum('calculated_price');
        $totalGoldWeight = JewelryProduct::where('metal_type', 'like', '%Gold%')->sum('net_weight');
        $totalSilverWeight = JewelryProduct::where('metal_type', 'like', '%Silver%')->sum('net_weight');

        $featuredProducts = JewelryProduct::where('is_featured', true)->take(4)->get();

        // Category breakdown
        $categoriesBreakdown = JewelryProduct::selectRaw('category, count(*) as count, sum(calculated_price) as total_val')
            ->groupBy('category')
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalInventoryValue',
            'totalGoldWeight',
            'totalSilverWeight',
            'featuredProducts',
            'categoriesBreakdown'
        ));
    }
}
