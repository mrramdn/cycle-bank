<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->uuid('user_guid'); // Guid pengguna yang melakukan penarikan
            $table->enum('actor_type', ['customer', 'waste_bank', 'waste_manager']); // Jenis aktor
            $table->decimal('amount', 16, 2); // Jumlah yang ditarik
            $table->enum('status', ['pending', 'approved', 'completed', 'rejected'])->default('pending');
            $table->string('payment_method'); // Metode pembayaran (transfer bank, e-wallet, dll)
            $table->string('account_number'); // Nomor rekening/akun
            $table->string('account_name'); // Nama pemilik rekening/akun
            $table->string('bank_or_provider'); // Nama bank atau provider e-wallet
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->text('rejection_reason')->nullable(); // Alasan penolakan
            $table->uuid('approved_by')->nullable(); // Admin yang menyetujui
            $table->uuid('processed_by')->nullable(); // Admin yang memproses pembayaran
            $table->timestamp('processed_at')->nullable(); // Waktu diproses
            $table->string('transaction_proof')->nullable(); // Bukti transfer/pembayaran
            $table->boolean('is_offline_sync')->default(false); // Apakah transaksi offline
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('withdrawals');
    }
}; 