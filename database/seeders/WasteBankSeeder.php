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
                'business_license_number' => 'WB-2023-001',
                'tax_id_number' => '123.456.789.0-111.000',
                'address' => '456 Environmental Road',
                'city' => 'West Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '11480',
                'contact_person' => 'Ahmad Saputra',
                'phone_number' => '081234567891',
                'email' => 'contact@greenwaste.com',
                'website' => 'www.greenwaste.com',
                'description' => 'A community-driven waste bank focused on educating the public about recycling.',
                'logo_url' => 'waste-banks/green-waste-bank-logo.jpg',
                'operation_hours' => json_encode([
                    'monday' => ['08:00-17:00'],
                    'tuesday' => ['08:00-17:00'],
                    'wednesday' => ['08:00-17:00'],
                    'thursday' => ['08:00-17:00'],
                    'friday' => ['08:00-17:00'],
                    'saturday' => ['09:00-15:00'],
                    'sunday' => []
                ]),
                'accepted_waste_types' => json_encode(['plastic', 'paper', 'glass', 'metal']),
                'storage_capacity' => 10000.00, 
                'current_storage' => 3500.00,
                'geo_location' => json_encode(['latitude' => -6.1754, 'longitude' => 106.8272]),
                'verification_status' => 'verified',
                'is_active' => true,
                'balance' => 5000000.00,
                'rating' => 4,
                'pickup_service_available' => true,
                'minimum_pickup_amount' => 5.00
            ],
            'eco_waste_bank' => [
                'name' => 'Eco Waste Solutions',
                'business_license_number' => 'WB-2023-002',
                'tax_id_number' => '123.456.789.0-222.000',
                'address' => '789 Sustainability Street',
                'city' => 'East Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '13210',
                'contact_person' => 'Budi Hartono',
                'phone_number' => '081234567892',
                'email' => 'info@ecowaste.com',
                'website' => 'www.ecowaste.com',
                'description' => 'A high-capacity waste bank with state-of-the-art sorting facilities.',
                'logo_url' => 'waste-banks/eco-waste-bank-logo.jpg',
                'operation_hours' => json_encode([
                    'monday' => ['07:00-16:00'],
                    'tuesday' => ['07:00-16:00'],
                    'wednesday' => ['07:00-16:00'],
                    'thursday' => ['07:00-16:00'],
                    'friday' => ['07:00-16:00'],
                    'saturday' => ['08:00-13:00'],
                    'sunday' => []
                ]),
                'accepted_waste_types' => json_encode(['plastic', 'paper', 'glass', 'metal', 'electronics', 'organic']),
                'storage_capacity' => 8000.00,
                'current_storage' => 2000.00,
                'geo_location' => json_encode(['latitude' => -6.2088, 'longitude' => 106.8456]),
                'verification_status' => 'premium',
                'is_active' => true,
                'balance' => 7500000.00,
                'rating' => 5,
                'pickup_service_available' => true,
                'minimum_pickup_amount' => 3.00
            ],
        ];
        
        foreach ($wasteBankIds as $key => $guid) {
            if (isset($wasteBanks[$key])) {
                DB::table('waste_banks')->insert([
                    'guid' => $guid,
                    'user_guid' => $guid,
                    'name' => $wasteBanks[$key]['name'],
                    'business_license_number' => $wasteBanks[$key]['business_license_number'],
                    'tax_id_number' => $wasteBanks[$key]['tax_id_number'],
                    'address' => $wasteBanks[$key]['address'],
                    'city' => $wasteBanks[$key]['city'],
                    'province' => $wasteBanks[$key]['province'],
                    'postal_code' => $wasteBanks[$key]['postal_code'],
                    'contact_person' => $wasteBanks[$key]['contact_person'],
                    'phone_number' => $wasteBanks[$key]['phone_number'],
                    'email' => $wasteBanks[$key]['email'],
                    'website' => $wasteBanks[$key]['website'],
                    'description' => $wasteBanks[$key]['description'],
                    'logo_url' => $wasteBanks[$key]['logo_url'],
                    'operation_hours' => $wasteBanks[$key]['operation_hours'],
                    'accepted_waste_types' => $wasteBanks[$key]['accepted_waste_types'],
                    'storage_capacity' => $wasteBanks[$key]['storage_capacity'],
                    'current_storage' => $wasteBanks[$key]['current_storage'],
                    'geo_location' => $wasteBanks[$key]['geo_location'],
                    'verification_status' => $wasteBanks[$key]['verification_status'],
                    'verification_date' => now()->subMonths(rand(1, 6)),
                    'is_active' => $wasteBanks[$key]['is_active'],
                    'balance' => $wasteBanks[$key]['balance'],
                    'rating' => $wasteBanks[$key]['rating'],
                    'pickup_service_available' => $wasteBanks[$key]['pickup_service_available'],
                    'minimum_pickup_amount' => $wasteBanks[$key]['minimum_pickup_amount'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
} 