<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_balances', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->uuid('customer_guid');
            $table->decimal('current_balance', 16, 2)->default(0);
            $table->decimal('total_earned', 16, 2)->default(0);
            $table->decimal('total_withdrawn', 16, 2)->default(0);
            $table->decimal('minimum_withdrawal', 16, 2)->default(0);
            $table->boolean('withdrawal_eligibility')->default(false);
            $table->timestamp('last_transaction_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_balances');
    }
}; 