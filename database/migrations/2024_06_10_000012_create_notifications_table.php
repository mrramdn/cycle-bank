<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->uuid('user_guid'); // Penerima notifikasi
            $table->string('title');
            $table->text('message');
            $table->string('type'); // transaction, system, promotion, dll
            $table->string('icon')->nullable();
            $table->json('data')->nullable(); // Data tambahan
            $table->uuid('related_guid')->nullable(); // ID relasi (transaction_guid, dll)
            $table->string('action')->nullable(); // Aksi yang bisa dilakukan
            $table->string('action_url')->nullable(); // URL untuk aksi
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // Waktu kedaluwarsa
            $table->boolean('is_sent')->default(false); // Apakah telah terkirim
            $table->boolean('is_offline_sync')->default(false); // Apakah dibuat saat offline
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}; 