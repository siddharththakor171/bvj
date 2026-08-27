<?php

namespace Database\Seeders;

use App\Models\JewelryInquiry;
use App\Models\JewelryOrder;
use App\Models\JewelryProduct;
use App\Models\MetalRate;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create or update Default Admin User
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'B V Jewellers Admin',
                'email' => 'admin@bvjewellers.com',
                'phone' => '+91 98765 43210',
                'password' => Hash::make('admin'),
                'role' => 'admin',
                'avatar' => null,
            ]
        );

        // 2. Seed Daily Live Metal Rates (per gram & per 10g)
        $rates = [
            [
                'metal_name' => 'Gold 24K (999)',
                'metal_code' => 'gold_24k',
                'purity' => '99.9%',
                'rate_per_gram' => 7480.00,
                'rate_per_10g' => 74800.00,
                'unit' => 'gram',
                'previous_rate' => 7420.00,
                'trend' => 'up',
            ],
            [
                'metal_name' => 'Gold 22K (916 Hallmark)',
                'metal_code' => 'gold_22k',
                'purity' => '91.6%',
                'rate_per_gram' => 6855.00,
                'rate_per_10g' => 68550.00,
                'unit' => 'gram',
                'previous_rate' => 6810.00,
                'trend' => 'up',
            ],
            [
                'metal_name' => 'Gold 18K (750)',
                'metal_code' => 'gold_18k',
                'purity' => '75.0%',
                'rate_per_gram' => 5610.00,
                'rate_per_10g' => 56100.00,
                'unit' => 'gram',
                'previous_rate' => 5580.00,
                'trend' => 'stable',
            ],
            [
                'metal_name' => 'Fine Silver 999',
                'metal_code' => 'silver_999',
                'purity' => '99.9%',
                'rate_per_gram' => 92.50,
                'rate_per_10g' => 925.00,
                'unit' => 'gram',
                'previous_rate' => 91.00,
                'trend' => 'up',
            ],
            [
                'metal_name' => 'Platinum 950',
                'metal_code' => 'platinum_950',
                'purity' => '95.0%',
                'rate_per_gram' => 3850.00,
                'rate_per_10g' => 38500.00,
                'unit' => 'gram',
                'previous_rate' => 3890.00,
                'trend' => 'down',
            ],
            [
                'metal_name' => 'Solitaire Diamond (VVS1-EF)',
                'metal_code' => 'diamond_carat',
                'purity' => 'VVS1 / E-F Color',
                'rate_per_gram' => 85000.00, // base per carat rate
                'rate_per_10g' => 850000.00,
                'unit' => 'carat',
                'previous_rate' => 84000.00,
                'trend' => 'up',
            ],
        ];

        foreach ($rates as $rate) {
            MetalRate::updateOrCreate(['metal_code' => $rate['metal_code']], $rate);
        }

        // 3. Seed Representative Luxury Jewelry Catalog
        $products = [
            [
                'name' => 'Royal Maharani Polki & Kundan Bridal Choker',
                'sku' => 'BVJ-NK-1001',
                'category' => 'Necklaces',
                'metal_type' => 'Gold & Kundan',
                'purity' => '22K (916)',
                'gross_weight' => 74.850,
                'net_weight' => 61.200,
                'stone_weight_carat' => 13.650,
                'stone_type' => 'Uncut Polki Diamonds & Zambian Emeralds',
                'making_charge_percent' => 14.00,
                'making_charge_fixed' => 0.00,
                'calculated_price' => 548000.00,
                'stock_quantity' => 2,
                'hallmark_huid' => 'BVJ92X88',
                'status' => 'in_stock',
                'description' => 'Exquisite heritage bridal choker handcrafted with 22K yellow gold, certified uncut polki diamonds and vibrant green emerald droplets.',
                'image_url' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600&auto=format&fit=crop&q=80',
                'is_featured' => true,
            ],
            [
                'name' => 'Eternal Solitaire Diamond Engagement Ring',
                'sku' => 'BVJ-RNG-2045',
                'category' => 'Rings',
                'metal_type' => 'Diamond',
                'purity' => '18K White Gold',
                'gross_weight' => 5.250,
                'net_weight' => 4.950,
                'stone_weight_carat' => 1.500,
                'stone_type' => 'IGI Certified VVS1 / Colorless E',
                'making_charge_percent' => 10.00,
                'making_charge_fixed' => 4500.00,
                'calculated_price' => 165000.00,
                'stock_quantity' => 5,
                'hallmark_huid' => 'BVJ18D34',
                'status' => 'in_stock',
                'description' => 'Brilliant cut 1.50 carat solitaire diamond set in a precision four-prong 18K white gold cathedral band.',
                'image_url' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=600&auto=format&fit=crop&q=80',
                'is_featured' => true,
            ],
            [
                'name' => 'Heritage Antique Lakshmi Kasu Temple Bangle Set',
                'sku' => 'BVJ-BNG-3088',
                'category' => 'Bangles & Bracelets',
                'metal_type' => 'Gold',
                'purity' => '22K (916)',
                'gross_weight' => 48.600,
                'net_weight' => 48.600,
                'stone_weight_carat' => 0.000,
                'stone_type' => 'Pure 22K Gold Antique Finish',
                'making_charge_percent' => 12.50,
                'making_charge_fixed' => 0.00,
                'calculated_price' => 386500.00,
                'stock_quantity' => 3,
                'hallmark_huid' => 'BVJ91A55',
                'status' => 'in_stock',
                'description' => 'Pair of 22K antique gold handcrafted temple bangles featuring Goddess Lakshmi motifs with ruby cabochon highlights.',
                'image_url' => 'https://images.unsplash.com/photo-1611591475836-9e19bbd2a762?w=600&auto=format&fit=crop&q=80',
                'is_featured' => true,
            ],
            [
                'name' => 'Imperial Floral Diamond Jhumkas',
                'sku' => 'BVJ-EAR-4012',
                'category' => 'Earrings',
                'metal_type' => 'Diamond',
                'purity' => '18K Rose Gold',
                'gross_weight' => 14.800,
                'net_weight' => 13.200,
                'stone_weight_carat' => 1.800,
                'stone_type' => 'Round Brilliant Cut Diamonds (VVS-FG)',
                'making_charge_percent' => 15.00,
                'making_charge_fixed' => 2000.00,
                'calculated_price' => 218000.00,
                'stock_quantity' => 4,
                'hallmark_huid' => 'BVJ18J99',
                'status' => 'in_stock',
                'description' => 'Dazzling 18K rose gold jhumka earrings encrusted with 1.80 cts of sparkling natural diamonds with micro-pavé detailing.',
                'image_url' => 'https://images.unsplash.com/photo-1630019852942-f89202989a59?w=600&auto=format&fit=crop&q=80',
                'is_featured' => false,
            ],
            [
                'name' => 'Diamond Mangalsutra with Dual Strands',
                'sku' => 'BVJ-MNG-5020',
                'category' => 'Mangalsutras',
                'metal_type' => 'Gold & Diamond',
                'purity' => '18K (750)',
                'gross_weight' => 11.400,
                'net_weight' => 10.100,
                'stone_weight_carat' => 0.650,
                'stone_type' => 'Diamonds & Handcrafted Black Onyx Beads',
                'making_charge_percent' => 11.00,
                'making_charge_fixed' => 1500.00,
                'calculated_price' => 112000.00,
                'stock_quantity' => 6,
                'hallmark_huid' => 'BVJ18M21',
                'status' => 'in_stock',
                'description' => 'Modern bridal mangalsutra in 18K yellow gold with elegant diamond centerpiece and auspicious black bead strands.',
                'image_url' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=600&auto=format&fit=crop&q=80',
                'is_featured' => false,
            ],
            [
                'name' => 'Handcrafted 925 Pure Silver Nakshi Puja Thali Set',
                'sku' => 'BVJ-SLV-6004',
                'category' => 'Silverware',
                'metal_type' => 'Silver',
                'purity' => '92.5% Sterling',
                'gross_weight' => 520.000,
                'net_weight' => 520.000,
                'stone_weight_carat' => 0.000,
                'stone_type' => 'Plain Pure Silver',
                'making_charge_percent' => 18.00,
                'making_charge_fixed' => 0.00,
                'calculated_price' => 58500.00,
                'stock_quantity' => 8,
                'hallmark_huid' => 'BVJ92S11',
                'status' => 'in_stock',
                'description' => 'Luxurious 925 sterling silver puja thali complete with diya, agarbatti stand, kumkum vati, and bell featuring floral hand embossing.',
                'image_url' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=600&auto=format&fit=crop&q=80',
                'is_featured' => false,
            ],
            [
                'name' => '24K 999 Fine Gold Bullion Minted Bar (10 Grams)',
                'sku' => 'BVJ-BAR-7010',
                'category' => 'Coins & Bars',
                'metal_type' => 'Gold',
                'purity' => '24K (999.9)',
                'gross_weight' => 10.000,
                'net_weight' => 10.000,
                'stone_weight_carat' => 0.000,
                'stone_type' => 'NABL Assay Certified Bullion',
                'making_charge_percent' => 2.00,
                'making_charge_fixed' => 500.00,
                'calculated_price' => 76800.00,
                'stock_quantity' => 25,
                'hallmark_huid' => 'BVJ99B99',
                'status' => 'in_stock',
                'description' => 'Tamper-evident assay blister packed 24 Karat 999.9 purity investment gold bar embossed with B V Jewellers emblem.',
                'image_url' => 'https://images.unsplash.com/photo-1610375461246-83df859d849d?w=600&auto=format&fit=crop&q=80',
                'is_featured' => true,
            ],
        ];

        foreach ($products as $product) {
            JewelryProduct::updateOrCreate(['sku' => $product['sku']], $product);
        }

        // 4. Seed Representative Orders
        $orders = [
            [
                'order_number' => 'BVJ-ORD-2026-101',
                'customer_name' => 'Vikramaditya Singhania',
                'customer_phone' => '+91 98230 11223',
                'customer_email' => 'vikram.singhania@gmail.com',
                'customer_city' => 'Mumbai',
                'order_type' => 'Custom Bridal Making',
                'items_summary' => '22K Kundan Polki Choker + Matching Maang Tikka & Earrings',
                'metal_rate_applied' => 6855.00,
                'total_weight' => 112.400,
                'subtotal_amount' => 845000.00,
                'making_charges_total' => 95000.00,
                'gst_amount' => 28200.00,
                'total_amount' => 968200.00,
                'advance_paid' => 500000.00,
                'balance_due' => 468200.00,
                'status' => 'in_workshop',
                'delivery_due_date' => now()->addDays(12)->toDateString(),
                'notes' => 'Custom bridal commission for wedding on Sept 20. Karigar: Master Ramesh.',
            ],
            [
                'order_number' => 'BVJ-ORD-2026-102',
                'customer_name' => 'Ananya Deshmukh',
                'customer_phone' => '+91 97664 55443',
                'customer_email' => 'ananya.d@outlook.com',
                'customer_city' => 'Pune',
                'order_type' => 'Ready Stock',
                'items_summary' => 'Eternal Solitaire Diamond Engagement Ring (1.5 ct)',
                'metal_rate_applied' => 5610.00,
                'total_weight' => 5.250,
                'subtotal_amount' => 160000.00,
                'making_charges_total' => 4500.00,
                'gst_amount' => 4935.00,
                'total_amount' => 169435.00,
                'advance_paid' => 169435.00,
                'balance_due' => 0.00,
                'status' => 'ready_for_pickup',
                'delivery_due_date' => now()->addDays(2)->toDateString(),
                'notes' => 'Ring sized to Indian standard size 14. Velvet luxury gift box packed.',
            ],
            [
                'order_number' => 'BVJ-ORD-2026-103',
                'customer_name' => 'Rajeshwar Patel',
                'customer_phone' => '+91 99090 88776',
                'customer_email' => 'r.patel@patelgroup.in',
                'customer_city' => 'Ahmedabad',
                'order_type' => 'Gold Bullion Purchase',
                'items_summary' => '5 x 24K 999 10g Gold Minted Bars',
                'metal_rate_applied' => 7480.00,
                'total_weight' => 50.000,
                'subtotal_amount' => 374000.00,
                'making_charges_total' => 2500.00,
                'gst_amount' => 11295.00,
                'total_amount' => 387795.00,
                'advance_paid' => 387795.00,
                'balance_due' => 0.00,
                'status' => 'completed',
                'delivery_due_date' => now()->subDay()->toDateString(),
                'notes' => 'Invoiced with HUID certification and NABL purity report.',
            ],
        ];

        foreach ($orders as $order) {
            JewelryOrder::updateOrCreate(['order_number' => $order['order_number']], $order);
        }

        // 5. Seed Customer Inquiries
        $inquiries = [
            [
                'inquiry_number' => 'INQ-2026-501',
                'customer_name' => 'Dr. Meera Nambiar',
                'customer_phone' => '+91 94471 22334',
                'customer_email' => 'meera.nambiar@aiims.edu',
                'interested_category' => 'Bridal Polki & Diamond Set',
                'budget_range' => '₹8,00,000 - ₹12,00,000',
                'status' => 'appointment_booked',
                'message' => 'Looking for complete wedding jewelry package for daughter. VIP private vault viewing requested on Saturday.',
            ],
            [
                'inquiry_number' => 'INQ-2026-502',
                'customer_name' => 'Sanjay & Priya Kapoor',
                'customer_phone' => '+91 98112 33445',
                'customer_email' => 'sanjay.kapoor@gmail.com',
                'interested_category' => '2.0 Carat Solitaire Ring',
                'budget_range' => '₹3,50,000 - ₹5,00,000',
                'status' => 'new',
                'message' => '25th Anniversary gift inquiry. Interested in Oval or Cushion cut solitaire diamond.',
            ],
            [
                'inquiry_number' => 'INQ-2026-503',
                'customer_name' => 'Kavita Chawla',
                'customer_phone' => '+91 98711 00998',
                'customer_email' => 'kavita.c@yahoo.com',
                'interested_category' => 'Antique Temple Haram',
                'budget_range' => '₹4,00,000 - ₹6,00,000',
                'status' => 'contacted',
                'message' => 'Sent initial catalog on WhatsApp. Customer liked the Kasu mala design.',
            ],
        ];

        foreach ($inquiries as $inquiry) {
            JewelryInquiry::updateOrCreate(['inquiry_number' => $inquiry['inquiry_number']], $inquiry);
        }
    }
}
