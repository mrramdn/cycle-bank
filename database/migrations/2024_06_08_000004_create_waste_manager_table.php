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
            $table->string('company_name');
            $table->string('business_license')->nullable();
            $table->string('address');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->enum('verification_level', ['basic', 'verified', 'premium'])->default('basic');
            $table->string('company_type');
            $table->text('description');
            $table->json('optional_documents')->nullable();
            $table->string('profile_photo')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waste_managers');
    }
}; 