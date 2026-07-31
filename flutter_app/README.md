# AnstheLabel Mobile App

Aplikasi Flutter resmi untuk **AnstheLabel**, terhubung ke Laravel API yang sudah ada di repo utama (`routes/api.php` → prefix `/api/v1`).

Desain mengikuti bahasa visual website: warna primer `#560024` (marun), aksen `#A65A6A`, latar lembut `#FBE9EB`/`#F4D6CC`, tipografi **Montserrat**.

---

## Fitur

- **Beranda** — hero banner otomatis, best product, new arrival carousel, brand story.
- **Katalog** — pencarian, filter (kategori, bahan, warna, ukuran, rentang harga, stok, best product), sortir, pagination.
- **Detail produk** — galeri foto, pilih warna & ukuran, kuantitas, tombol add-to-cart persisten.
- **Keranjang** — persist ke `SharedPreferences`, edit kuantitas, hapus item.
- **Checkout** — form pengiriman, pilih ekspedisi & metode pembayaran, validasi voucher, ringkasan total realtime.
- **Konfirmasi pesanan** — invoice + instruksi transfer + tombol copy nomor rekening.
- **Detail transaksi** — timeline status, item, upload bukti pembayaran, batalkan pesanan.
- **Riwayat pesanan** — cari via email, atau langsung tempel kode invoice.

## Struktur

```
lib/
├── main.dart
├── theme/          # AppTheme, AppColors (palet brand)
├── models/         # Product, ProductDetail, FilterOptions, CartItem, Checkout, Transaction
├── services/       # ApiClient + ProductService, CheckoutService, TransactionService
├── providers/      # CartProvider, CustomerProvider (SharedPreferences)
├── screens/        # Home, Collection, ProductDetail, Cart, Checkout, OrderSuccess, TransactionDetail, TransactionHistory
├── widgets/        # brand_buttons, product_card, section_header, quantity_selector, color_swatch, size_chip, brand_logo, loading_placeholders
└── utils/          # currency (id_ID Rupiah), env (base URL)
```

## Menjalankan

Belum ada folder platform (`android/`, `ios/`, dst.) — hasilkan dengan `flutter create .` sekali di root ini:

```bash
cd flutter_app
flutter create .                # generate platform folders in-place
flutter pub get
flutter run --dart-define=API_BASE_URL=https://ansthelabel.test
```

Ganti `API_BASE_URL` ke domain server Laravel-mu (lokal atau production). Default: `https://ansthelabel.test`.

### Konfigurasi API

- Semua endpoint mengarah ke `${API_BASE_URL}/api/v1/...` — dikendalikan di `lib/utils/env.dart`.
- Model & service sudah 1:1 dengan controller Laravel:
  - `GET /products`, `/products/best`, `/products/filters`, `/products/{id}`
  - `GET /checkout-dependencies`; `POST /checkout/validate-voucher`, `/checkout/calculate`, `/checkout`
  - `GET /transactions`, `/transactions/{invoice}`; `POST .../upload-payment`, `.../cancel`

### Dependency

- `provider` (state), `shared_preferences` (persist cart & customer info)
- `http` (REST), `http_parser` (multipart), `image_picker` (bukti bayar)
- `cached_network_image` (foto produk), `google_fonts` (Montserrat), `intl` (format Rupiah)

## Catatan

- Cart tersimpan lokal di device, tidak perlu login.
- Riwayat pesanan diminta by email (endpoint API-nya memang keyed by email).
- Upload bukti bayar pakai `image_picker` dari galeri (2 MB, jpeg/png sesuai validasi backend).
