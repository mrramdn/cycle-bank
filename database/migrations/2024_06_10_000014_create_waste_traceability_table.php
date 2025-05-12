<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('waste_traceability', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->uuid('waste_category_guid');
            $table->decimal('quantity', 10, 2); // dalam kg
            $table->uuid('origin_customer_guid')->nullable(); // Nasabah asal
            $table->uuid('origin_transaction_guid')->nullable(); // Transaksi asal nasabah-bank
            $table->uuid('waste_bank_guid'); // Bank sampah yang mengelola
            $table->uuid('destination_transaction_guid')->nullable(); // Transaksi tujuan bank-pengelola
            $table->uuid('waste_manager_guid')->nullable(); // Pengelola sampah tujuan
            $table->enum('status', ['collected', 'in_bank', 'sold', 'processed', 'recycled', 'final'])->default('collected');
            $table->string('batch_number')->nullable(); // Nomor batch pengolahan (jika ada)
            $table->date('collection_date'); // Tanggal pengumpulan dari nasabah
            $table->date('processing_date')->nullable(); // Tanggal pengolahan oleh pengelola
            $table->text('processing_method')->nullable(); // Metode pengolahan
            $table->text('processing_result')->nullable(); // Hasil pengolahan
            $table->string('proof_url')->nullable(); // URL bukti foto
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waste_traceability');
    }
}; 