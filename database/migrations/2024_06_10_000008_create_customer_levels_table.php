<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_levels', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->string('name'); // Pemula, Pelopor, Pahlawan, dll
            $table->integer('level_number');
            $table->integer('min_points_required');
            $table->string('badge_image_url')->nullable();
            $table->text('description')->nullable();
            $table->decimal('reward_multiplier', 4, 2)->default(1.0); // Pengali reward
            $table->boolean('is_active')->default(true);
            $table->json('benefits')->nullable(); // Keuntungan khusus di level ini
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_levels');
    }
}; 