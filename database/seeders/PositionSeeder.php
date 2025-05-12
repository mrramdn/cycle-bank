<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            [
                'guid' => Str::uuid()->toString(),
                'position_name' => 'CEO',
                'status' => 'active',
            ],
            [
                'guid' => Str::uuid()->toString(),
                'position_name' => 'Manager',
                'status' => 'active',
            ],
            [
                'guid' => Str::uuid()->toString(),
                'position_name' => 'Supervisor',
                'status' => 'active',
            ],
            [
                'guid' => Str::uuid()->toString(),
                'position_name' => 'Staff',
                'status' => 'active',
            ],
            [
                'guid' => Str::uuid()->toString(),
                'position_name' => 'Volunteer',
                'status' => 'active',
            ],
        ];

        foreach ($positions as $position) {
            DB::table('positions')->insert([
                'guid' => $position['guid'],
                'position_name' => $position['position_name'],
                'status' => $position['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 