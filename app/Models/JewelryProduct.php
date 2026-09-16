<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JewelryProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'category',
        'metal_type',
        'purity',
        'gross_weight',
        'net_weight',
        'stone_weight_carat',
        'stone_type',
        'making_charge_percent',
        'making_charge_fixed',
        'calculated_price',
        'stock_quantity',
        'hallmark_huid',
        'status',
        'description',
        'image_url',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'gross_weight' => 'decimal:3',
            'net_weight' => 'decimal:3',
            'stone_weight_carat' => 'decimal:3',
            'making_charge_percent' => 'decimal:2',
            'making_charge_fixed' => 'decimal:2',
            'calculated_price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    public function getDisplayImageUrlAttribute(): string
    {
        if (filled($this->image_url)) {
            return $this->image_url;
        }

        return [
            'Necklaces' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=900&auto=format&fit=crop&q=85',
            'Rings' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=900&auto=format&fit=crop&q=85',
            'Bangles & Bracelets' => 'https://images.unsplash.com/photo-1611591475836-9e19bbd2a762?w=900&auto=format&fit=crop&q=85',
            'Earrings' => 'https://images.unsplash.com/photo-1630019852942-f89202989a59?w=900&auto=format&fit=crop&q=85',
            'Pendants' => 'https://images.unsplash.com/photo-1599643477877-530eb83abc8e?w=900&auto=format&fit=crop&q=85',
            'Mangalsutras' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=900&auto=format&fit=crop&q=85',
            'Coins & Bars' => 'https://images.unsplash.com/photo-1610375461246-83df859d849d?w=900&auto=format&fit=crop&q=85',
            'Silverware' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=900&auto=format&fit=crop&q=85',
        ][$this->category] ?? 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=900&auto=format&fit=crop&q=85';
    }

    /**
     * Get calculated current market price based on live metal rates.
     */
    public function calculateEstimatedPrice(?MetalRate $rate = null): float
    {
        $ratePerGram = $rate ? (float) $rate->rate_per_gram : 7000.00;
        $metalValue = (float) $this->net_weight * $ratePerGram;
        $makingCharges = $metalValue * ((float) $this->making_charge_percent / 100) + (float) $this->making_charge_fixed;
        $stoneValue = (float) $this->stone_weight_carat * 65000.00; // estimated diamond/gem rate
        $subtotal = $metalValue + $makingCharges + $stoneValue;
        $gst = $subtotal * 0.03; // 3% GST

        return round($subtotal + $gst, 2);
    }
}
