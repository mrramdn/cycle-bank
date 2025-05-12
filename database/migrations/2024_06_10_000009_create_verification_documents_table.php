<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('verification_documents', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->uuid('user_guid');
            $table->string('document_type'); // KTP, SIUP, Izin Lingkungan, dll
            $table->string('document_number')->nullable();
            $table->string('document_url'); // URL file dokumen
            $table->date('issued_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->uuid('verified_by')->nullable(); // Admin yang memverifikasi
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('verification_documents');
    }
}; 