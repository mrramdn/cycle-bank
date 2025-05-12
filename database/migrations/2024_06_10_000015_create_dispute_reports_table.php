<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dispute_reports', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->uuid('transaction_guid'); // Transaksi yang bermasalah
            $table->uuid('reported_by'); // User yang melaporkan
            $table->uuid('reported_against')->nullable(); // User yang dilaporkan
            $table->string('reason_category'); // Kategori masalah: misrepresentation, non_payment, quality, etc
            $table->text('description'); // Deskripsi masalah
            $table->json('evidence_urls')->nullable(); // URL bukti (foto, dll)
            $table->enum('status', ['open', 'investigating', 'resolved', 'closed', 'escalated'])->default('open');
            $table->text('resolution')->nullable(); // Resolusi masalah
            $table->uuid('resolved_by')->nullable(); // Admin yang menyelesaikan
            $table->timestamp('resolved_at')->nullable(); // Waktu penyelesaian
            $table->decimal('refund_amount', 16, 2)->nullable(); // Jumlah pengembalian jika ada
            $table->boolean('is_refunded')->default(false); // Apakah sudah dikembalikan
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dispute_reports');
    }
}; 