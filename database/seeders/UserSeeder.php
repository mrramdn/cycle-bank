<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definisikan beberapa user IDs untuk digunakan di profile seeders nanti
        $customerIds = [
            'john_customer' => Str::uuid()->toString(),
            'sarah_customer' => Str::uuid()->toString(),
            'ali_customer' => Str::uuid()->toString(),
        ];
        
        $wasteBankIds = [
            'green_waste_bank' => Str::uuid()->toString(),
            'eco_waste_bank' => Str::uuid()->toString(),
        ];
        
        $wasteManagerIds = [
            'recycle_corp' => Str::uuid()->toString(),
            'eco_processors' => Str::uuid()->toString(),
        ];
        
        $governmentIds = [
            'env_department' => Str::uuid()->toString(),
            'city_sanitation' => Str::uuid()->toString(),
        ];
        
        // Save to temporary file for reference in other seeders
        file_put_contents(
            storage_path('app/seeder_ids.json'), 
            json_encode([
                'customers' => $customerIds,
                'waste_banks' => $wasteBankIds,
                'waste_managers' => $wasteManagerIds,
                'governments' => $governmentIds,
            ])
        );
        
        // Customer Users
        foreach ($customerIds as $key => $id) {
            DB::table('users')->insert([
                'guid' => $id,
                'email' => $key . '@example.com',
                'password' => Hash::make('password'),
                'phone' => '08' . rand(10000000, 99999999),
                'role' => 'customer',
                'is_verified' => true,
                'is_active' => true,
                'last_login' => now()->subDays(rand(0, 30)),
                'device_fingerprint' => Str::random(10),
                'notification_settings' => json_encode(['email' => true, 'push' => true, 'sms' => false]),
                'fcm_token' => 'fcm_' . Str::random(20),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Waste Bank Users
        foreach ($wasteBankIds as $key => $id) {
            DB::table('users')->insert([
                'guid' => $id,
                'email' => $key . '@example.com',
                'password' => Hash::make('password'),
                'phone' => '08' . rand(10000000, 99999999),
                'role' => 'waste_bank',
                'is_verified' => true,
                'is_active' => true,
                'last_login' => now()->subDays(rand(0, 30)),
                'device_fingerprint' => Str::random(10),
                'notification_settings' => json_encode(['email' => true, 'push' => true, 'sms' => true]),
                'fcm_token' => 'fcm_' . Str::random(20),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Waste Manager Users
        foreach ($wasteManagerIds as $key => $id) {
            DB::table('users')->insert([
                'guid' => $id,
                'email' => $key . '@example.com',
                'password' => Hash::make('password'),
                'phone' => '08' . rand(10000000, 99999999),
                'role' => 'waste_manager',
                'is_verified' => true,
                'is_active' => true,
                'last_login' => now()->subDays(rand(0, 30)),
                'device_fingerprint' => Str::random(10),
                'notification_settings' => json_encode(['email' => true, 'push' => false, 'sms' => true]),
                'fcm_token' => 'fcm_' . Str::random(20),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Government Users
        foreach ($governmentIds as $key => $id) {
            DB::table('users')->insert([
                'guid' => $id,
                'email' => $key . '@example.com',
                'password' => Hash::make('password'),
                'phone' => '08' . rand(10000000, 99999999),
                'role' => 'government',
                'is_verified' => true,
                'is_active' => true,
                'last_login' => now()->subDays(rand(0, 30)),
                'device_fingerprint' => Str::random(10),
                'notification_settings' => json_encode(['email' => true, 'push' => false, 'sms' => false]),
                'fcm_token' => 'fcm_' . Str::random(20),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Admin user
        DB::table('users')->insert([
            'guid' => Str::uuid()->toString(),
            'email' => 'admin@cyclebank.com',
            'password' => Hash::make('admin123'),
            'phone' => '08123456789',
            'role' => 'waste_manager', // or another role with admin privileges
            'is_verified' => true,
            'is_active' => true,
            'last_login' => now(),
            'device_fingerprint' => Str::random(10),
            'notification_settings' => json_encode(['email' => true, 'push' => true, 'sms' => true]),
            'fcm_token' => 'fcm_' . Str::random(20),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
} 