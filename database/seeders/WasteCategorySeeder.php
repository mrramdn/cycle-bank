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
                'image_url' => 'waste-categories/pet.jpg',
                'points_per_kg' => 10,
                'material_type' => 'plastic',
                'is_active' => true,
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'HDPE Plastic (2)',
                'description' => 'High-Density Polyethylene - Used in milk jugs, detergent bottles, and some plastic bags.',
                'image_url' => 'waste-categories/hdpe.jpg',
                'points_per_kg' => 8,
                'material_type' => 'plastic',
                'is_active' => true,
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Cardboard',
                'description' => 'Cardboard boxes, packaging materials, and paper products.',
                'image_url' => 'waste-categories/cardboard.jpg',
                'points_per_kg' => 5,
                'material_type' => 'paper',
                'is_active' => true,
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Aluminum',
                'description' => 'Aluminum cans, foils, and some packaging materials.',
                'image_url' => 'waste-categories/aluminum.jpg',
                'points_per_kg' => 15,
                'material_type' => 'metal',
                'is_active' => true,
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Glass',
                'description' => 'Glass bottles, jars, and containers of various colors.',
                'image_url' => 'waste-categories/glass.jpg',
                'points_per_kg' => 7,
                'material_type' => 'glass',
                'is_active' => true,
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Paper',
                'description' => 'Newspapers, magazines, office paper, and printed materials.',
                'image_url' => 'waste-categories/paper.jpg',
                'points_per_kg' => 4,
                'material_type' => 'paper',
                'is_active' => true,
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'E-Waste',
                'description' => 'Electronic waste including small devices, cables, and components.',
                'image_url' => 'waste-categories/e-waste.jpg',
                'points_per_kg' => 20,
                'material_type' => 'electronic',
                'is_active' => true,
            ],
            [
                'guid' => Str::uuid()->toString(),
                'name' => 'Organic Waste',
                'description' => 'Food scraps, yard waste, and other biodegradable materials.',
                'image_url' => 'waste-categories/organic.jpg',
                'points_per_kg' => 2,
                'material_type' => 'organic',
                'is_active' => true,
            ],
        ];

        foreach ($wasteCategories as $category) {
            DB::table('waste_categories')->insert([
                'guid' => $category['guid'],
                'name' => $category['name'],
                'description' => $category['description'],
                'image_url' => $category['image_url'],
                'points_per_kg' => $category['points_per_kg'],
                'material_type' => $category['material_type'],
                'is_active' => $category['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 