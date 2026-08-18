# PT. Radika Bintang Nusantara — Company Profile

Aplikasi web company profile berbasis **Laravel 11** untuk PT. Radika Bintang Nusantara,
Authorized Supplier & System Integrator komponen elektrikal (Schneider Electric Partner).
Dilengkapi **admin panel** untuk CRUD produk dan mengganti gambar background hero.

## Tech Stack

- Laravel 11 + Blade
- Tailwind CSS (via CDN) + Alpine.js
- **MySQL** (default)
- Font: Helvetica Neue (system stack)

## Halaman Publik

- `/` — Home (hero, produk unggulan, company snapshot, brand partners)
- `/catalog` — Katalog produk dengan filter kategori & pencarian
- `/catalog/{slug}` — Detail produk (spesifikasi, fitur, produk terkait)
- `/about` — Profil perusahaan, visi & misi, data legal, sertifikasi

## Admin Panel

URL: `/admin/login`

**Kredensial default (seeder):**

- Email: `admin@radikabintang.com`
- Password: ``

Fitur:
- **Dashboard** — ringkasan jumlah produk, produk unggulan, kategori, dan daftar produk terbaru
- **Produk** — list, search, filter kategori, **create / edit / delete** termasuk upload gambar produk, JSON spesifikasi (`Key: Value` per baris), dan fitur (satu per baris)
- **Settings** — upload / hapus **gambar background hero** untuk halaman Home

## Setup

### 1. Install dependencies

```bash
composer install
cp .env.example .env
php artisan key:generate
```

### 2. MySQL

Buat database & user, lalu sesuaikan `.env`:

```sql
CREATE DATABASE webrbn CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'webrbn'@'localhost' IDENTIFIED BY 'webrbn';
GRANT ALL PRIVILEGES ON webrbn.* TO 'webrbn'@'localhost';
FLUSH PRIVILEGES;
```

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webrbn
DB_USERNAME=webrbn
DB_PASSWORD=webrbn
```

### 3. Migrate, seed, dan link storage

```bash
php artisan migrate --seed
php artisan storage:link
```

Seeder akan mengisi:
- 9 kategori produk + 11 produk berdasarkan company profile resmi
- 1 user admin (kredensial di atas)
- 1 baris settings (`hero_background` = null)

### 4. Jalankan

```bash
php artisan serve
```

Buka `http://127.0.0.1:8000` untuk website publik dan `http://127.0.0.1:8000/admin/login` untuk admin.

## Data Model

- `product_categories` — kategori produk
- `products` — produk dengan `specifications` & `features` (JSON), flag `is_featured`, dan kolom `image` (path di disk `public`)
- `users` — admin login
- `settings` — key/value config (saat ini menyimpan `hero_background`)

## Catatan

- Gambar yang di-upload tersimpan di `storage/app/public/{products,settings}/...` dan diakses lewat symlink `public/storage` (jalankan `php artisan storage:link` sekali setelah clone).
- Form admin produk menerima HTML untuk `full_description` (mis. `<p>`, `<strong>`).
