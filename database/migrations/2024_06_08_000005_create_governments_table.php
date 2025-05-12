<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('governments', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->string('institution');
            $table->string('department');
            $table->string('position');
            $table->integer('access_level')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('governments');
    }
}; 