<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetalRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RateController extends Controller
{
    /**
     * Display daily bullion rates and price calculator.
     */
    public function index(): View
    {
        $rates = MetalRate::all();

        return view('admin.rates.index', compact('rates'));
    }

    /**
     * Update live rate for a specific metal.
     */
    public function update(Request $request, MetalRate $rate): RedirectResponse
    {
        $validated = $request->validate([
            'rate_per_gram' => ['required', 'numeric', 'min:1'],
        ]);

        $newRate = (float) $validated['rate_per_gram'];
        $currentRate = (float) $rate->rate_per_gram;

        $trend = 'stable';
        if ($newRate > $currentRate) {
            $trend = 'up';
        } elseif ($newRate < $currentRate) {
            $trend = 'down';
        }

        $rate->previous_rate = $currentRate;
        $rate->rate_per_gram = $newRate;
        $rate->rate_per_10g = round($newRate * 10, 2);
        $rate->trend = $trend;
        $rate->save();

        return back()->with('success', "Live rate for {$rate->metal_name} updated to ₹".number_format($newRate, 2).'/g successfully!');
    }
}
