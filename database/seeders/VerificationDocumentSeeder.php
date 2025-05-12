<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VerificationDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get waste banks and waste managers
        $wasteBanks = DB::table('waste_banks')->get();
        $wasteManagers = DB::table('waste_managers')->get();
        
        // Get admin user for verification
        $admin = DB::table('users')->where('email', 'admin@cyclebank.com')->first();
        
        // If no entities, exit early
        if ($wasteBanks->isEmpty() && $wasteManagers->isEmpty()) {
            $this->command->info('No waste banks or managers found. Skipping document verification seeding.');
            return;
        }
        
        // Document types
        $documentTypes = [
            'waste_bank' => [
                'KTP Pemilik',
                'SIUP',
                'NPWP',
                'Surat Izin Tempat Usaha',
                'Foto Lokasi Usaha',
            ],
            'waste_manager' => [
                'KTP Penanggung Jawab',
                'Izin Usaha Industri',
                'NPWP Perusahaan',
                'Izin Lingkungan',
                'Sertifikat Proper',
                'Dokumen UKL-UPL',
                'Foto Fasilitas Pengolahan',
            ],
        ];
        
        // Seed verification documents for waste banks
        foreach ($wasteBanks as $bank) {
            // Get user for this bank
            $user = DB::table('users')->where('guid', $bank->user_guid)->first();
            
            if (!$user) continue;
            
            // Create 2-4 documents per bank
            $docCount = rand(2, 4);
            $selectedTypes = array_rand(array_flip($documentTypes['waste_bank']), $docCount);
            
            if (!is_array($selectedTypes)) {
                $selectedTypes = [$selectedTypes];
            }
            
            foreach ($selectedTypes as $docType) {
                $status = $this->getRandomVerificationStatus();
                $verifiedAt = $status === 'verified' ? now()->subDays(rand(1, 30)) : null;
                
                DB::table('verification_documents')->insert([
                    'guid' => Str::uuid()->toString(),
                    'user_guid' => $user->guid,
                    'document_type' => $docType,
                    'document_number' => $this->generateDocumentNumber($docType),
                    'document_url' => 'documents/' . strtolower(str_replace(' ', '_', $docType)) . '_' . rand(1000, 9999) . '.pdf',
                    'issued_date' => now()->subYears(rand(1, 5))->format('Y-m-d'),
                    'expiry_date' => now()->addYears(rand(1, 3))->format('Y-m-d'),
                    'status' => $status,
                    'rejection_reason' => $status === 'rejected' ? 'Dokumen tidak lengkap atau tidak jelas' : null,
                    'verified_by' => ($status === 'verified' && $admin) ? $admin->guid : null,
                    'verified_at' => $verifiedAt,
                    'created_at' => now()->subDays(rand(30, 60)),
                    'updated_at' => $verifiedAt ?? now()->subDays(rand(1, 29)),
                ]);
            }
        }
        
        // Seed verification documents for waste managers
        foreach ($wasteManagers as $manager) {
            // Get user for this manager
            $user = DB::table('users')->where('guid', $manager->user_guid)->first();
            
            if (!$user) continue;
            
            // Create 3-5 documents per manager
            $docCount = rand(3, 5);
            $selectedTypes = array_rand(array_flip($documentTypes['waste_manager']), $docCount);
            
            if (!is_array($selectedTypes)) {
                $selectedTypes = [$selectedTypes];
            }
            
            foreach ($selectedTypes as $docType) {
                $status = $this->getRandomVerificationStatus();
                $verifiedAt = $status === 'verified' ? now()->subDays(rand(1, 30)) : null;
                
                DB::table('verification_documents')->insert([
                    'guid' => Str::uuid()->toString(),
                    'user_guid' => $user->guid,
                    'document_type' => $docType,
                    'document_number' => $this->generateDocumentNumber($docType),
                    'document_url' => 'documents/' . strtolower(str_replace(' ', '_', $docType)) . '_' . rand(1000, 9999) . '.pdf',
                    'issued_date' => now()->subYears(rand(1, 5))->format('Y-m-d'),
                    'expiry_date' => now()->addYears(rand(1, 3))->format('Y-m-d'),
                    'status' => $status,
                    'rejection_reason' => $status === 'rejected' ? 'Dokumen tidak lengkap atau tidak jelas' : null,
                    'verified_by' => ($status === 'verified' && $admin) ? $admin->guid : null,
                    'verified_at' => $verifiedAt,
                    'created_at' => now()->subDays(rand(30, 60)),
                    'updated_at' => $verifiedAt ?? now()->subDays(rand(1, 29)),
                ]);
            }
        }
    }
    
    /**
     * Generate a random document number based on document type
     */
    private function generateDocumentNumber(string $docType): string
    {
        return match ($docType) {
            'KTP Pemilik', 'KTP Penanggung Jawab' => rand(1000, 9999) . rand(10000000, 99999999),
            'SIUP', 'Izin Usaha Industri' => 'SIUP-' . rand(100, 999) . '/' . rand(100, 999) . '/' . date('Y'),
            'NPWP', 'NPWP Perusahaan' => rand(10, 99) . '.' . rand(100, 999) . '.' . rand(100, 999) . '.' . rand(1, 9) . '-' . rand(100, 999) . '.' . rand(100, 999),
            'Izin Lingkungan' => 'IL-' . rand(100, 999) . '/' . date('Y'),
            'Sertifikat Proper' => 'PROPER-' . rand(10000, 99999),
            default => Str::upper(Str::random(3)) . '-' . rand(1000, 9999) . '/' . date('Y'),
        };
    }
    
    /**
     * Get a random verification status with weighted probability
     */
    private function getRandomVerificationStatus(): string
    {
        $statuses = [
            'pending' => 20,
            'verified' => 70,
            'rejected' => 10,
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
        
        return 'pending'; // Default fallback
    }
} 