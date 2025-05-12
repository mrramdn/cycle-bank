<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('waste_categories', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->string('name');
            $table->string('icon_url')->nullable();
            $table->string('image_url')->nullable();
            $table->string('color_code')->nullable(); // Kode warna untuk UI
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('points_per_kg')->default(1); // Poin yang didapat per kg
            $table->json('handling_instructions')->nullable(); // Panduan penanganan
            $table->string('material_type')->nullable(); // Jenis material (plastik, kertas, dll)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waste_categories');
    }
}; 