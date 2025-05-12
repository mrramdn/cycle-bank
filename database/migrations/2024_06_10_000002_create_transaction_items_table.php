<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->uuid('transaction_guid');
            $table->uuid('waste_category_guid');
            $table->decimal('quantity', 10, 2); // dalam kg
            $table->decimal('price_per_unit', 10, 2);
            $table->decimal('subtotal', 16, 2);
            $table->integer('points_earned')->default(0);
            $table->text('description')->nullable();
            $table->string('image_url')->nullable(); // Foto sampah jika ada
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_items');
    }
}; 