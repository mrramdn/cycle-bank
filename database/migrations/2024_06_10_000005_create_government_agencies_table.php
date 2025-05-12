<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('government_agencies', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->uuid('user_guid'); // Relasi ke user
            $table->string('agency_name');
            $table->string('department');
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code');
            $table->string('phone_number');
            $table->string('email');
            $table->string('website')->nullable();
            $table->string('logo_url')->nullable();
            $table->json('jurisdiction_areas'); // Area tanggung jawab (kota/kabupaten)
            $table->string('authorized_person'); // Nama pejabat yang bertanggung jawab
            $table->string('position'); // Jabatan pejabat
            $table->string('auth_letter_number')->nullable(); // Nomor surat tugas
            $table->boolean('is_active')->default(true);
            $table->json('access_level')->nullable(); // Tingkat akses ke data (full, restricted, etc)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('government_agencies');
    }
}; 