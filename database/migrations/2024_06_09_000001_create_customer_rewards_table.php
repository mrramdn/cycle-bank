<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_rewards', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->uuid('customer_guid');
            $table->uuid('reward_guid');
            $table->timestamp('redeemed_at');
            $table->enum('status', ['pending', 'processed', 'delivered', 'canceled'])->default('pending');
            $table->text('notes')->nullable();
            $table->integer('points_used');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_rewards');
    }
}; 