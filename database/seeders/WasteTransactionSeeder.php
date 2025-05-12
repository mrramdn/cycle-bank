<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WasteTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get customers, waste banks, waste managers, and waste categories
        $customers = DB::table('customers')->get();
        $wasteBanks = DB::table('waste_banks')->get();
        $wasteManagers = DB::table('waste_managers')->get();
        $wasteCategories = DB::table('waste_categories')->get();
        $wastePrices = DB::table('waste_prices')->get()->groupBy('waste_bank_guid');
        
        // If any is empty, exit early
        if ($customers->isEmpty() || $wasteBanks->isEmpty() || $wasteManagers->isEmpty() || $wasteCategories->isEmpty()) {
            $this->command->info('Missing required data for transactions. Skipping transaction seeding.');
            return;
        }
        
        // Create customer to bank transactions
        $this->createCustomerToBankTransactions($customers, $wasteBanks, $wasteCategories, $wastePrices);
        
        // Create bank to manager transactions
        $this->createBankToManagerTransactions($wasteBanks, $wasteManagers, $wasteCategories);
    }
    
    /**
     * Create transactions from customers to waste banks
     */
    private function createCustomerToBankTransactions($customers, $wasteBanks, $wasteCategories, $wastePrices): void
    {
        // For each customer, create 0-5 transactions
        foreach ($customers as $customer) {
            $transactionCount = rand(0, 5);
            
            for ($i = 0; $i < $transactionCount; $i++) {
                // Pick a random waste bank
                $wasteBank = $wasteBanks->random();
                
                // Create transaction
                $transactionDate = now()->subDays(rand(1, 60));
                $status = $this->getRandomTransactionStatus();
                
                $transactionGuid = Str::uuid()->toString();
                
                // Delivery and payment methods
                $deliveryMethod = rand(0, 1) ? 'pickup' : 'dropoff';
                $paymentMethod = rand(0, 1) ? 'cash' : 'digital_balance';
                $isPaid = $status === 'completed' || rand(0, 1);
                
                // Create transaction items (1-4 types of waste)
                $itemCount = rand(1, 4);
                $totalAmount = 0;
                $totalPoints = 0;
                $selectedCategories = $wasteCategories->random($itemCount);
                
                // Transaction items
                foreach ($selectedCategories as $category) {
                    // Find price for this category at this bank
                    $price = null;
                    if (isset($wastePrices[$wasteBank->guid])) {
                        foreach ($wastePrices[$wasteBank->guid] as $wastePrice) {
                            if ($wastePrice->waste_category_guid === $category->guid) {
                                $price = $wastePrice;
                                break;
                            }
                        }
                    }
                    
                    // Use default price if not found
                    $buyPrice = $price ? $price->buy_price : rand(1000, 5000);
                    
                    // Generate random quantity
                    $quantity = rand(1, 10) + (rand(0, 9) / 10); // 1.0 - 10.9 kg
                    $subtotal = $quantity * $buyPrice;
                    
                    // Points earned (1 point per 1000 Rupiah)
                    $pointsEarned = floor($subtotal / 1000);
                    
                    // Add to totals
                    $totalAmount += $subtotal;
                    $totalPoints += $pointsEarned;
                    
                    // Insert transaction item
                    DB::table('transaction_items')->insert([
                        'guid' => Str::uuid()->toString(),
                        'transaction_guid' => $transactionGuid,
                        'waste_category_guid' => $category->guid,
                        'quantity' => $quantity,
                        'price_per_unit' => $buyPrice,
                        'subtotal' => $subtotal,
                        'points_earned' => $pointsEarned,
                        'description' => "Penjualan {$category->name}",
                        'image_url' => null,
                        'created_at' => $transactionDate,
                        'updated_at' => $transactionDate
                    ]);
                }
                
                // Insert the transaction
                DB::table('waste_transactions')->insert([
                    'guid' => $transactionGuid,
                    'customer_guid' => $customer->guid,
                    'waste_bank_guid' => $wasteBank->guid,
                    'waste_manager_guid' => null,
                    'transaction_type' => 'customer_to_bank',
                    'status' => $status,
                    'delivery_method' => $deliveryMethod,
                    'payment_method' => $paymentMethod,
                    'is_paid' => $isPaid,
                    'total_amount' => $totalAmount,
                    'total_points_earned' => $totalPoints,
                    'transaction_proof' => $status === 'completed' ? 'proofs/transaction-'.rand(1000, 9999).'.jpg' : null,
                    'signature_url' => $status === 'completed' ? 'signatures/sig-'.rand(1000, 9999).'.png' : null,
                    'location_data' => json_encode(['lat' => -6.2 + (rand(-100, 100) / 1000), 'lng' => 106.8 + (rand(-100, 100) / 1000)]),
                    'is_offline_sync' => rand(0, 5) === 0, // 1 in 6 chance of offline transaction
                    'synced_at' => rand(0, 5) === 0 ? null : $transactionDate->addHours(rand(1, 12)),
                    'notes' => null,
                    'created_at' => $transactionDate,
                    'updated_at' => $transactionDate->addHours(rand(0, 24)),
                    'deleted_at' => null
                ]);
                
                // If completed, update customer points and balances
                if ($status === 'completed') {
                    // Update customer
                    DB::table('customers')
                        ->where('guid', $customer->guid)
                        ->increment('points', $totalPoints);
                    
                    DB::table('customers')
                        ->where('guid', $customer->guid)
                        ->increment('total_waste_sold', array_sum(DB::table('transaction_items')
                            ->where('transaction_guid', $transactionGuid)
                            ->pluck('quantity')
                            ->toArray()));
                    
                    // Update customer balance if digital payment
                    if ($paymentMethod === 'digital_balance') {
                        DB::table('customer_balances')
                            ->where('customer_guid', $customer->guid)
                            ->increment('current_balance', $totalAmount);
                        
                        DB::table('customer_balances')
                            ->where('customer_guid', $customer->guid)
                            ->increment('total_earned', $totalAmount);
                    }
                }
            }
        }
    }
    
    /**
     * Create transactions from waste banks to waste managers
     */
    private function createBankToManagerTransactions($wasteBanks, $wasteManagers, $wasteCategories): void
    {
        // For each bank, create 0-3 transactions
        foreach ($wasteBanks as $bank) {
            $transactionCount = rand(0, 3);
            
            for ($i = 0; $i < $transactionCount; $i++) {
                // Pick a random waste manager
                $wasteManager = $wasteManagers->random();
                
                // Create transaction
                $transactionDate = now()->subDays(rand(1, 40));
                $status = $this->getRandomTransactionStatus();
                
                $transactionGuid = Str::uuid()->toString();
                
                // Payment is always digital for B2B
                $paymentMethod = 'digital_balance';
                $isPaid = $status === 'completed' || rand(0, 1);
                
                // Create transaction items (2-5 types of waste)
                $itemCount = rand(2, 5);
                $totalAmount = 0;
                $selectedCategories = $wasteCategories->random($itemCount);
                
                // Transaction items
                foreach ($selectedCategories as $category) {
                    // Higher quantity for B2B (50-200 kg)
                    $quantity = rand(50, 200) + (rand(0, 9) / 10);
                    
                    // Manager buys at higher bulk prices
                    $sellPrice = rand(5000, 15000); // Price per kg for bulk
                    $subtotal = $quantity * $sellPrice;
                    
                    // Add to totals
                    $totalAmount += $subtotal;
                    
                    // Insert transaction item
                    DB::table('transaction_items')->insert([
                        'guid' => Str::uuid()->toString(),
                        'transaction_guid' => $transactionGuid,
                        'waste_category_guid' => $category->guid,
                        'quantity' => $quantity,
                        'price_per_unit' => $sellPrice,
                        'subtotal' => $subtotal,
                        'points_earned' => 0, // No points for bank-to-manager
                        'description' => "Penjualan bulk {$category->name}",
                        'image_url' => null,
                        'created_at' => $transactionDate,
                        'updated_at' => $transactionDate
                    ]);
                }
                
                // Insert the transaction
                DB::table('waste_transactions')->insert([
                    'guid' => $transactionGuid,
                    'customer_guid' => null,
                    'waste_bank_guid' => $bank->guid,
                    'waste_manager_guid' => $wasteManager->guid,
                    'transaction_type' => 'bank_to_manager',
                    'status' => $status,
                    'delivery_method' => null, // No delivery method for B2B
                    'payment_method' => $paymentMethod,
                    'is_paid' => $isPaid,
                    'total_amount' => $totalAmount,
                    'total_points_earned' => 0, // No points for B2B
                    'transaction_proof' => $status === 'completed' ? 'proofs/b2b-transaction-'.rand(1000, 9999).'.jpg' : null,
                    'signature_url' => $status === 'completed' ? 'signatures/b2b-sig-'.rand(1000, 9999).'.png' : null,
                    'location_data' => json_encode(['lat' => -6.2 + (rand(-100, 100) / 1000), 'lng' => 106.8 + (rand(-100, 100) / 1000)]),
                    'is_offline_sync' => false, // B2B typically online
                    'synced_at' => $transactionDate,
                    'notes' => null,
                    'created_at' => $transactionDate,
                    'updated_at' => $transactionDate->addHours(rand(0, 24)),
                    'deleted_at' => null
                ]);
                
                // If completed, update bank's balance
                if ($status === 'completed' && $isPaid) {
                    DB::table('waste_banks')
                        ->where('guid', $bank->guid)
                        ->increment('balance', $totalAmount);
                }
            }
        }
    }
    
    /**
     * Get a random transaction status with weighted probability
     */
    private function getRandomTransactionStatus(): string
    {
        $statuses = [
            'pending' => 10,
            'confirmed' => 15,
            'completed' => 65,
            'canceled' => 8,
            'disputed' => 2,
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
        
        return 'completed'; // Default fallback
    }
} 