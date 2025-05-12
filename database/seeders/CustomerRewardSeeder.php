<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerRewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get customers and rewards
        $customers = DB::table('customers')->get();
        $rewards = DB::table('rewards')->get();
        
        // If no customers or rewards, exit early
        if ($customers->isEmpty() || $rewards->isEmpty()) {
            $this->command->info('No customers or rewards found. Skipping customer reward seeding.');
            return;
        }
        
        // Create some sample reward redemptions
        $rewardStatuses = ['pending', 'processed', 'delivered', 'canceled'];
        
        foreach ($customers as $customer) {
            // Each customer redeems 0-3 rewards
            $redeemCount = rand(0, 3);
            
            for ($i = 0; $i < $redeemCount; $i++) {
                // Get a random reward
                $reward = $rewards->random();
                
                // Get a random status
                $status = $rewardStatuses[array_rand($rewardStatuses)];
                
                // If it's delivered, set a past date for redemption
                $redeemedAt = match($status) {
                    'delivered' => now()->subDays(rand(5, 30)),
                    'processed' => now()->subDays(rand(1, 4)),
                    'pending' => now()->subHours(rand(1, 24)),
                    'canceled' => now()->subDays(rand(1, 10)),
                };
                
                // Insert the redemption record
                DB::table('customer_rewards')->insert([
                    'guid' => Str::uuid()->toString(),
                    'customer_guid' => $customer->guid,
                    'reward_guid' => $reward->guid,
                    'redeemed_at' => $redeemedAt,
                    'status' => $status,
                    'notes' => $status === 'canceled' ? 'Dibatalkan oleh pengguna' : null,
                    'points_used' => $reward->points_required,
                    'created_at' => $redeemedAt,
                    'updated_at' => $status === 'pending' ? $redeemedAt : now()->subDays(rand(0, 2)),
                ]);
            }
        }
    }
} 