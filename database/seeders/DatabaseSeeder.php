<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Menonaktifkan foreign key checks untuk memudahkan seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate semua tabel untuk fresh start
        $this->truncateTables();

        // Seed data master dan referensi
        $this->call([
            PositionSeeder::class,
            RewardSeeder::class,
            WasteCategorySeeder::class,
            
            // Data user dan profil
            UserSeeder::class,
            CustomerSeeder::class,
            WasteBankSeeder::class,
            WasteManagerSeeder::class,
            GovernmentSeeder::class,
            
            // Data transaksi dan harga
            WasteBankPriceSeeder::class,
            CustomerBalanceSeeder::class,
        ]);
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
    
    /**
     * Truncate semua tabel
     */
    private function truncateTables(): void
    {
        // Daftar tabel untuk di-truncate dalam urutan tertentu
        $tables = [
            'customer_balances',
            'waste_bank_prices',
            'customers',
            'waste_banks',
            'waste_managers',
            'governments',
            'users',
            'positions',
            'rewards',
            'waste_categories',
        ];
        
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
    }
}
