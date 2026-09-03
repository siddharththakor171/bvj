<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();
            $table->string('establishment');
            $table->string('tagline');
            $table->string('bis_certificate');
            $table->string('bis_note');
            $table->string('gstin');
            $table->string('gst_note');
            $table->timestamps();
        });

        DB::table('store_settings')->insert([
            'establishment' => 'B V JEWELLERS',
            'tagline' => 'Crafting Timeless Elegance & Bullion Trust Since 1984',
            'bis_certificate' => 'HM-IND-2026-928810-BVJ',
            'bis_note' => '100% HUID Laser Verified & Bullion Assay Approved',
            'gstin' => '27AAAAA0000A1Z5',
            'gst_note' => 'Jewellery CGST 1.5% + SGST 1.5%',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_settings');
    }
};
