<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WastePriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load waste banks and waste categories
        $wasteCategories = DB::table('waste_categories')->get();
        $wasteBanks = DB::table('waste_banks')->get();
        
        // If no waste banks or categories found, exit early
        if ($wasteBanks->isEmpty() || $wasteCategories->isEmpty()) {
            $this->command->info('No waste banks or categories found. Skipping price seeding.');
            return;
        }
        
        // Clear existing prices
        DB::table('waste_prices')->truncate();
        
        // Create price for each bank and each category
        foreach ($wasteBanks as $bank) {
            foreach ($wasteCategories as $category) {
                // Generate prices with some randomness but consistent with waste type value
                $baseBuyPrice = $this->getBasePriceForCategory($category->name);
                
                // Add some variation per bank (±10%)
                $modifier = rand(90, 110) / 100;
                $buyPrice = round($baseBuyPrice * $modifier, 2);
                
                // Sell price is higher than buy price (15-25% margin)
                $margin = rand(115, 125) / 100;
                $sellPrice = round($buyPrice * $margin, 2);
                
                // Insert the price record
                DB::table('waste_prices')->insert([
                    'guid' => Str::uuid()->toString(),
                    'waste_bank_guid' => $bank->guid,
                    'waste_category_guid' => $category->guid,
                    'buy_price' => $buyPrice,
                    'sell_price' => $sellPrice,
                    'min_quantity' => 0.5, // Minimum 500 grams
                    'condition_requirements' => json_encode(['clean' => true, 'sorted' => true]),
                    'is_active' => true,
                    'valid_from' => now(),
                    'valid_until' => now()->addMonths(3),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
    
    /**
     * Generate base price based on waste category
     */
    private function getBasePriceForCategory(string $categoryName): float
    {
        // Default prices per kg in Rupiah (you can adjust these)
        return match (true) {
            str_contains($categoryName, 'PET') => 3500,
            str_contains($categoryName, 'HDPE') => 4000,
            str_contains($categoryName, 'Cardboard') => 2000,
            str_contains($categoryName, 'Aluminum') => 12000,
            str_contains($categoryName, 'Glass') => 1500,
            str_contains($categoryName, 'Paper') => 3000,
            str_contains($categoryName, 'E-Waste') => 20000,
            str_contains($categoryName, 'Organic') => 500,
            default => 1000,
        };
    }
} 