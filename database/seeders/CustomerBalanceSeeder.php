<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data user IDs dari file sementara
        $seederIds = json_decode(file_get_contents(storage_path('app/seeder_ids.json')), true);
        $customerIds = $seederIds['customers'] ?? [];
        
        // Buat saldo untuk setiap customer
        foreach ($customerIds as $customerKey => $customerGuid) {
            // Buat data saldo acak berdasarkan aktivitas
            $totalEarned = rand(500000, 2000000); // Rp 500k - Rp 2M
            $totalWithdrawn = rand(0, $totalEarned * 0.9); // Maksimal 90% dari yang didapat
            $currentBalance = $totalEarned - $totalWithdrawn;
            
            DB::table('customer_balances')->insert([
                'guid' => Str::uuid()->toString(),
                'customer_guid' => $customerGuid,
                'current_balance' => $currentBalance,
                'total_earned' => $totalEarned,
                'total_withdrawn' => $totalWithdrawn,
                'minimum_withdrawal' => 50000, // Rp 50k minimum withdrawal
                'withdrawal_eligibility' => $currentBalance >= 50000, // Eligible jika saldo >= Rp 50k
                'last_transaction_at' => now()->subDays(rand(0, 30)),
                'updated_at' => now(),
            ]);
        }
    }
} 