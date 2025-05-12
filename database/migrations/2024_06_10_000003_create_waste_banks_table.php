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
            $table->uuid('user_guid'); // Relasi ke user
            $table->string('name');
            $table->string('business_license_number')->nullable();
            $table->string('tax_id_number')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code');
            $table->string('contact_person');
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->string('logo_url')->nullable();
            $table->json('operation_hours')->nullable();
            $table->json('accepted_waste_types')->nullable();
            $table->decimal('storage_capacity', 10, 2)->nullable(); // Dalam ton
            $table->decimal('current_storage', 10, 2)->default(0); // Dalam ton
            $table->json('geo_location')->nullable(); // Latitude & longitude
            $table->enum('verification_status', ['unverified', 'basic', 'verified', 'premium'])->default('unverified');
            $table->timestamp('verification_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('balance', 16, 2)->default(0); // Saldo deposit
            $table->integer('rating')->nullable(); // Rating dari pelanggan (1-5)
            $table->boolean('pickup_service_available')->default(false);
            $table->decimal('minimum_pickup_amount', 10, 2)->nullable(); // Minimal jumlah untuk layanan antar jemput
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waste_banks');
    }
}; 