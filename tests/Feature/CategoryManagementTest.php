<?php

use App\Models\JewelryCategory;
use App\Models\User;

test('category management lists existing product categories and creates a new category', function () {
    $admin = User::factory()->create(['username' => 'category-admin']);
    JewelryCategory::factory()->create(['name' => 'Traditional Jewellery', 'slug' => 'traditional-jewellery']);

    $this->actingAs($admin)->get(route('admin.categories.index'))
        ->assertOk()
        ->assertSeeInOrder(['Create Category', 'Existing Categories', 'Traditional Jewellery']);

    $this->actingAs($admin)->post(route('admin.categories.store'), [
        'name' => 'Bridal Collections',
        'description' => 'Wedding jewellery collections.',
    ])->assertRedirect(route('admin.categories.index'));

    $this->assertDatabaseHas('jewelry_categories', [
        'name' => 'Bridal Collections',
        'description' => 'Wedding jewellery collections.',
    ]);
    expect(JewelryCategory::where('name', 'Traditional Jewellery')->exists())->toBeTrue();

    $this->actingAs($admin)->get(route('admin.products.index'))
        ->assertOk()
        ->assertSee('Bridal Collections');
});
