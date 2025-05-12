<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->unique();
            $table->enum('role', ['customer', 'waste_bank', 'waste_manager', 'government']);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login')->nullable();
            $table->string('device_fingerprint')->nullable();
            $table->json('notification_settings')->nullable();
            $table->string('fcm_token')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}; 