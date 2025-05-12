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
                'business_license' => 'WM-2023-001',
                'address' => '123 Industrial Area, North Jakarta',
                'latitude' => -6.1382,
                'longitude' => 106.8132,
                'verification_level' => 'verified',
                'company_type' => 'Plastic Recycling',
                'description' => 'A large-scale industrial recycler specializing in plastic processing.',
                'optional_documents' => json_encode([
                    'environmental_permit' => 'permits/recycle-corp-env.pdf',
                    'safety_certificate' => 'certificates/recycle-corp-safety.pdf'
                ]),
                'rating' => 4.6,
            ],
            'eco_processors' => [
                'company_name' => 'Eco Processors Ltd',
                'business_license' => 'WM-2023-002',
                'address' => '456 Manufacturing Zone, East Jakarta',
                'latitude' => -6.2541,
                'longitude' => 106.8642,
                'verification_level' => 'premium',
                'company_type' => 'Multi-Material Processing',
                'description' => 'A comprehensive waste processing facility handling various types of recyclables.',
                'optional_documents' => json_encode([
                    'environmental_permit' => 'permits/eco-processors-env.pdf',
                    'international_certification' => 'certificates/eco-processors-iso.pdf'
                ]),
                'rating' => 4.9,
            ],
        ];
        
        foreach ($wasteManagerIds as $key => $guid) {
            if (isset($wasteManagers[$key])) {
                DB::table('waste_managers')->insert([
                    'guid' => $guid,
                    'company_name' => $wasteManagers[$key]['company_name'],
                    'business_license' => $wasteManagers[$key]['business_license'],
                    'address' => $wasteManagers[$key]['address'],
                    'latitude' => $wasteManagers[$key]['latitude'],
                    'longitude' => $wasteManagers[$key]['longitude'],
                    'verification_level' => $wasteManagers[$key]['verification_level'],
                    'company_type' => $wasteManagers[$key]['company_type'],
                    'description' => $wasteManagers[$key]['description'],
                    'optional_documents' => $wasteManagers[$key]['optional_documents'],
                    'profile_photo' => 'waste-managers/' . Str::slug($wasteManagers[$key]['company_name']) . '.jpg',
                    'rating' => $wasteManagers[$key]['rating'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
} 