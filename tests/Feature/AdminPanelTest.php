<?php

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

    $this->rate = MetalRate::firstOrCreate(
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

test('admin can access dashboard and view bullion metrics', function () {
    $response = $this->actingAs($this->admin)->get('/admin/dashboard');
    $response->assertStatus(200);
    $response->assertSee('B V JEWELLERS');
    $response->assertSee('Total Vault Valuation');
    $response->assertSee('Gold 22K');
});

test('admin can view jewellery catalog', function () {
    $response = $this->actingAs($this->admin)->get('/admin/products');
    $response->assertStatus(200);
    $response->assertSee('Vault Inventory');
    $response->assertSee('Jewellery Catalog');
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

test('admin can update live bullion rate', function () {
    $response = $this->actingAs($this->admin)->put("/admin/rates/{$this->rate->id}", [
        'rate_per_gram' => 6900.00,
    ]);

    $response->assertRedirect();
    $this->rate->refresh();
    expect((float) $this->rate->rate_per_gram)->toEqual(6900.00);
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
