# Cycle Bank

Cycle Bank adalah platform pengelolaan sampah yang menghubungkan nasabah, bank sampah, pengelola sampah, dan pemerintah untuk menangani daur ulang sampah secara efisien dan transparan.

## Deskripsi Aplikasi

Cycle Bank merupakan solusi digital untuk:
- Memudahkan nasabah menjual sampah ke bank sampah
- Membantu bank sampah mengelola inventaris dan transaksi sampah
- Memfasilitasi pengelola sampah untuk membeli sampah dari bank sampah
- Memungkinkan pemerintah memantau dan menganalisis data pengelolaan sampah
- Menyediakan sistem reward dan gamifikasi untuk mendorong partisipasi masyarakat

## Fitur Utama

- Sistem transaksi sampah dengan mekanisme pengecekan kualitas
- Sistem reward dan poin bagi nasabah
- Pelacakan sampah (waste traceability) dari nasabah hingga daur ulang akhir
- Dukungan operasional offline dengan sinkronisasi data
- Dashboard analitik untuk semua pemangku kepentingan

## Requirements

- PHP >= 8.1
- MySQL >= 8.0
- Composer
- Node.js & NPM

## Installation

1. Clone the repository:
```bash
git clone https://github.com/your-username/cycle-bank.git
cd cycle-bank
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install NPM dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cycle_bank
DB_USERNAME=root
DB_PASSWORD=
```

7. Run database migrations and seeders:
```bash
# Migrate fresh database and run all seeders
php artisan migrate:fresh --seed

# Jika ingin menjalankan seeder notifikasi secara terpisah
php artisan db:seed --class=NotificationSeeder
```

## Struktur Database

Aplikasi ini menggunakan UUID sebagai primary key dan referensi antar tabel, tanpa menggunakan foreign key constraints.

Entitas Utama:
- Users: Tabel untuk semua pengguna (customer, waste_bank, waste_manager, government)
- Customers: Profil nasabah
- Waste Banks: Profil bank sampah
- Waste Managers: Profil pengelola sampah
- Government Agencies: Profil institusi pemerintah

Transaksi dan Reward:
- Waste Transactions: Transaksi jual-beli sampah
- Transaction Items: Item sampah dalam transaksi
- Waste Categories: Kategori sampah
- Waste Prices: Harga sampah per kategori di bank sampah
- Customer Rewards: Penukaran reward oleh nasabah

Pelacakan dan Verifikasi:
- Waste Traceability: Pelacakan sampah dari asal hingga daur ulang akhir
- Verification Documents: Dokumen verifikasi pengguna
- Dispute Reports: Laporan sengketa transaksi

Fitur Tambahan:
- Notifications: Notifikasi pengguna
- Sync Logs: Log sinkronisasi data offline

## Development

- Run tests: `php artisan test`
- Run linting: `composer run lint`
- Build assets: `npm run build`
- Start the development server: `php artisan serve`