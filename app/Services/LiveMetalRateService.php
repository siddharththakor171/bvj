<?php

namespace App\Services;

use App\Models\MetalRate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class LiveMetalRateService
{
    private const TROY_OUNCE_IN_GRAMS = 31.1034768;

    /**
     * Return the latest gold and silver rates in INR per gram.
     */
    public function currentRates(): Collection
    {
        if (! app()->environment('testing')) {
            if (! Cache::has('live-metal-rates-refreshed') && $this->refreshRates()) {
                Cache::put('live-metal-rates-refreshed', true, now()->addMinutes(5));
            }
        }

        return MetalRate::query()
            ->whereIn('metal_code', ['gold_24k', 'gold_22k', 'silver_999'])
            ->orderByRaw("CASE metal_code WHEN 'gold_24k' THEN 1 WHEN 'gold_22k' THEN 2 WHEN 'silver_999' THEN 3 END")
            ->get();
    }

    private function refreshRates(): bool
    {
        try {
            $http = Http::timeout(5);
            $caBundle = config('services.live_rates.ca_bundle');

            if (is_string($caBundle) && is_file($caBundle)) {
                $http = $http->withOptions(['verify' => $caBundle]);
            }

            $gold = $http->get('https://api.gold-api.com/price/XAU')->json('price');
            $silver = $http->get('https://api.gold-api.com/price/XAG')->json('price');
            $exchangeRate = $http->get('https://open.er-api.com/v6/latest/USD')->json('rates.INR');

            if (! is_numeric($gold) || ! is_numeric($silver) || ! is_numeric($exchangeRate)) {
                return false;
            }

            $goldPerGram = ((float) $gold * (float) $exchangeRate) / self::TROY_OUNCE_IN_GRAMS;
            $silverPerGram = ((float) $silver * (float) $exchangeRate) / self::TROY_OUNCE_IN_GRAMS;

            $this->updateRate('gold_24k', $goldPerGram);
            $this->updateRate('gold_22k', $goldPerGram * 0.916);
            $this->updateRate('silver_999', $silverPerGram);

            return true;
        } catch (Throwable) {
            // Keep the last known database rates when a provider is unavailable.
            return false;
        }
    }

    private function updateRate(string $metalCode, float $newRate): void
    {
        $rate = MetalRate::where('metal_code', $metalCode)->first();

        if (! $rate) {
            return;
        }

        $currentRate = (float) $rate->rate_per_gram;
        $rate->update([
            'previous_rate' => $currentRate,
            'rate_per_gram' => round($newRate, 2),
            'rate_per_10g' => round($newRate * 10, 2),
            'trend' => $newRate > $currentRate ? 'up' : ($newRate < $currentRate ? 'down' : 'stable'),
        ]);
    }
}
