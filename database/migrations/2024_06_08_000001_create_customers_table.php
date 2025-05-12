<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nasabah', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->uuid('reward_guid');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address')->nullable();
            $table->string('profile_photo')->nullable();
            $table->decimal('total_waste_sold', 12, 2)->default(0);
            $table->integer('points')->default(0);
            $table->decimal('current_progress', 12, 2)->default(0);
            $table->string('signature_url')->nullable();
            $table->string('preferred_payment_method')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nasabah');
    }
}; 