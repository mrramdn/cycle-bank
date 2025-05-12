# Cycle Bank

A modern banking application built with Laravel 10.

## Requirements

- PHP >= 8.1
- MySQL >= 8.0
- Composer
- Node.js & NPM

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/cycle-bank.git
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

7. Run database migrations:
```bash
php artisan migrate
```

8. Start the development server:
```bash
php artisan serve
```

## Development

- Run tests: `php artisan test`
- Run linting: `composer run lint`
- Build assets: `npm run build`