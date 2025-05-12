<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('waste_managers', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->uuid('user_guid'); // Relasi ke user
            $table->string('company_name');
            $table->string('business_license_number');
            $table->string('environmental_permit_number')->nullable();
            $table->string('tax_id_number');
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code');
            $table->string('contact_person');
            $table->string('phone_number');
            $table->string('email');
            $table->string('website')->nullable();
            $table->text('company_description')->nullable();
            $table->string('logo_url')->nullable();
            $table->json('operation_scope')->nullable(); // Jenis pengolahan yang dilakukan
            $table->json('accepted_waste_types')->nullable();
            $table->decimal('processing_capacity', 10, 2)->nullable(); // Kapasitas pengolahan (ton/hari)
            $table->json('geo_location')->nullable(); // Latitude & longitude
            $table->enum('verification_status', ['unverified', 'basic', 'verified', 'premium'])->default('unverified');
            $table->timestamp('verification_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('balance', 16, 2)->default(0); // Saldo deposit
            $table->json('service_areas')->nullable(); // Area layanan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waste_managers');
    }
}; 