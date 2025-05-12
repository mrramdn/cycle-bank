<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rewards = [
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Eco-Friendly Water Bottle',
                'description' => 'A reusable water bottle made from recycled materials. Perfect for reducing plastic waste!',
                'points_required' => 100,
                'stock' => 50,
                'image_url' => 'rewards/water-bottle.jpg',
                'is_active' => true,
                'expiry_date' => now()->addMonths(3),
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Grocery Discount Voucher',
                'description' => 'Get 10% off your next grocery shopping at GreenMart.',
                'points_required' => 150,
                'stock' => 100,
                'image_url' => 'rewards/grocery-voucher.jpg',
                'is_active' => true,
                'expiry_date' => now()->addMonths(2),
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Recycled Tote Bag',
                'description' => 'A stylish tote bag made from recycled fabric. Say no to plastic bags!',
                'points_required' => 75,
                'stock' => 200,
                'image_url' => 'rewards/tote-bag.jpg',
                'is_active' => true,
                'expiry_date' => now()->addMonths(6),
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Energy Bill Discount',
                'description' => 'Receive a $5 discount on your next energy bill.',
                'points_required' => 300,
                'stock' => 50,
                'image_url' => 'rewards/energy-bill.jpg',
                'is_active' => true,
                'expiry_date' => now()->addMonths(1),
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Eco-Workshop Session',
                'description' => 'Join an exclusive workshop on creating home items from recycled materials.',
                'points_required' => 500,
                'stock' => 20,
                'image_url' => 'rewards/workshop.jpg',
                'is_active' => true,
                'expiry_date' => now()->addMonths(2),
            ],
        ];

        foreach ($rewards as $reward) {
            DB::table('rewards')->insert([
                'guid' => $reward['guid'],
                'name' => $reward['name'],
                'description' => $reward['description'],
                'points_required' => $reward['points_required'],
                'stock' => $reward['stock'],
                'image_url' => $reward['image_url'],
                'is_active' => $reward['is_active'],
                'expiry_date' => $reward['expiry_date'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 