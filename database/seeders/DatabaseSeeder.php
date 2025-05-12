<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jangan gunakan foreign key checks karena kita tidak menggunakan foreign key constraints
        // Tapi tetap truncate tabel untuk fresh start
        $this->truncateTables();

        // Seed semua data
        $this->call([
            // Data Master 
            WasteCategorySeeder::class, // Kategori sampah
            RewardSeeder::class, // Hadiah
            CustomerLevelSeeder::class, // Level customer
            
            // Data Pengguna
            UserSeeder::class, // Semua user (customer, waste_bank, waste_manager, government)
            CustomerSeeder::class, // Profile customer
            WasteBankSeeder::class, // Profile bank sampah
            WasteManagerSeeder::class, // Profile pengelola sampah
            GovernmentAgencySeeder::class, // Profil institusi pemerintah
            
            // Data Bisnis dan Operasional
            WastePriceSeeder::class, // Harga sampah di bank sampah
            CustomerBalanceSeeder::class, // Saldo customer
            CustomerRewardSeeder::class, // Penukaran reward customer
            
            // Data Transaksi
            WasteTransactionSeeder::class, // Transaksi penjualan sampah
            
            // Verifikasi dan Tracking
            VerificationDocumentSeeder::class, // Dokumen verifikasi
            WasteTraceabilitySeeder::class, // Pelacakan sampah
            DisputeReportSeeder::class, // Laporan sengketa
        ]);
    }
    
    /**
     * Truncate semua tabel
     */
    private function truncateTables(): void
    {
        // Daftar tabel untuk di-truncate dalam urutan tertentu
        $tables = [
            // Data tracking dan verifikasi
            'waste_traceability',
            'verification_documents',
            'dispute_reports',
            
            // Notifikasi dan system
            'notifications',
            'withdrawals',
            'sync_logs',
            
            // Data transaksi
            'transaction_items',
            'waste_transactions',
            
            // Data reward dan saldo
            'customer_rewards',
            'customer_balances',
            
            // Data operasional
            'waste_prices',
            
            // Data profil
            'customers',
            'waste_banks',
            'waste_managers',
            'government_agencies',
            'users',
            
            // Data master
            'customer_levels',
            'rewards',
            'waste_categories',
            
            // Tabel Laravel bawaan
            'password_reset_tokens',
            'failed_jobs',
            'personal_access_tokens',
        ];
        
        foreach ($tables as $table) {
            try {
                DB::table($table)->truncate();
            } catch (\Exception $e) {
                // Log bahwa tabel mungkin belum dibuat
                Log::info("Tidak dapat truncate tabel $table: " . $e->getMessage());
            }
        }
    }
}
