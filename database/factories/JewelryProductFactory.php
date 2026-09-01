<?php

namespace Database\Factories;

use App\Models\JewelryProduct;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<JewelryProduct>
 */
class JewelryProductFactory extends Factory
{
    protected $model = JewelryProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Necklaces', 'Rings', 'Bangles & Bracelets', 'Earrings', 'Pendants', 'Mangalsutras', 'Coins & Bars', 'Silverware'];
        $metalTypes = ['Gold', 'Diamond', 'Silver', 'Platinum', 'Polki & Kundan'];

        return [
            'name' => 'Royal ' . fake()->words(3, true),
            'sku' => 'BVJ-' . strtoupper(Str::random(3)) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'category' => fake()->randomElement($categories),
            'metal_type' => fake()->randomElement($metalTypes),
            'purity' => '22K (916)',
            'gross_weight' => fake()->randomFloat(3, 5, 80),
            'net_weight' => fake()->randomFloat(3, 4, 75),
            'stone_weight_carat' => fake()->randomFloat(3, 0, 3),
            'stone_type' => 'Certified Diamonds',
            'making_charge_percent' => fake()->randomFloat(2, 8, 16),
            'making_charge_fixed' => 0.00,
            'calculated_price' => fake()->randomFloat(2, 25000, 500000),
            'stock_quantity' => fake()->numberBetween(1, 10),
            'hallmark_huid' => 'BVJ' . strtoupper(Str::random(5)),
            'status' => 'in_stock',
            'description' => fake()->paragraph(),
            'image_url' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600&auto=format&fit=crop&q=80',
            'is_featured' => fake()->boolean(30),
        ];
    }
}
