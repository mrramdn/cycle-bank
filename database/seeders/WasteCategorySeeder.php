<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WasteCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wasteCategories = [
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'PET Plastic (1)',
                'description' => 'Polyethylene Terephthalate - Common in water bottles, beverage containers, and some food packaging.',
                'unit' => 'kg',
                'image_url' => 'waste-categories/pet.jpg',
                'is_active' => true,
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'HDPE Plastic (2)',
                'description' => 'High-Density Polyethylene - Used in milk jugs, detergent bottles, and some plastic bags.',
                'unit' => 'kg',
                'image_url' => 'waste-categories/hdpe.jpg',
                'is_active' => true,
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Cardboard',
                'description' => 'Cardboard boxes, packaging materials, and paper products.',
                'unit' => 'kg',
                'image_url' => 'waste-categories/cardboard.jpg',
                'is_active' => true,
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Aluminum',
                'description' => 'Aluminum cans, foils, and some packaging materials.',
                'unit' => 'kg',
                'image_url' => 'waste-categories/aluminum.jpg',
                'is_active' => true,
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Glass',
                'description' => 'Glass bottles, jars, and containers of various colors.',
                'unit' => 'kg',
                'image_url' => 'waste-categories/glass.jpg',
                'is_active' => true,
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Paper',
                'description' => 'Newspapers, magazines, office paper, and printed materials.',
                'unit' => 'kg',
                'image_url' => 'waste-categories/paper.jpg',
                'is_active' => true,
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'E-Waste',
                'description' => 'Electronic waste including small devices, cables, and components.',
                'unit' => 'kg',
                'image_url' => 'waste-categories/e-waste.jpg',
                'is_active' => true,
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Organic Waste',
                'description' => 'Food scraps, yard waste, and other biodegradable materials.',
                'unit' => 'kg',
                'image_url' => 'waste-categories/organic.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($wasteCategories as $category) {
            DB::table('waste_categories')->insert([
                'guid' => $category['guid'],
                'name' => $category['name'],
                'description' => $category['description'],
                'unit' => $category['unit'],
                'image_url' => $category['image_url'],
                'is_active' => $category['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 