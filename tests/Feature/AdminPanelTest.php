<?php

use App\Models\JewelryCategory;
use App\Models\JewelryProduct;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    // Seed the default admin and the category required for product creation.
    $this->admin = User::firstOrCreate(
        ['username' => 'admin'],
        [
            'name' => 'B V Jewellers Admin',
            'email' => 'admin@bvjewellers.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]
    );

    JewelryCategory::firstOrCreate(['name' => 'Necklaces'], ['slug' => 'necklaces']);
});

test('guest is redirected to login from admin dashboard', function () {
    $response = $this->get('/admin');
    $response->assertRedirect('/login');
});

test('login page renders with jewelry branding', function () {
    $response = $this->get('/login');
    $response->assertStatus(200);
    $response->assertSee('B V JEWELLERS');
    $response->assertSee('admin');
});

test('admin can authenticate with default credentials admin/admin', function () {
    $response = $this->post('/login', [
        'login' => 'admin',
        'password' => 'admin',
    ]);

    $response->assertRedirect(route('admin.dashboard'));
    $this->assertAuthenticatedAs($this->admin);
});

test('admin can access dashboard without live rate metrics', function () {
    $response = $this->actingAs($this->admin)->get('/admin/dashboard');
    $response->assertStatus(200);
    $response->assertSee('B V JEWELLERS');
    $response->assertSee('Total Vault Valuation');
    $response->assertDontSee("Today's Live Bullion Board");
});

test('admin dashboard renders when inventory has no priced products', function () {
    JewelryProduct::factory()->create([
        'category' => 'Imported Products',
        'calculated_price' => 0,
    ]);

    $this->actingAs($this->admin)->get('/admin/dashboard')
        ->assertOk()
        ->assertSee('Imported Products');
});

test('admin can view jewellery catalog', function () {
    $response = $this->actingAs($this->admin)->get('/admin/products');
    $response->assertStatus(200);
    $response->assertSee('Vault Inventory');
    $response->assertSee('Jewellery Catalog');
});

test('admin product category filter loads stored categories', function () {
    JewelryCategory::create(['name' => 'Custom Bridal Collection', 'slug' => 'custom-bridal-collection']);
    JewelryProduct::factory()->create(['category' => 'Custom Bridal Collection']);

    $this->actingAs($this->admin)->get('/admin/products')
        ->assertOk()
        ->assertSee('Custom Bridal Collection');
});

test('admin can add a new jewellery item', function () {
    $response = $this->actingAs($this->admin)->post('/admin/products', [
        'name' => 'Royal Heritage Temple Haram',
        'sku' => 'BVJ-TEST-'.rand(100, 999),
        'category' => 'Necklaces',
        'metal_type' => 'Gold',
        'purity' => '22K (916)',
        'gross_weight' => 52.400,
        'net_weight' => 52.400,
        'making_charge_percent' => 12.00,
        'calculated_price' => 395000.00,
        'stock_quantity' => 1,
        'status' => 'in_stock',
        'hallmark_huid' => 'BVJ88T11',
    ]);

    $response->assertRedirect('/admin/products');
    $this->assertDatabaseHas('jewelry_products', [
        'name' => 'Royal Heritage Temple Haram',
    ]);
});

test('admin requires a stored category to add a jewellery item', function () {
    $response = $this->actingAs($this->admin)->post('/admin/products', [
        'name' => 'Minimal Name Only Jewellery Item',
    ]);

    $response->assertSessionHasErrors('category');
    $this->assertDatabaseMissing('jewelry_products', [
        'name' => 'Minimal Name Only Jewellery Item',
    ]);
});

test('admin can view change password page', function () {
    $response = $this->actingAs($this->admin)->get('/admin/change-password');
    $response->assertStatus(200);
    $response->assertSee('Change Password');
    $response->assertSee('Current Password');
    $response->assertSee('New Password');
});

test('admin can successfully change password', function () {
    $response = $this->actingAs($this->admin)->post('/admin/change-password', [
        'current_password' => 'admin',
        'password' => 'newSecretPass123',
        'password_confirmation' => 'newSecretPass123',
    ]);

    $response->assertRedirect('/admin/change-password');
    $response->assertSessionHas('success');

    $this->admin->refresh();
    expect(Hash::check('newSecretPass123', $this->admin->password))->toBeTrue();
});

test('admin can logout cleanly', function () {
    $response = $this->actingAs($this->admin)->post('/logout');
    $response->assertRedirect('/login');
    $this->assertGuest();
});
