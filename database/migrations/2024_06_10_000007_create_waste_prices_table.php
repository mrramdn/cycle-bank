<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('waste_prices', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->uuid('waste_bank_guid'); // Bank sampah yang menetapkan harga
            $table->uuid('waste_category_guid'); // Kategori sampah
            $table->decimal('buy_price', 10, 2); // Harga beli dari nasabah
            $table->decimal('sell_price', 10, 2); // Harga jual ke pengelola
            $table->decimal('min_quantity', 8, 2)->default(0.1); // Minimal kuantitas dalam kg
            $table->json('condition_requirements')->nullable(); // Persyaratan kondisi sampah
            $table->boolean('is_active')->default(true);
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waste_prices');
    }
}; 