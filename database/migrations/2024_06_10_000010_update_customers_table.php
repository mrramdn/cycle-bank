<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->uuid('level_guid')->nullable()->after('points');
            $table->json('badges_earned')->nullable()->after('level_guid');
            $table->integer('total_transactions')->default(0)->after('badges_earned');
            $table->decimal('total_waste_recycled', 12, 2)->default(0)->after('total_transactions');
            $table->integer('leaderboard_rank')->nullable()->after('total_waste_recycled');
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'level_guid',
                'badges_earned',
                'total_transactions',
                'total_waste_recycled',
                'leaderboard_rank'
            ]);
        });
    }
}; 