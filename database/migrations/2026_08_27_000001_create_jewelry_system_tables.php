<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('metal_rates', function (Blueprint $table) {
            $table->id();
            $table->string('metal_name'); // e.g. Gold 24K, Gold 22K, Gold 18K, Silver 999, Platinum 950
            $table->string('metal_code')->unique(); // gold_24k, gold_22k, gold_18k, silver_999, platinum_950, diamond_carat
            $table->string('purity'); // 99.9%, 91.6% (916), 75.0% (750), 99.9%, 95.0%
            $table->decimal('rate_per_gram', 10, 2); // e.g. 7450.00
            $table->decimal('rate_per_10g', 12, 2)->nullable();
            $table->string('unit')->default('gram'); // gram, kg, carat
            $table->decimal('previous_rate', 10, 2)->nullable();
            $table->enum('trend', ['up', 'down', 'stable'])->default('stable');
            $table->timestamps();
        });

        Schema::create('jewelry_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique(); // e.g. BVJ-GLD-1021
            $table->string('category'); // Necklaces, Rings, Bangles & Bracelets, Earrings, Pendants, Mangalsutras, Coins & Bars, Silverware
            $table->string('metal_type'); // Gold, Diamond, Silver, Platinum, Polki & Kundan
            $table->string('purity')->default('22K (916)'); // 24K, 22K, 18K, 925 Silver, etc.
            $table->decimal('gross_weight', 8, 3); // in grams (e.g. 18.450 g)
            $table->decimal('net_weight', 8, 3); // metal weight without stones
            $table->decimal('stone_weight_carat', 8, 3)->default(0.000); // diamond/stone weight
            $table->string('stone_type')->nullable(); // Diamond VVS-EF, Emerald, Ruby, Cubic Zirconia
            $table->decimal('making_charge_percent', 5, 2)->default(12.50); // % making charge
            $table->decimal('making_charge_fixed', 10, 2)->default(0.00); // fixed making charge per gram or total
            $table->decimal('calculated_price', 12, 2); // Estimated selling price
            $table->integer('stock_quantity')->default(1);
            $table->string('hallmark_huid')->nullable(); // BIS Hallmark 6-digit alphanumeric unique identifier
            $table->enum('status', ['in_stock', 'low_stock', 'custom_order', 'sold'])->default('in_stock');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        Schema::create('jewelry_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // e.g. BVJ-ORD-2026-001
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->string('customer_city')->nullable();
            $table->string('order_type')->default('Ready Stock'); // Ready Stock, Custom Bridal Making, Gold Exchange, Repair & Polishing
            $table->text('items_summary');
            $table->decimal('metal_rate_applied', 10, 2)->nullable();
            $table->decimal('total_weight', 8, 3)->nullable();
            $table->decimal('subtotal_amount', 12, 2);
            $table->decimal('making_charges_total', 10, 2)->default(0.00);
            $table->decimal('gst_amount', 10, 2)->default(0.00); // 3% jewelry GST
            $table->decimal('total_amount', 12, 2);
            $table->decimal('advance_paid', 12, 2)->default(0.00);
            $table->decimal('balance_due', 12, 2)->default(0.00);
            $table->enum('status', ['pending', 'in_workshop', 'hallmarking', 'ready_for_pickup', 'completed', 'cancelled'])->default('pending');
            $table->date('delivery_due_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('jewelry_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('inquiry_number')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->string('interested_category'); // Bridal Set, Solitaire Ring, Gold Chain, Daily Wear
            $table->string('budget_range')->nullable(); // ₹1,00,000 - ₹3,00,000
            $table->enum('status', ['new', 'contacted', 'appointment_booked', 'converted', 'closed'])->default('new');
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jewelry_inquiries');
        Schema::dropIfExists('jewelry_orders');
        Schema::dropIfExists('jewelry_products');
        Schema::dropIfExists('metal_rates');
    }
};
