<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sync_logs', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->uuid('user_guid'); // Pengguna yang melakukan sinkronisasi
            $table->string('device_id'); // ID perangkat
            $table->enum('status', ['pending', 'success', 'failed', 'partial'])->default('pending');
            $table->dateTime('started_at');
            $table->dateTime('completed_at')->nullable();
            $table->integer('total_items'); // Jumlah total item yang perlu disinkronkan
            $table->integer('synced_items')->default(0); // Jumlah item yang berhasil disinkronkan
            $table->integer('failed_items')->default(0); // Jumlah item yang gagal disinkronkan
            $table->json('entity_counts')->nullable(); // Jumlah per jenis entitas yang disinkronkan
            $table->json('errors')->nullable(); // Detail error jika ada
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sync_logs');
    }
}; 