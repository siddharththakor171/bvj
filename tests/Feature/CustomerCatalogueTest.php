<?php

use App\Models\JewelryInquiry;
use App\Models\JewelryProduct;
use App\Models\MetalRate;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    // Seed default admin and initial rates if not present
    $this->admin = User::firstOrCreate(
        ['username' => 'admin'],
        [
            'name' => 'B V Jewellers Admin',
            'email' => 'admin@bvjewellers.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]
    );

    $this->goldRate = MetalRate::firstOrCreate(
        ['metal_code' => 'gold_22k'],
        [
            'metal_name' => 'Gold 22K (916)',
            'purity' => '91.6%',
            'rate_per_gram' => 6855.00,
            'rate_per_10g' => 68550.00,
            'unit' => 'gram',
            'trend' => 'up',
        ]
    );

    // Create a known vault product
    $this->product = JewelryProduct::updateOrCreate(
        ['sku' => 'BVJ-TEST-CHOKER'],
        [
            'name' => 'Royal Heritage Polki Bridal Choker',
            'category' => 'Necklaces',
            'metal_type' => 'Gold & Kundan',
            'purity' => '22K (916)',
            'gross_weight' => 65.500,
            'net_weight' => 55.200,
            'stone_weight_carat' => 10.300,
            'stone_type' => 'Uncut Polki & Emeralds',
            'making_charge_percent' => 14.00,
            'making_charge_fixed' => 0.00,
            'calculated_price' => 475000.00,
            'stock_quantity' => 2,
            'hallmark_huid' => 'BVJ999HUID',
            'status' => 'in_stock',
            'description' => 'Exquisite handcrafted bridal choker in 22K pure gold.',
            'image_url' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600&auto=format&fit=crop&q=80',
            'is_featured' => true,
        ]
    );
});

test('customer home page still renders when the Vite manifest is missing', function () {
    $manifestPath = public_path('build/manifest.json');
    $hadManifest = file_exists($manifestPath);

    if ($hadManifest) {
        rename($manifestPath, $manifestPath.'.bak');
    }

    try {
        $this->get('/')->assertStatus(200)->assertSee('B V JEWELLERS');
    } finally {
        if ($hadManifest) {
            rename($manifestPath.'.bak', $manifestPath);
        }
    }
});

test('customer home page renders with branding, live rates and featured vault items', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('B V JEWELLERS');
    $response->assertSee('Heritage Karigar Atelier');
    $response->assertSee('Gold 22K (916)');
    $response->assertSee('Royal Heritage Polki Bridal Choker');
    $response->assertSee('Explore Vault Collection');
});

test('customer catalogue page lists products directly from the vault database', function () {
    $response = $this->get('/catalogue');

    $response->assertStatus(200);
    $response->assertSee('Jewellery Catalogue & Vault');
    $response->assertSee('Royal Heritage Polki Bridal Choker');
    $response->assertSee('BVJ-TEST-CHOKER');
    $response->assertSee('₹475,000.00');
});

test('customer catalogue search finds products by name, sku, or hallmark huid', function () {
    // Search by SKU
    $response = $this->get('/catalogue?search=BVJ-TEST-CHOKER');
    $response->assertStatus(200);
    $response->assertSee('Royal Heritage Polki Bridal Choker');

    // Search by Hallmark HUID
    $response = $this->get('/catalogue?search=BVJ999HUID');
    $response->assertStatus(200);
    $response->assertSee('Royal Heritage Polki Bridal Choker');

    // Search for non-existing query
    $response = $this->get('/catalogue?search=NonExistentJewelXYZ');
    $response->assertStatus(200);
    $response->assertSee('No Vault Items Found');
});

test('customer catalogue filters products by category and metal type', function () {
    $response = $this->get('/catalogue?category=Necklaces');
    $response->assertStatus(200);
    $response->assertSee('Royal Heritage Polki Bridal Choker');

    // Filter by another category where this item shouldn't appear
    $ring = JewelryProduct::updateOrCreate(
        ['sku' => 'BVJ-TEST-RING'],
        [
            'name' => 'Solitaire Diamond Ring',
            'category' => 'Rings',
            'metal_type' => 'Diamond',
            'purity' => '18K White Gold',
            'gross_weight' => 4.500,
            'net_weight' => 4.200,
            'stone_weight_carat' => 1.000,
            'stone_type' => 'Solitaire Diamond',
            'making_charge_percent' => 10.00,
            'calculated_price' => 120000.00,
            'stock_quantity' => 3,
            'status' => 'in_stock',
        ]
    );

    $responseCat = $this->get('/catalogue?category=Rings');
    $responseCat->assertStatus(200);
    $responseCat->assertSee('Solitaire Diamond Ring');
    $responseCat->assertDontSee('Royal Heritage Polki Bridal Choker');
});

test('customer product details page displays exact saved vault specifications', function () {
    $response = $this->get("/jewellery/{$this->product->sku}");

    $response->assertStatus(200);
    $response->assertSee('Royal Heritage Polki Bridal Choker');
    $response->assertSee('BVJ-TEST-CHOKER');
    $response->assertSee('Necklaces');
    $response->assertSee('Gold & Kundan');
    $response->assertSee('22K (916)');
    $response->assertSee('65.500 Grams');
    $response->assertSee('55.200 Grams');
    $response->assertSee('10.300 Carats');
    $response->assertSee('BVJ999HUID');
    $response->assertSee('14.00%');
    $response->assertSee('₹475,000.00');
    $response->assertSee('Exquisite handcrafted bridal choker in 22K pure gold.');
});

test('customer product details generates pre-filled whatsapp enquiry link', function () {
    $response = $this->get("/jewellery/{$this->product->sku}");

    $response->assertStatus(200);
    $response->assertSee('https://wa.me/919876543210?text=', false);
    $response->assertSee(urlencode('BVJ-TEST-CHOKER'), false);
});

test('collections page displays categories with vault counts', function () {
    $response = $this->get('/collections');

    $response->assertStatus(200);
    $response->assertSee('Curated Jewellery Collections');
    $response->assertSee('Necklaces');
});

test('about page renders brand heritage and statutory credentials', function () {
    $response = $this->get('/about');

    $response->assertStatus(200);
    $response->assertSee('Four Decades of Purity');
    $response->assertSee('HM-IND-2026-928810-BVJ');
    $response->assertSee('27AAAAA0000A1Z5');
});

test('contact page renders showroom details and timings', function () {
    $response = $this->get('/contact');

    $response->assertStatus(200);
    $response->assertSee('Visit B V JEWELLERS Showroom');
    $response->assertSee('Zaveri Bazaar');
    $response->assertSee('+91 98765 43210');
    $response->assertSee('10:30 AM – 8:30 PM');
});

test('customer can submit consultation inquiry logged in database', function () {
    $response = $this->post('/inquiry', [
        'customer_name' => 'Radhika Sharma',
        'customer_phone' => '+91 98111 22233',
        'customer_email' => 'radhika@example.com',
        'interested_category' => 'Bridal Jewellery Set',
        'budget_range' => '₹6,00,000 - ₹15,00,000',
        'message' => 'Interested in viewing uncut polki bridal sets.',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('jewelry_inquiries', [
        'customer_name' => 'Radhika Sharma',
        'customer_phone' => '+91 98111 22233',
        'interested_category' => 'Bridal Jewellery Set',
    ]);
});

test('e2e sync: admin adds product in vault -> appears in customer catalogue -> admin updates -> reflects -> admin deletes -> removed', function () {
    $sku = 'BVJ-SYNC-' . rand(1000, 9999);

    // 1. Admin adds item in "Add Jewellery Item to Vault"
    $addResponse = $this->actingAs($this->admin)->post('/admin/products', [
        'name' => 'Imperial Emerald Peacock Haram',
        'sku' => $sku,
        'category' => 'Necklaces',
        'metal_type' => 'Gold',
        'purity' => '22K (916)',
        'gross_weight' => 88.250,
        'net_weight' => 76.100,
        'stone_weight_carat' => 12.150,
        'stone_type' => 'Zambian Emeralds',
        'making_charge_percent' => 13.50,
        'calculated_price' => 625000.00,
        'stock_quantity' => 1,
        'hallmark_huid' => 'BVJ77SYNC',
        'status' => 'in_stock',
        'description' => 'Antique peacock motif studded with Colombian emeralds.',
        'image_url' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600&auto=format&fit=crop&q=80',
    ]);
    $addResponse->assertRedirect('/admin/products');

    // 2. Customer sees it in /catalogue
    $catResponse = $this->get('/catalogue');
    $catResponse->assertStatus(200);
    $catResponse->assertSee('Imperial Emerald Peacock Haram');
    $catResponse->assertSee($sku);
    $catResponse->assertSee('₹625,000.00');

    // 3. Customer opens /jewellery/{sku}
    $detailResponse = $this->get("/jewellery/{$sku}");
    $detailResponse->assertStatus(200);
    $detailResponse->assertSee('Imperial Emerald Peacock Haram');
    $detailResponse->assertSee('88.250 Grams');
    $detailResponse->assertSee('BVJ77SYNC');

    // 4. Admin edits the product details
    $product = JewelryProduct::where('sku', $sku)->firstOrFail();
    $updateResponse = $this->actingAs($this->admin)->put("/admin/products/{$product->id}", [
        'name' => 'Imperial Emerald Peacock Haram - Grand Edition',
        'category' => 'Necklaces',
        'metal_type' => 'Gold',
        'purity' => '22K (916)',
        'gross_weight' => 90.000,
        'net_weight' => 78.000,
        'stone_weight_carat' => 12.000,
        'stone_type' => 'Zambian Emeralds',
        'making_charge_percent' => 13.50,
        'calculated_price' => 650000.00,
        'stock_quantity' => 1,
        'hallmark_huid' => 'BVJ77SYNC',
        'status' => 'in_stock',
    ]);
    $updateResponse->assertRedirect('/admin/products');

    // Customer sees updated details
    $detailUpdated = $this->get("/jewellery/{$sku}");
    $detailUpdated->assertStatus(200);
    $detailUpdated->assertSee('Imperial Emerald Peacock Haram - Grand Edition');
    $detailUpdated->assertSee('₹650,000.00');
    $detailUpdated->assertSee('90.000 Grams');

    // 5. Admin deletes the product
    $deleteResponse = $this->actingAs($this->admin)->delete("/admin/products/{$product->id}");
    $deleteResponse->assertRedirect('/admin/products');

    // Customer catalogue no longer contains the item
    $catAfterDelete = $this->get('/catalogue');
    $catAfterDelete->assertDontSee('Imperial Emerald Peacock Haram - Grand Edition');

    // Direct link returns 404
    $detailAfterDelete = $this->get("/jewellery/{$sku}");
    $detailAfterDelete->assertStatus(404);
});
