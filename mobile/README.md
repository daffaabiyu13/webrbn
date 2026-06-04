# Agenda Nusantara

Aplikasi mobile **Todo List** untuk Tes Observasi SerKom BNSP DIPA 2026
— Skema: Pemrograman Aplikasi Mobile Berbasis Database
— TUK: JTI Polinema

**Developer:** Daffa Abiyu — NIM `2241760061`

## Teknologi

- **Framework:** Flutter (Dart)
- **Database lokal:** SQLite (`sqflite`)
- **State sederhana:** `setState` + `SharedPreferences` untuk kredensial
- **Grafik (bonus):** `fl_chart` — bar chart "tugas selesai per hari"

## Fitur

| # | Halaman              | Keterangan                                                                |
|---|----------------------|---------------------------------------------------------------------------|
| 1 | **Login**            | Username/password awal `user`/`user`, bisa diubah di Pengaturan           |
| 2 | **Beranda**          | Statistik tugas selesai & belum, grafik 7 hari, 4 image button menu       |
| 3 | **Tambah Tugas Penting** | Date picker, judul, deskripsi → simpan ke SQLite sebagai `penting`   |
| 4 | **Tambah Tugas Biasa**   | Sama dengan di atas, kategori `biasa`                                |
| 5 | **Daftar Tugas**     | ListView scrollable + filter, arrow merah (penting) / hijau (biasa), checkbox tandai selesai |
| 6 | **Pengaturan**       | Ubah password (verifikasi password lama), kartu developer (foto+nama+NIM) |

## Setup

> **Prasyarat:** Flutter SDK ≥ 3.10, Android SDK / emulator atau perangkat fisik.

```bash
cd mobile

# 1. Generate platform folders (android/ios/etc) jika belum ada
flutter create .

# 2. Install dependencies
flutter pub get

# 3. Pasang foto developer
#    Copy foto Anda ke: assets/images/profile.jpg

# 4. Jalankan di device/emulator
flutter run
```

### Build APK Rilis

```bash
flutter build apk --release
# Output: build/app/outputs/flutter-apk/app-release.apk
```

## Struktur

```
mobile/
├── lib/
│   ├── main.dart                  # Entry point + tema
│   ├── models/
│   │   └── task.dart              # Model Task & enum kategori
│   ├── database/
│   │   └── database_helper.dart   # SQLite helper (CRUD + statistik)
│   ├── services/
│   │   └── auth_service.dart      # Login & ganti password (SharedPreferences)
│   └── pages/
│       ├── login_page.dart
│       ├── home_page.dart         # Beranda
│       ├── add_task_page.dart     # Tambah tugas penting/biasa
│       ├── task_list_page.dart    # Daftar tugas + filter
│       └── settings_page.dart     # Pengaturan + kartu developer
├── assets/images/
│   └── profile.jpg                # Foto developer (taruh manual)
├── pubspec.yaml
└── analysis_options.yaml
```

## Skema Database

Tabel **`tasks`**:

| Kolom         | Tipe    | Keterangan                              |
|---------------|---------|-----------------------------------------|
| `id`          | INTEGER | Primary key autoincrement               |
| `title`       | TEXT    | Judul tugas                             |
| `description` | TEXT    | Deskripsi tugas                         |
| `due_date`    | TEXT    | Tanggal jatuh tempo (ISO 8601)          |
| `category`    | TEXT    | `penting` / `biasa`                     |
| `is_completed`| INTEGER | 0 / 1                                   |
| `completed_at`| TEXT    | Timestamp saat ditandai selesai (nullable) |
| `created_at`  | TEXT    | Timestamp pembuatan                     |

## Catatan untuk Asesor

- **Login default**: `user` / `user`
- Tampilan UI tidak persis sama dengan mockup, namun **semua persyaratan fungsional** pada PDF soal sudah terpenuhi.
- Item Daftar Tugas: **tap = toggle selesai**, **long-press = hapus**.
- Grafik bonus menampilkan jumlah tugas yang diselesaikan dalam 7 hari terakhir berdasarkan kolom `completed_at`.
