<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('waste_bank_prices', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->uuid('waste_bank_guid');
            $table->uuid('waste_category_guid');
            $table->decimal('buy_price', 12, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waste_bank_prices');
    }
}; 