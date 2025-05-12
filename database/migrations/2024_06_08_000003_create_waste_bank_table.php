<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('waste_banks', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->string('name');
            $table->string('business_license')->nullable();
            $table->string('business_license_photo')->nullable();
            $table->string('address');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('max_capacity', 12, 2);
            $table->decimal('current_capacity', 12, 2)->default(0);
            $table->json('operational_hours');
            $table->boolean('pickup_service')->default(false);
            $table->decimal('pickup_radius', 8, 2)->nullable();
            $table->enum('verification_level', ['basic', 'verified', 'premium'])->default('basic');
            $table->text('description');
            $table->string('profile_photo')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->boolean('cash_payment_enabled')->default(true);
            $table->boolean('digital_payment_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waste_banks');
    }
}; 