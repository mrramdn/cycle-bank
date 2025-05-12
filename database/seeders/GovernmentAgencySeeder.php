<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GovernmentAgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load seeder IDs
        $seedIds = json_decode(file_get_contents(storage_path('app/seeder_ids.json')), true);
        $governmentIds = $seedIds['governments'] ?? [];
        
        // Define some sample government agencies
        $agencies = [
            [
                'user_guid' => $governmentIds['env_department'] ?? Str::uuid()->toString(),
                'agency_name' => 'Departemen Lingkungan Hidup',
                'department' => 'Divisi Pengelolaan Sampah',
                'address' => 'Jl. Lingkungan Hijau No. 123',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '10110',
                'phone_number' => '021-5551234',
                'email' => 'pengelolaan.sampah@dlh.go.id',
                'website' => 'www.dlh.go.id',
                'logo_url' => 'logos/dlh.png',
                'jurisdiction_areas' => json_encode(['Jakarta Pusat', 'Jakarta Utara', 'Jakarta Barat', 'Jakarta Selatan', 'Jakarta Timur']),
                'authorized_person' => 'Dr. Budi Santoso',
                'position' => 'Kepala Divisi Pengelolaan Sampah',
                'auth_letter_number' => 'SK/DLH/2023/456',
                'access_level' => json_encode(['level' => 'full', 'features' => ['analytics', 'monitoring', 'reporting']]),
            ],
            [
                'user_guid' => $governmentIds['city_sanitation'] ?? Str::uuid()->toString(),
                'agency_name' => 'Dinas Kebersihan Kota',
                'department' => 'Bidang Daur Ulang',
                'address' => 'Jl. Bersih No. 45',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'postal_code' => '40115',
                'phone_number' => '022-4205678',
                'email' => 'daur.ulang@kebersihan-bdg.go.id',
                'website' => 'www.kebersihan-bdg.go.id',
                'logo_url' => 'logos/dkb.png',
                'jurisdiction_areas' => json_encode(['Bandung Utara', 'Bandung Selatan', 'Bandung Timur', 'Bandung Barat']),
                'authorized_person' => 'Ir. Siti Nurhaliza',
                'position' => 'Kepala Bidang Daur Ulang',
                'auth_letter_number' => 'SK/DKB/2023/789',
                'access_level' => json_encode(['level' => 'restricted', 'features' => ['analytics', 'monitoring']]),
            ],
        ];

        // Insert agencies
        foreach ($agencies as $agency) {
            DB::table('government_agencies')->insert([
                'guid' => Str::uuid()->toString(),
                'user_guid' => $agency['user_guid'],
                'agency_name' => $agency['agency_name'],
                'department' => $agency['department'],
                'address' => $agency['address'],
                'city' => $agency['city'],
                'province' => $agency['province'],
                'postal_code' => $agency['postal_code'],
                'phone_number' => $agency['phone_number'],
                'email' => $agency['email'],
                'website' => $agency['website'],
                'logo_url' => $agency['logo_url'],
                'jurisdiction_areas' => $agency['jurisdiction_areas'],
                'authorized_person' => $agency['authorized_person'],
                'position' => $agency['position'],
                'auth_letter_number' => $agency['auth_letter_number'],
                'is_active' => true,
                'access_level' => $agency['access_level'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 