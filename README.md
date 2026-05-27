# PT. Radika Bintang Nusantara — Company Profile

Aplikasi web company profile berbasis **Laravel 11** untuk PT. Radika Bintang Nusantara,
Authorized Supplier & System Integrator komponen elektrikal (Schneider Electric Partner).

## Tech Stack

- Laravel 11 + Blade
- Tailwind CSS (via CDN)
- Alpine.js (interaktivitas ringan)
- SQLite (default) — kompatibel dengan MySQL

## Halaman

- `/` — Home (hero, produk unggulan, company snapshot, brand partners)
- `/catalog` — Katalog produk dengan filter kategori & pencarian
- `/catalog/{slug}` — Detail produk (spesifikasi, fitur, produk terkait)
- `/about` — Profil perusahaan, visi & misi, data legal, sertifikasi

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate

# Database (SQLite — default)
touch database/database.sqlite
php artisan migrate --seed

# Jalankan
php artisan serve
```

### Menggunakan MySQL

Ubah `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=radika_bintang
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan `php artisan migrate --seed`.

## Data Model

- `product_categories` — kategori produk (Distribution Network, Altivar Process, SM AirSeT, dll.)
- `products` — produk dengan `specifications` & `features` (JSON), serta flag `is_featured`

Seeder (`ProductCategorySeeder`, `ProductSeeder`) mengisi data produk berdasarkan
company profile resmi.
