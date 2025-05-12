<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Pemula',
                'level_number' => 1,
                'min_points_required' => 0,
                'badge_image_url' => 'badges/pemula.png',
                'description' => 'Level pemula untuk pengguna baru.',
                'reward_multiplier' => 1.0,
                'benefits' => json_encode(['Akses ke fitur dasar aplikasi']),
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Pelopor',
                'level_number' => 2,
                'min_points_required' => 500,
                'badge_image_url' => 'badges/pelopor.png',
                'description' => 'Pengguna yang aktif mendaur ulang.',
                'reward_multiplier' => 1.1,
                'benefits' => json_encode(['Bonus poin 10%', 'Prioritas penjemputan sampah']),
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Pejuang',
                'level_number' => 3,
                'min_points_required' => 1500,
                'badge_image_url' => 'badges/pejuang.png',
                'description' => 'Pengguna yang konsisten mendaur ulang dan berkontribusi signifikan.',
                'reward_multiplier' => 1.2,
                'benefits' => json_encode(['Bonus poin 20%', 'Prioritas penjemputan sampah', 'Diskon redeem reward 5%']),
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Pahlawan',
                'level_number' => 4,
                'min_points_required' => 5000,
                'badge_image_url' => 'badges/pahlawan.png',
                'description' => 'Pengguna elit yang telah berkontribusi besar dalam daur ulang.',
                'reward_multiplier' => 1.3,
                'benefits' => json_encode(['Bonus poin 30%', 'Prioritas penjemputan sampah', 'Diskon redeem reward 10%', 'Akses ke reward eksklusif']),
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Legenda',
                'level_number' => 5,
                'min_points_required' => 10000,
                'badge_image_url' => 'badges/legenda.png',
                'description' => 'Level tertinggi untuk kontributor daur ulang terbaik.',
                'reward_multiplier' => 1.5,
                'benefits' => json_encode(['Bonus poin 50%', 'Prioritas penjemputan sampah', 'Diskon redeem reward 15%', 'Akses ke reward eksklusif', 'Program referral khusus']),
            ],
        ];

        foreach ($levels as $level) {
            DB::table('customer_levels')->insert([
                'guid' => $level['guid'],
                'name' => $level['name'],
                'level_number' => $level['level_number'],
                'min_points_required' => $level['min_points_required'],
                'badge_image_url' => $level['badge_image_url'],
                'description' => $level['description'],
                'reward_multiplier' => $level['reward_multiplier'],
                'benefits' => $level['benefits'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 