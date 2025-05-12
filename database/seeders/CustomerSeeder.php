<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data user IDs dari file sementara
        $seederIds = json_decode(file_get_contents(storage_path('app/seeder_ids.json')), true);
        $customerIds = $seederIds['customers'] ?? [];
        
        // Ambil level random untuk diassign ke customer
        $levelGuids = DB::table('customer_levels')->pluck('guid')->toArray();
        
        // Dummy data untuk customers
        $customers = [
            'john_customer' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'address' => '123 Green Street, South Jakarta',
                'profile_photo' => 'customers/john.jpg',
                'total_waste_sold' => 125.5,
                'points' => 300,
            ],
            'sarah_customer' => [
                'first_name' => 'Sarah',
                'last_name' => 'Smith',
                'address' => '45 Eco Avenue, Central Jakarta',
                'profile_photo' => 'customers/sarah.jpg',
                'total_waste_sold' => 85.2,
                'points' => 180,
            ],
            'ali_customer' => [
                'first_name' => 'Ali',
                'last_name' => 'Rahman',
                'address' => '78 Recycle Lane, North Jakarta',
                'profile_photo' => 'customers/ali.jpg',
                'total_waste_sold' => 210.8,
                'points' => 450,
            ],
        ];
        
        foreach ($customerIds as $key => $guid) {
            if (isset($customers[$key])) {
                DB::table('customers')->insert([
                    'guid' => $guid,
                    'user_guid' => $guid, // User GUID matches customer GUID
                    'first_name' => $customers[$key]['first_name'],
                    'last_name' => $customers[$key]['last_name'],
                    'address' => $customers[$key]['address'],
                    'profile_photo' => $customers[$key]['profile_photo'],
                    'total_waste_sold' => $customers[$key]['total_waste_sold'],
                    'points' => $customers[$key]['points'],
                    'level_guid' => $levelGuids[array_rand($levelGuids)],
                    'badges_earned' => json_encode(['recycling_starter', 'first_transaction']),
                    'total_transactions' => rand(5, 20),
                    'total_waste_recycled' => $customers[$key]['total_waste_sold'] + rand(10, 50),
                    'leaderboard_rank' => rand(1, 100),
                    'current_progress' => rand(1, 20),
                    'signature_url' => 'signatures/' . Str::slug($customers[$key]['first_name']) . '.png',
                    'preferred_payment_method' => rand(0, 2), // Assuming it's an enum or integer field
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
} 