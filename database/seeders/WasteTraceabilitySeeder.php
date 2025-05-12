<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WasteTraceabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get completed customer transactions
        $customerTransactions = DB::table('waste_transactions')
            ->where('transaction_type', 'customer_to_bank')
            ->where('status', 'completed')
            ->get();
        
        // Get completed bank to manager transactions
        $managerTransactions = DB::table('waste_transactions')
            ->where('transaction_type', 'bank_to_manager')
            ->where('status', 'completed')
            ->get();
        
        // If no transactions, exit early
        if ($customerTransactions->isEmpty()) {
            $this->command->info('No completed customer transactions found. Skipping traceability seeding.');
            return;
        }
        
        // Create traceability records for customer transactions
        foreach ($customerTransactions as $transaction) {
            // Get transaction items
            $items = DB::table('transaction_items')
                ->where('transaction_guid', $transaction->guid)
                ->get();
            
            foreach ($items as $item) {
                // Determine if this will be sold to manager
                $willBeSold = rand(0, 3) > 0; // 75% chance of being sold to manager
                
                // Find a manager transaction if this will be sold
                $managerTransaction = null;
                if ($willBeSold && !$managerTransactions->isEmpty()) {
                    // Get manager transactions for this waste bank
                    $bankManagerTransactions = $managerTransactions->where('waste_bank_guid', $transaction->waste_bank_guid);
                    
                    // Only pick a random transaction if we have some for this bank
                    if (!$bankManagerTransactions->isEmpty()) {
                        $managerTransaction = $bankManagerTransactions->random();
                    }
                }
                
                // Determine status based on manager transaction
                $status = 'collected';
                $processingDate = null;
                $processingMethod = null;
                $processingResult = null;
                
                if ($managerTransaction) {
                    // 10% chance to be still in bank
                    if (rand(1, 10) === 1) {
                        $status = 'in_bank';
                    } else {
                        $daysAfterCollection = rand(3, 15);
                        $processingDate = now()->subDays(rand(1, 30))->addDays($daysAfterCollection);
                        
                        // Random status for processing
                        $randomStatus = rand(1, 100);
                        if ($randomStatus <= 35) {
                            $status = 'sold';
                        } elseif ($randomStatus <= 70) {
                            $status = 'processed';
                            $processingMethod = $this->getRandomProcessingMethod($item->waste_category_guid);
                        } elseif ($randomStatus <= 90) {
                            $status = 'recycled';
                            $processingMethod = $this->getRandomProcessingMethod($item->waste_category_guid);
                            $processingResult = $this->getRandomProcessingResult($item->waste_category_guid);
                        } else {
                            $status = 'final';
                            $processingMethod = $this->getRandomProcessingMethod($item->waste_category_guid);
                            $processingResult = $this->getRandomProcessingResult($item->waste_category_guid);
                        }
                    }
                }
                
                // Insert traceability record
                DB::table('waste_traceability')->insert([
                    'guid' => Str::uuid()->toString(),
                    'waste_category_guid' => $item->waste_category_guid,
                    'quantity' => $item->quantity,
                    'origin_customer_guid' => $transaction->customer_guid,
                    'origin_transaction_guid' => $transaction->guid,
                    'waste_bank_guid' => $transaction->waste_bank_guid,
                    'destination_transaction_guid' => $managerTransaction ? $managerTransaction->guid : null,
                    'waste_manager_guid' => $managerTransaction ? $managerTransaction->waste_manager_guid : null,
                    'status' => $status,
                    'batch_number' => $status !== 'collected' && $status !== 'in_bank' ? 'BTH-' . date('Ymd') . '-' . rand(1000, 9999) : null,
                    'collection_date' => now()->parse($transaction->created_at)->format('Y-m-d'),
                    'processing_date' => $processingDate ? $processingDate->format('Y-m-d') : null,
                    'processing_method' => $processingMethod,
                    'processing_result' => $processingResult,
                    'proof_url' => $status === 'recycled' || $status === 'final' ? 'proofs/processing-' . rand(1000, 9999) . '.jpg' : null,
                    'notes' => null,
                    'created_at' => $transaction->created_at,
                    'updated_at' => $processingDate ?? $transaction->created_at,
                ]);
            }
        }
    }
    
    /**
     * Get a random processing method based on waste category
     */
    private function getRandomProcessingMethod(string $categoryGuid): ?string
    {
        // Get the category name
        $category = DB::table('waste_categories')->where('guid', $categoryGuid)->first();
        
        if (!$category) return null;
        
        $categoryName = $category->name;
        
        // Determine method based on category
        return match (true) {
            str_contains($categoryName, 'PET') || str_contains($categoryName, 'HDPE') => 
                $this->pickRandom(['Shredding and Pelletizing', 'Melting and Remolding', 'Chemical Recycling']),
            str_contains($categoryName, 'Aluminum') => 
                $this->pickRandom(['Melting and Recasting', 'Continuous Casting']),
            str_contains($categoryName, 'Glass') => 
                $this->pickRandom(['Crushing and Remolding', 'Melting and Reforming']),
            str_contains($categoryName, 'Paper') || str_contains($categoryName, 'Cardboard') => 
                $this->pickRandom(['Pulping and Deinking', 'Mechanical Recycling']),
            str_contains($categoryName, 'E-Waste') => 
                $this->pickRandom(['Component Disassembly', 'Precious Metal Recovery', 'Chemical Extraction']),
            str_contains($categoryName, 'Organic') => 
                $this->pickRandom(['Composting', 'Anaerobic Digestion', 'Biogas Production']),
            default => 'Standard Processing',
        };
    }
    
    /**
     * Get a random processing result based on waste category
     */
    private function getRandomProcessingResult(string $categoryGuid): ?string
    {
        // Get the category name
        $category = DB::table('waste_categories')->where('guid', $categoryGuid)->first();
        
        if (!$category) return null;
        
        $categoryName = $category->name;
        
        // Determine result based on category
        return match (true) {
            str_contains($categoryName, 'PET') || str_contains($categoryName, 'HDPE') => 
                $this->pickRandom(['Recycled Plastic Pellets', 'Plastic Sheets', 'New Plastic Containers', 'Textile Fibers']),
            str_contains($categoryName, 'Aluminum') => 
                $this->pickRandom(['Recycled Aluminum Ingots', 'New Aluminum Cans', 'Construction Materials']),
            str_contains($categoryName, 'Glass') => 
                $this->pickRandom(['Recycled Glass Cullet', 'New Glass Containers', 'Glass Fiber Insulation']),
            str_contains($categoryName, 'Paper') || str_contains($categoryName, 'Cardboard') => 
                $this->pickRandom(['Recycled Paper Pulp', 'New Paper Products', 'Packaging Materials']),
            str_contains($categoryName, 'E-Waste') => 
                $this->pickRandom(['Recovered Metals', 'Refurbished Components', 'Precious Metal Recovery']),
            str_contains($categoryName, 'Organic') => 
                $this->pickRandom(['Compost', 'Fertilizer', 'Biogas', 'Energy Generation']),
            default => 'Recycled Materials',
        };
    }
    
    /**
     * Pick a random item from an array
     */
    private function pickRandom(array $items): string
    {
        return $items[array_rand($items)];
    }
} 