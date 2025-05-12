<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DisputeReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some completed transactions
        $transactions = DB::table('waste_transactions')
            ->where('status', 'completed')
            ->orWhere('status', 'disputed')
            ->limit(20)
            ->get();
        
        // If no transactions found, exit early
        if ($transactions->isEmpty()) {
            $this->command->info('No suitable transactions found. Skipping dispute report seeding.');
            return;
        }
        
        // Get admin user for resolver
        $admin = DB::table('users')->where('email', 'admin@cyclebank.com')->first();
        
        // Create disputes for some transactions (about 5% of them)
        $transactionsForDispute = $transactions->random(max(1, intval($transactions->count() * 0.05)));
        
        // Reason categories for disputes
        $reasonCategories = [
            'quality_issue' => 'Kualitas sampah tidak sesuai dengan kesepakatan',
            'weight_discrepancy' => 'Ketidaksesuaian berat sampah',
            'pricing_disagreement' => 'Ketidaksetujuan harga',
            'payment_issue' => 'Masalah pembayaran',
            'service_complaint' => 'Keluhan layanan',
        ];
        
        foreach ($transactionsForDispute as $transaction) {
            // Get the users involved in transaction
            $reportedBy = null;
            $reportedAgainst = null;
            
            if ($transaction->transaction_type === 'customer_to_bank') {
                // 50% chance the customer reports the bank, 50% the other way around
                if (rand(0, 1) === 0) {
                    $customer = DB::table('customers')->where('guid', $transaction->customer_guid)->first();
                    $customerUser = $customer ? DB::table('users')->where('guid', $customer->user_guid)->first() : null;
                    $reportedBy = $customerUser ? $customerUser->guid : null;
                    
                    $bank = DB::table('waste_banks')->where('guid', $transaction->waste_bank_guid)->first();
                    $bankUser = $bank ? DB::table('users')->where('guid', $bank->user_guid)->first() : null;
                    $reportedAgainst = $bankUser ? $bankUser->guid : null;
                } else {
                    $bank = DB::table('waste_banks')->where('guid', $transaction->waste_bank_guid)->first();
                    $bankUser = $bank ? DB::table('users')->where('guid', $bank->user_guid)->first() : null;
                    $reportedBy = $bankUser ? $bankUser->guid : null;
                    
                    $customer = DB::table('customers')->where('guid', $transaction->customer_guid)->first();
                    $customerUser = $customer ? DB::table('users')->where('guid', $customer->user_guid)->first() : null;
                    $reportedAgainst = $customerUser ? $customerUser->guid : null;
                }
            } else { // bank_to_manager
                // 50% chance the bank reports the manager, 50% the other way around
                if (rand(0, 1) === 0) {
                    $bank = DB::table('waste_banks')->where('guid', $transaction->waste_bank_guid)->first();
                    $bankUser = $bank ? DB::table('users')->where('guid', $bank->user_guid)->first() : null;
                    $reportedBy = $bankUser ? $bankUser->guid : null;
                    
                    $manager = DB::table('waste_managers')->where('guid', $transaction->waste_manager_guid)->first();
                    $managerUser = $manager ? DB::table('users')->where('guid', $manager->user_guid)->first() : null;
                    $reportedAgainst = $managerUser ? $managerUser->guid : null;
                } else {
                    $manager = DB::table('waste_managers')->where('guid', $transaction->waste_manager_guid)->first();
                    $managerUser = $manager ? DB::table('users')->where('guid', $manager->user_guid)->first() : null;
                    $reportedBy = $managerUser ? $managerUser->guid : null;
                    
                    $bank = DB::table('waste_banks')->where('guid', $transaction->waste_bank_guid)->first();
                    $bankUser = $bank ? DB::table('users')->where('guid', $bank->user_guid)->first() : null;
                    $reportedAgainst = $bankUser ? $bankUser->guid : null;
                }
            }
            
            // Skip if we couldn't get the users
            if (!$reportedBy || !$reportedAgainst) continue;
            
            // Choose a random reason category
            $reasonKey = array_rand($reasonCategories);
            $reason = $reasonCategories[$reasonKey];
            
            // Generate a status with weighted probability
            $status = $this->getDisputeStatus();
            
            // If resolved, set resolution details
            $resolvedBy = null;
            $resolvedAt = null;
            $resolution = null;
            $refundAmount = null;
            $isRefunded = false;
            
            if ($status === 'resolved' || $status === 'closed') {
                $resolvedBy = $admin ? $admin->guid : null;
                $resolvedAt = now()->subDays(rand(1, 10));
                $resolution = $this->getResolution($reasonKey);
                
                // 30% chance of refund for resolved disputes
                if (rand(1, 100) <= 30) {
                    $refundAmount = $transaction->total_amount * (rand(10, 100) / 100); // 10-100% refund
                    $isRefunded = rand(0, 1) === 1; // 50% chance it's already refunded
                }
            }
            
            // Create the dispute record
            DB::table('dispute_reports')->insert([
                'guid' => Str::uuid()->toString(),
                'transaction_guid' => $transaction->guid,
                'reported_by' => $reportedBy,
                'reported_against' => $reportedAgainst,
                'reason_category' => $reasonKey,
                'description' => $reason . '. ' . $this->getRandomDescription($reasonKey),
                'evidence_urls' => json_encode(['dispute/evidence_' . rand(1000, 9999) . '.jpg']),
                'status' => $status,
                'resolution' => $resolution,
                'resolved_by' => $resolvedBy,
                'resolved_at' => $resolvedAt,
                'refund_amount' => $refundAmount,
                'is_refunded' => $isRefunded,
                'notes' => $status === 'investigating' ? 'Sedang dilakukan investigasi lebih lanjut' : null,
                'created_at' => now()->subDays(rand(10, 30)),
                'updated_at' => $resolvedAt ?? now()->subDays(rand(1, 9)),
                'deleted_at' => null,
            ]);
            
            // Update transaction status if dispute is still open
            if ($status === 'open' || $status === 'investigating') {
                DB::table('waste_transactions')
                    ->where('guid', $transaction->guid)
                    ->update(['status' => 'disputed']);
            }
        }
    }
    
    /**
     * Get a random dispute status with weighted probability
     */
    private function getDisputeStatus(): string
    {
        $statuses = [
            'open' => 15,
            'investigating' => 25,
            'resolved' => 45,
            'closed' => 10,
            'escalated' => 5,
        ];
        
        $total = array_sum($statuses);
        $rand = rand(1, $total);
        
        $sum = 0;
        foreach ($statuses as $status => $weight) {
            $sum += $weight;
            if ($rand <= $sum) {
                return $status;
            }
        }
        
        return 'open'; // Default fallback
    }
    
    /**
     * Get a resolution based on the reason category
     */
    private function getResolution(string $reasonCategory): string
    {
        return match($reasonCategory) {
            'quality_issue' => 'Sampah akan diperiksa ulang dan harga disesuaikan berdasarkan kualitas aktual.',
            'weight_discrepancy' => 'Dilakukan penimbangan ulang dengan disaksikan kedua belah pihak.',
            'pricing_disagreement' => 'Harga disesuaikan dengan patokan harga rata-rata pasar saat ini.',
            'payment_issue' => 'Pembayaran akan dilakukan dalam 3 hari kerja.',
            'service_complaint' => 'Bank sampah akan meningkatkan standar layanan dan memberikan kompensasi.',
            default => 'Kesepakatan telah tercapai antara kedua belah pihak.',
        };
    }
    
    /**
     * Get a random description based on reason category
     */
    private function getRandomDescription(string $reasonCategory): string
    {
        $descriptions = [
            'quality_issue' => [
                'Sampah tercampur dengan material non-recyclable.',
                'Sampah terlalu kotor dan tidak sesuai standar.',
                'Sampah sudah terlalu lama disimpan dan mengalami degradasi.',
            ],
            'weight_discrepancy' => [
                'Selisih berat sangat signifikan dibandingkan estimasi awal.',
                'Timbangan yang digunakan tidak terkalibrasi dengan baik.',
                'Ada perbedaan berat saat penyerahan dan saat penimbangan final.',
            ],
            'pricing_disagreement' => [
                'Harga yang ditawarkan jauh di bawah harga pasar.',
                'Terjadi perubahan harga mendadak tanpa pemberitahuan.',
                'Potongan harga terlalu besar untuk sampah yang sedikit cacat.',
            ],
            'payment_issue' => [
                'Pembayaran belum diterima sesuai tenggat waktu.',
                'Jumlah yang ditransfer tidak sesuai dengan kesepakatan.',
                'Metode pembayaran yang disepakati tiba-tiba diubah.',
            ],
            'service_complaint' => [
                'Petugas tidak ramah dan profesional.',
                'Proses pengambilan sampah terlambat dari jadwal.',
                'Pelayanan tidak sesuai dengan yang dijanjikan di aplikasi.',
            ],
        ];
        
        $categoryDescriptions = $descriptions[$reasonCategory] ?? $descriptions['quality_issue'];
        return $categoryDescriptions[array_rand($categoryDescriptions)];
    }
} 