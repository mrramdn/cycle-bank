<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WasteBankPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data user IDs dari file sementara
        $seederIds = json_decode(file_get_contents(storage_path('app/seeder_ids.json')), true);
        $wasteBankIds = $seederIds['waste_banks'] ?? [];
        
        // Ambil semua waste category guids
        $wasteCategories = DB::table('waste_categories')->get();
        
        // Harga dasar untuk setiap kategori sampah (dalam Rp per kg)
        $basePrices = [
            'PET Plastic (1)' => 5000,
            'HDPE Plastic (2)' => 6000,
            'Cardboard' => 2000,
            'Aluminum' => 15000,
            'Glass' => 3000,
            'Paper' => 3500,
            'E-Waste' => 25000,
            'Organic Waste' => 1000,
        ];
        
        // Untuk setiap bank sampah, buat harga untuk setiap kategori
        foreach ($wasteBankIds as $bankKey => $bankGuid) {
            // Faktor variasi harga untuk bank sampah ini (antara 0.9 dan 1.1)
            $priceFactor = rand(90, 110) / 100;
            
            foreach ($wasteCategories as $category) {
                // Skip jika tidak ada harga dasar untuk kategori ini
                if (!isset($basePrices[$category->name])) {
                    continue;
                }
                
                // Hitung harga spesifik untuk kombinasi bank sampah ini dan kategori sampah
                $price = $basePrices[$category->name] * $priceFactor;
                
                // Tambahkan sedikit variasi acak (±5%)
                $price *= (rand(95, 105) / 100);
                
                // Bulatkan ke ratusan terdekat
                $price = round($price / 100) * 100;
                
                DB::table('waste_bank_prices')->insert([
                    'guid' => Str::uuid()->toString(),
                    'waste_bank_guid' => $bankGuid,
                    'waste_category_guid' => $category->guid,
                    'buy_price' => $price,
                    'is_active' => true,
                    'updated_at' => now(),
                ]);
            }
        }
    }
} 