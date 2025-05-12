<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('waste_transactions', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->uuid('customer_guid')->nullable(); // pembeli/penjual - Nasabah
            $table->uuid('waste_bank_guid')->nullable(); // pembeli/penjual - Bank Sampah
            $table->uuid('waste_manager_guid')->nullable(); // pembeli - Pengelola Sampah
            $table->enum('transaction_type', ['customer_to_bank', 'bank_to_manager']);
            $table->enum('status', ['pending', 'confirmed', 'completed', 'canceled', 'disputed']);
            $table->enum('delivery_method', ['pickup', 'dropoff'])->nullable();
            $table->enum('payment_method', ['cash', 'digital_balance']);
            $table->boolean('is_paid')->default(false);
            $table->decimal('total_amount', 16, 2);
            $table->integer('total_points_earned')->default(0);
            $table->string('transaction_proof')->nullable(); // URL foto bukti transaksi
            $table->string('signature_url')->nullable();
            $table->json('location_data')->nullable(); // Data GPS
            $table->boolean('is_offline_sync')->default(false);
            $table->timestamp('synced_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waste_transactions');
    }
}; 