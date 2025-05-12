<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GovernmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data user IDs dari file sementara
        $seederIds = json_decode(file_get_contents(storage_path('app/seeder_ids.json')), true);
        $governmentIds = $seederIds['governments'] ?? [];
        
        // Dummy data untuk government entities
        $governments = [
            'env_department' => [
                'institution' => 'Ministry of Environment',
                'department' => 'Waste Management Division',
                'position' => 'Senior Officer',
                'access_level' => 3,
            ],
            'city_sanitation' => [
                'institution' => 'Jakarta City Government',
                'department' => 'Sanitation and Cleanliness Department',
                'position' => 'Field Supervisor',
                'access_level' => 2,
            ],
        ];
        
        foreach ($governmentIds as $key => $guid) {
            if (isset($governments[$key])) {
                DB::table('governments')->insert([
                    'guid' => $guid,
                    'institution' => $governments[$key]['institution'],
                    'department' => $governments[$key]['department'],
                    'position' => $governments[$key]['position'],
                    'access_level' => $governments[$key]['access_level'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
} 