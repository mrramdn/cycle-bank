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
            $table->char('position_guid', 36)->nullable();
            $table->char('department_guid', 36)->nullable();
            $table->char('division_guid', 36)->nullable();
            $table->text('fcm_token')->nullable();
            $table->string('id_employee')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('photo_url')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('status', ['active', 'pending', 'deleted'])->default('pending');
            $table->enum('type', ['Top Management', 'Middle Management', 'First Line Management', ''])->default('');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}; 