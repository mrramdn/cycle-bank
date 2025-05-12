<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WasteBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data user IDs dari file sementara
        $seederIds = json_decode(file_get_contents(storage_path('app/seeder_ids.json')), true);
        $wasteBankIds = $seederIds['waste_banks'] ?? [];
        
        // Dummy data untuk waste banks
        $wasteBanks = [
            'green_waste_bank' => [
                'name' => 'Green Waste Bank',
                'business_license' => 'WB-2023-001',
                'business_license_photo' => 'licenses/green-waste-bank.jpg',
                'address' => '456 Environmental Road, West Jakarta',
                'latitude' => -6.1754,
                'longitude' => 106.8272,
                'max_capacity' => 10000,
                'current_capacity' => 3500,
                'operational_hours' => json_encode([
                    'monday' => ['08:00-17:00'],
                    'tuesday' => ['08:00-17:00'],
                    'wednesday' => ['08:00-17:00'],
                    'thursday' => ['08:00-17:00'],
                    'friday' => ['08:00-17:00'],
                    'saturday' => ['09:00-15:00'],
                    'sunday' => []
                ]),
                'verification_level' => 'verified',
                'description' => 'A community-driven waste bank focused on educating the public about recycling.',
                'rating' => 4.5,
            ],
            'eco_waste_bank' => [
                'name' => 'Eco Waste Solutions',
                'business_license' => 'WB-2023-002',
                'business_license_photo' => 'licenses/eco-waste-bank.jpg',
                'address' => '789 Sustainability Street, East Jakarta',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'max_capacity' => 8000,
                'current_capacity' => 2000,
                'operational_hours' => json_encode([
                    'monday' => ['07:00-16:00'],
                    'tuesday' => ['07:00-16:00'],
                    'wednesday' => ['07:00-16:00'],
                    'thursday' => ['07:00-16:00'],
                    'friday' => ['07:00-16:00'],
                    'saturday' => ['08:00-13:00'],
                    'sunday' => []
                ]),
                'verification_level' => 'premium',
                'description' => 'A high-capacity waste bank with state-of-the-art sorting facilities.',
                'rating' => 4.8,
            ],
        ];
        
        foreach ($wasteBankIds as $key => $guid) {
            if (isset($wasteBanks[$key])) {
                DB::table('waste_banks')->insert([
                    'guid' => $guid,
                    'name' => $wasteBanks[$key]['name'],
                    'business_license' => $wasteBanks[$key]['business_license'],
                    'business_license_photo' => $wasteBanks[$key]['business_license_photo'],
                    'address' => $wasteBanks[$key]['address'],
                    'latitude' => $wasteBanks[$key]['latitude'],
                    'longitude' => $wasteBanks[$key]['longitude'],
                    'max_capacity' => $wasteBanks[$key]['max_capacity'],
                    'current_capacity' => $wasteBanks[$key]['current_capacity'],
                    'operational_hours' => $wasteBanks[$key]['operational_hours'],
                    'pickup_service' => rand(0, 1),
                    'pickup_radius' => rand(3, 10),
                    'verification_level' => $wasteBanks[$key]['verification_level'],
                    'description' => $wasteBanks[$key]['description'],
                    'profile_photo' => 'waste-banks/' . Str::slug($wasteBanks[$key]['name']) . '.jpg',
                    'rating' => $wasteBanks[$key]['rating'],
                    'cash_payment_enabled' => true,
                    'digital_payment_enabled' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
} 