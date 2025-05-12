<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WasteManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data user IDs dari file sementara
        $seederIds = json_decode(file_get_contents(storage_path('app/seeder_ids.json')), true);
        $wasteManagerIds = $seederIds['waste_managers'] ?? [];
        
        // Dummy data untuk waste managers
        $wasteManagers = [
            'recycle_corp' => [
                'company_name' => 'Recycle Corp',
                'business_license_number' => 'WM-2023-001',
                'environmental_permit_number' => 'EP-2023-001',
                'tax_id_number' => '123.456.789.0-333.000',
                'address' => '123 Industrial Area',
                'city' => 'North Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '14350',
                'contact_person' => 'Darmawan Wijaya',
                'phone_number' => '081234567893',
                'email' => 'contact@recyclecorp.com',
                'website' => 'www.recyclecorp.com',
                'company_description' => 'A large-scale industrial recycler specializing in plastic processing.',
                'logo_url' => 'waste-managers/recycle-corp-logo.jpg',
                'operation_scope' => json_encode(['plastic_recycling', 'granulation', 'pelletizing']),
                'accepted_waste_types' => json_encode(['PET', 'HDPE', 'LDPE', 'PP']),
                'processing_capacity' => 250.00, // 250 ton/hari
                'geo_location' => json_encode(['latitude' => -6.1382, 'longitude' => 106.8132]),
                'verification_status' => 'verified',
                'is_active' => true,
                'balance' => 12500000.00,
                'service_areas' => json_encode(['North Jakarta', 'Central Jakarta', 'West Jakarta']),
            ],
            'eco_processors' => [
                'company_name' => 'Eco Processors Ltd',
                'business_license_number' => 'WM-2023-002',
                'environmental_permit_number' => 'EP-2023-002',
                'tax_id_number' => '123.456.789.0-444.000',
                'address' => '456 Manufacturing Zone',
                'city' => 'East Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '13310',
                'contact_person' => 'Maya Indira',
                'phone_number' => '081234567894',
                'email' => 'contact@ecoprocessors.com',
                'website' => 'www.ecoprocessors.com',
                'company_description' => 'A comprehensive waste processing facility handling various types of recyclables.',
                'logo_url' => 'waste-managers/eco-processors-logo.jpg',
                'operation_scope' => json_encode(['plastic_recycling', 'metal_recycling', 'paper_recycling', 'glass_recycling', 'e-waste']),
                'accepted_waste_types' => json_encode(['PET', 'HDPE', 'LDPE', 'PP', 'Aluminum', 'Steel', 'Paper', 'Cardboard', 'Glass', 'Electronics']),
                'processing_capacity' => 350.00, // 350 ton/hari
                'geo_location' => json_encode(['latitude' => -6.2541, 'longitude' => 106.8642]),
                'verification_status' => 'premium',
                'is_active' => true,
                'balance' => 20000000.00,
                'service_areas' => json_encode(['East Jakarta', 'South Jakarta', 'Central Jakarta']),
            ],
        ];
        
        foreach ($wasteManagerIds as $key => $guid) {
            if (isset($wasteManagers[$key])) {
                DB::table('waste_managers')->insert([
                    'guid' => $guid,
                    'user_guid' => $guid,
                    'company_name' => $wasteManagers[$key]['company_name'],
                    'business_license_number' => $wasteManagers[$key]['business_license_number'],
                    'environmental_permit_number' => $wasteManagers[$key]['environmental_permit_number'],
                    'tax_id_number' => $wasteManagers[$key]['tax_id_number'],
                    'address' => $wasteManagers[$key]['address'],
                    'city' => $wasteManagers[$key]['city'],
                    'province' => $wasteManagers[$key]['province'],
                    'postal_code' => $wasteManagers[$key]['postal_code'],
                    'contact_person' => $wasteManagers[$key]['contact_person'],
                    'phone_number' => $wasteManagers[$key]['phone_number'],
                    'email' => $wasteManagers[$key]['email'],
                    'website' => $wasteManagers[$key]['website'],
                    'company_description' => $wasteManagers[$key]['company_description'],
                    'logo_url' => $wasteManagers[$key]['logo_url'],
                    'operation_scope' => $wasteManagers[$key]['operation_scope'],
                    'accepted_waste_types' => $wasteManagers[$key]['accepted_waste_types'],
                    'processing_capacity' => $wasteManagers[$key]['processing_capacity'],
                    'geo_location' => $wasteManagers[$key]['geo_location'],
                    'verification_status' => $wasteManagers[$key]['verification_status'],
                    'verification_date' => now()->subMonths(rand(1, 6)),
                    'is_active' => $wasteManagers[$key]['is_active'],
                    'balance' => $wasteManagers[$key]['balance'],
                    'service_areas' => $wasteManagers[$key]['service_areas'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
} 