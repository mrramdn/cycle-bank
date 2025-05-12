<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $users = DB::table('users')->get();
        
        // If no users, exit early
        if ($users->isEmpty()) {
            $this->command->info('No users found. Skipping notification seeding.');
            return;
        }
        
        // Get some transactions for transaction-related notifications
        $transactions = DB::table('waste_transactions')->limit(20)->get();
        
        // Get some rewards for promotion notifications
        $rewards = DB::table('rewards')->limit(5)->get();
        
        // Notification types and templates
        $notificationTypes = [
            'transaction' => [
                'icons' => ['notifications/transaction.png'],
                'titles' => [
                    'Transaksi Baru',
                    'Transaksi Selesai',
                    'Status Transaksi Diperbarui',
                    'Pembayaran Diterima',
                ],
                'messages' => [
                    'Transaksi nomor {transaction_id} telah dibuat.',
                    'Transaksi nomor {transaction_id} telah selesai.',
                    'Status transaksi nomor {transaction_id} telah diperbarui menjadi {status}.',
                    'Pembayaran untuk transaksi nomor {transaction_id} telah diterima.',
                ],
                'actions' => [
                    'Lihat Detail',
                    'Lihat Detail',
                    'Lihat Detail',
                    'Lihat Detail',
                ],
                'action_urls' => [
                    '/transactions/{transaction_id}',
                    '/transactions/{transaction_id}',
                    '/transactions/{transaction_id}',
                    '/transactions/{transaction_id}',
                ],
            ],
            'system' => [
                'icons' => ['notifications/system.png'],
                'titles' => [
                    'Pemeliharaan Sistem',
                    'Versi Baru Tersedia',
                    'Peringatan Keamanan',
                    'Akun Terverifikasi',
                ],
                'messages' => [
                    'Sistem akan mengalami pemeliharaan pada {date}.',
                    'Versi baru aplikasi telah tersedia. Silakan perbarui aplikasi Anda.',
                    'Kami mendeteksi aktivitas mencurigakan pada akun Anda.',
                    'Selamat! Akun Anda telah berhasil diverifikasi.',
                ],
                'actions' => [
                    'Selengkapnya',
                    'Perbarui Sekarang',
                    'Periksa Akun',
                    'OK',
                ],
                'action_urls' => [
                    '/system/maintenance',
                    '/update',
                    '/account/security',
                    null,
                ],
            ],
            'promotion' => [
                'icons' => ['notifications/promotion.png'],
                'titles' => [
                    'Reward Baru',
                    'Poin Bonus',
                    'Program Spesial',
                    'Tingkatkan Level Anda',
                ],
                'messages' => [
                    'Reward baru "{reward_name}" telah tersedia. Tukarkan poin Anda sekarang!',
                    'Dapatkan poin bonus 2x untuk setiap transaksi sampah hingga {end_date}!',
                    'Program spesial! Ajak teman dan dapatkan 500 poin untuk setiap referral.',
                    'Anda hanya butuh {points_needed} poin lagi untuk naik ke level berikutnya!',
                ],
                'actions' => [
                    'Lihat Reward',
                    'Jual Sampah Sekarang',
                    'Bagikan',
                    'Tingkatkan',
                ],
                'action_urls' => [
                    '/rewards/{reward_id}',
                    '/sell',
                    '/referral',
                    '/account/level',
                ],
            ],
        ];
        
        // Create 2-5 notifications for each user
        foreach ($users as $user) {
            $notificationCount = rand(2, 5);
            
            for ($i = 0; $i < $notificationCount; $i++) {
                // Choose a random notification type
                $type = array_rand($notificationTypes);
                $typeData = $notificationTypes[$type];
                
                // Choose random elements for this notification
                $titleIndex = array_rand($typeData['titles']);
                $title = $typeData['titles'][$titleIndex];
                $message = $typeData['messages'][$titleIndex];
                $icon = $typeData['icons'][array_rand($typeData['icons'])];
                $action = $typeData['actions'][$titleIndex];
                $actionUrl = $typeData['action_urls'][$titleIndex];
                
                // Related data
                $relatedGuid = null;
                $data = null;
                
                // Replace placeholders based on type
                if ($type === 'transaction' && !$transactions->isEmpty()) {
                    $transaction = $transactions->random();
                    $relatedGuid = $transaction->guid;
                    $transactionId = substr($transaction->guid, 0, 8);
                    $message = str_replace('{transaction_id}', $transactionId, $message);
                    $message = str_replace('{status}', $transaction->status, $message);
                    $actionUrl = str_replace('{transaction_id}', $transactionId, $actionUrl);
                    $data = json_encode([
                        'transaction_guid' => $transaction->guid,
                        'status' => $transaction->status,
                        'amount' => $transaction->total_amount,
                    ]);
                } else if ($type === 'promotion' && !$rewards->isEmpty()) {
                    $reward = $rewards->random();
                    $relatedGuid = $reward->guid;
                    $message = str_replace('{reward_name}', $reward->name, $message);
                    $message = str_replace('{end_date}', now()->addDays(rand(5, 15))->format('d M Y'), $message);
                    $message = str_replace('{points_needed}', rand(100, 1000), $message);
                    $actionUrl = str_replace('{reward_id}', substr($reward->guid, 0, 8), $actionUrl);
                    $data = json_encode([
                        'reward_guid' => $reward->guid,
                        'points_required' => $reward->points_required,
                        'validity' => now()->addDays(rand(5, 15))->format('Y-m-d'),
                    ]);
                } else if ($type === 'system') {
                    $message = str_replace('{date}', now()->addDays(rand(1, 7))->format('d M Y, H:i'), $message);
                    $data = json_encode([
                        'importance' => rand(1, 3),
                        'auto_dismiss' => rand(0, 1),
                    ]);
                }
                
                // Determine read status (70% chance of being read)
                $isRead = rand(1, 100) <= 70;
                $readAt = $isRead ? now()->subHours(rand(1, 48)) : null;
                
                // Determine expiry (30% chance of expiring)
                $expiresAt = rand(1, 100) <= 30 ? now()->addDays(rand(7, 30)) : null;
                
                // Create notification
                $createdAt = now()->subDays(rand(1, 30));
                DB::table('notifications')->insert([
                    'guid' => Str::uuid()->toString(),
                    'user_guid' => $user->guid,
                    'title' => $title,
                    'message' => $message,
                    'type' => $type,
                    'icon' => $icon,
                    'data' => $data,
                    'related_guid' => $relatedGuid,
                    'action' => $action,
                    'action_url' => $actionUrl,
                    'is_read' => $isRead,
                    'read_at' => $readAt,
                    'expires_at' => $expiresAt,
                    'is_sent' => true,
                    'is_offline_sync' => false,
                    'created_at' => $createdAt,
                ]);
            }
        }
    }
} 