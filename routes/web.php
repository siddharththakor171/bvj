<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PasswordController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Customer\CatalogueController;
use Illuminate\Support\Facades\Route;

// Customer Facing Luxury Jewellery Catalogue Routes
Route::get('/', [CatalogueController::class, 'home'])->name('home');
Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue.index');
Route::get('/jewellery/{product:sku}', [CatalogueController::class, 'show'])->name('catalogue.show');
Route::get('/collections', [CatalogueController::class, 'collections'])->name('collections');
Route::get('/about', [CatalogueController::class, 'about'])->name('about');
Route::get('/contact', [CatalogueController::class, 'contact'])->name('contact');
Route::post('/inquiry', [CatalogueController::class, 'storeInquiry'])->name('catalogue.inquiry');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Admin Portal Routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // Executive Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Jewellery Products Catalog
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Change Password Module (Explicit Requirement)
    Route::get('/change-password', [PasswordController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [PasswordController::class, 'updatePassword'])->name('password.update');

    // Store & Admin Profile Settings
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
