# Rencana Implementasi Arsitektur SaaS POS (Multi-Tenant) & REST API Mobile

Dokumen ini menyajikan **Analisis Komparatif Mendalam** antara proyek saat ini (`laravel-pos`) dengan arsitektur modern dari [`ultimate-pos-saas-master`](file:///Users/user/Downloads/ultimate-pos-saas-master), serta **Rencana Implementasi Teknis Bertahap (Implementation Plan)** untuk mentransformasi sistem POS toko tunggal menjadi platform **Multi-Tenant SaaS POS** lengkap dengan **REST API untuk Mobile App (React Native / Flutter / Android / iOS)**.

---

## 1. Keputusan Teknis Utama (Architectural Decisions)

1. **Framework & Runtime**: Tetap menggunakan **Laravel 5.5** dan **PHP 7.4** (tanpa upgrade versi framework).
   - Menghindari *breaking changes* dari upgrade 7 versi framework (5.5 -> 12).
   - Mengadopsi arsitektur database, multi-tenancy, dan business logic dari `ultimate-pos-saas-master` dengan sintaks yang 100% kompatibel PHP 7.4 (menggunakan class constants pengganti Enum, switch/array mapping pengganti match, dsb).
2. **Model Multi-Tenancy**: Logical Isolation via `tenant_id` pada setiap tabel bisnis dengan `BelongsToTenant` trait (Eloquent Global Scope) dan `EnsureTenantScope` middleware.
3. **Struktur Organisasi**: `Tenant` (Pemilik Usaha/Merchant) -> `Outlets` (Cabang/Toko) -> `Users` (Kasir, Manager, Owner).
4. **Mobile REST API**: Dibangun di `routes/api.php` dan `app/Http/Controllers/Api/V1/` dengan autentikasi Bearer Token (`api_token` / Sanctum-compatible token), mendukung operasional POS mobile (React Native / Flutter) untuk Shift, Catalog, Checkout, Held Orders, dan Offline Sync.

---

## 2. Matriks Komparatif Arsitektur

| Dimensi Arsitektur | Current Project (`laravel-pos`) | Target Implementation (SaaS + Mobile API) |
| :--- | :--- | :--- |
| **Model Multi-Tenancy** | Single-store ("Toko Aisyah") tanpa isolasi. | **Multi-Tenant (Shared DB, Tenant Scoped)**: Tiap tenant terisolasi via `tenant_id` dan `EnsureTenantScope`. |
| **Struktur Organisasi** | 1 Toko, 1 Lokasi Kasir. | **Multi-Cabang (Multi-Outlet)**: 1 Tenant memiliki N Outlet dengan stok, harga, dan sesi kasir terpisah. |
| **SaaS & Monetisasi** | Tidak ada subscription / billing. | **4 Tier Subscription**: Starter (Rp 99k), Growth (Rp 299k), Pro (Rp 599k), Enterprise (Rp 1.499k), 14 Hari Trial, Grace Period, Frozen mode. |
| **Feature Gating** | Semua fitur terbuka untuk user login. | **Dynamic Feature Flags**: Cek izin fitur per tier via middleware `feature:<feature_name>` dan pembatasan kuota (outlet, user, produk). |
| **Katalog & Inventaris** | Flat inventory (tabel `barangs` tunggal). | **Two-Tier Catalog & Recipe (BOM)**: `inventory_items` (bahan mentah & stok per outlet) + `products` (menu jual, varian, modifiers, recipe). |
| **Shift & Sesi Kasir** | Stateless (tanpa sesi / modal awal). | **POS Sessions (`pos_sessions`)**: Catat modal awal, kas fisik tutup shift, rekonsiliasi selisih kas (*discrepancy*). |
| **Keranjang Belanja** | Tabel sementara MySQL (`detail_penjualan_tbs`). | **Held Orders (`held_orders`)**: Simpan dan panggil kembali keranjang belanja via JSON payload tanpa membebani tabel transaksi. |
| **Mobile App Support** | Tidak ada API (khusus web Blade). | **Full REST API (v1)**: Auth, Outlets, Products, Sessions, Checkout, Payments, Held Orders, Sync. |

---

## 3. Rencana Implementasi Bertahap (Execution Phases)

### Fase 1: Fondasi Multi-Tenancy & Organisasi Cabang (Outlet)
- **Tabel Baru**:
  - `tenants`: data merchant, slug, logo, timezone, currency, pengaturan pajak (`tax_rate`, `tax_mode`, `tax_enabled`), service charge.
  - `outlets`: data cabang per tenant, printer setting, alamat, jam operasional.
  - `user_outlets`: relasi penugasan user/kasir ke cabang tertentu.
- **Trait & Middleware**:
  - `BelongsToTenant`: Trait global scope untuk otomatis menambahkan `where('tenant_id', ...)` pada setiap query model.
  - `EnsureTenantScope`: Middleware pengaman untuk memastikan setiap request user terikat dengan tenant aktif.
  - Update tabel `users`: Menambahkan kolom `tenant_id`, `api_token`, `pin` (untuk manager override).

### Fase 2: SaaS Subscription & Feature Gating
- **Tabel Baru**:
  - `subscription_plans`: Starter, Growth, Professional, Enterprise dengan konfigurasi JSON `features` dan `limits`.
  - `subscriptions`: pencatatan status tenant (`trial`, `active`, `grace_period`, `frozen`, `canceled`), tanggal berakhir, dan riwayat.
  - `subscription_invoices`: invoice tagihan langganan tenant.
- **Middleware**:
  - `CheckSubscriptionStatus`: Memeriksa apakah tenant masih aktif/trial/grace period.
  - `BlockFrozenWrite`: Memblokir aksi tulis (create/update/delete transaksi) jika status akun tenant adalah `frozen` (read-only).
  - `CheckSubscriptionFeature`: Memeriksa apakah fitur yang diakses tersedia pada paket langganan tenant.

### Fase 3: Modernisasi Inventaris, Katalog & Recipe BOM
- **Tabel Baru**:
  - `units`: Satuan (pcs, kg, gr, ml, box).
  - `inventory_items`: Bahan baku / barang dagangan mentah dengan atribut stok minimum & harga modal.
  - `inventory_stocks`: Stok kuantitas per outlet (`outlet_id` + `inventory_item_id`).
  - `stock_movements`: Audit trail pergerakan stok (in, out, transfer, adjustment, waste).
  - `products`: Menu/produk yang dijual ke pelanggan di kasir.
  - `product_variants`: Varian produk (ukuran, warna, porsi).
  - `modifier_groups` & `modifiers`: Opsi tambahan (topping, level gula, dll).
  - `recipes` & `recipe_items`: Menghubungkan produk ke bahan mentah (`inventory_items`) untuk pengurangan stok otomatis saat terjual.

### Fase 4: Engine Transaksi POS & Sesi Kasir
- **Tabel Baru**:
  - `pos_sessions`: Pencatatan shift kasir per outlet (`opening_cash`, `closing_cash_actual`, `expected_cash`, `cash_difference`).
  - `transactions`: Header transaksi POS dengan nomor invoice `{OUTLET}-{YYYYMMDD}-{SEQ}`.
  - `transaction_items`: Item penjualan dengan harga, diskon, dan note.
  - `transaction_payments`: Dukungan multi-payment (Cash, QRIS, Transfer, Kartu Debit/Kredit).
  - `held_orders`: Simpan keranjang belanja sementara.
  - `authorization_settings` & `authorization_logs`: Otorisasi PIN supervisor untuk void/diskon besar.

### Fase 5: RESTful API untuk Mobile App (React Native / Flutter / Android / iOS)
- **Base URL**: `/api/v1`
- **Autentikasi**: Bearer Token via Header `Authorization: Bearer {api_token}`.
- **Header Khusus**: `X-Outlet-Id: {outlet_id}` untuk operasi berbasis outlet.
- **Daftar Endpoint Utama**:
  1. **Auth**:
     - `POST /api/v1/auth/login` (email + password)
     - `POST /api/v1/auth/pin-login` (outlet_id + pin)
     - `GET  /api/v1/auth/me` (profil kasir, izin, outlet aktif)
     - `POST /api/v1/auth/logout`
  2. **Outlets**:
     - `GET  /api/v1/outlets` (daftar outlet yang dapat diakses)
     - `GET  /api/v1/outlets/{id}` (detail outlet, setting pajak & printer)
  3. **Katalog Produk**:
     - `GET  /api/v1/categories` (kategori menu & jumlah produk)
     - `GET  /api/v1/products` (daftar produk, varian, modifiers, harga)
     - `GET  /api/v1/products/search?q={query}` (pencarian instan)
     - `GET  /api/v1/products/barcode/{code}` (scan barcode produk)
  4. **Shift Kasir (Sessions)**:
     - `GET  /api/v1/sessions/current` (cek status shift aktif)
     - `POST /api/v1/sessions/open` (buka shift dengan modal awal)
     - `POST /api/v1/sessions/close` (tutup shift dengan kas fisik aktual)
     - `GET  /api/v1/sessions/{id}/report` (ringkasan omset & selisih kas)
  5. **Checkout & Transaksi**:
     - `POST /api/v1/orders/calculate` (hitung kalkulasi pajak, diskon, service charge)
     - `POST /api/v1/orders/checkout` (simpan transaksi, potong stok, split payment)
     - `GET  /api/v1/transactions` (riwayat transaksi per shift / harian)
     - `GET  /api/v1/transactions/{id}` (detail transaksi & struk cetak)
     - `POST /api/v1/transactions/{id}/void` (void transaksi dengan otorisasi PIN)
  6. **Held Orders (Tahan Keranjang)**:
     - `GET    /api/v1/held-orders` (daftar keranjang ditahan)
     - `POST   /api/v1/held-orders` (simpan keranjang ditahan)
     - `DELETE /api/v1/held-orders/{id}` (recall / hapus keranjang)
  7. **Mobile Sync**:
     - `POST /api/v1/sync/transactions` (sinkronisasi transaksi offline ke server saat online)

### Fase 6: Database Seeders & Demo Data
- Seeder paket langganan: Starter, Growth, Professional, Enterprise.
- Seeder Tenant demo: "Toko Aisyah" dengan status Active Pro.
- Seeder Outlet demo: "Outlet Pusat (Aisyah 01)" dan "Outlet Cabang 02".
- Seeder Akun demo: Superadmin, Owner Toko, Kasir (dengan email, password, dan PIN kasir).
- Seeder Produk demo lengkap dengan varian dan stok awal.

---

## 4. Rencana Verifikasi & Pengujian

### Pengujian API Otomatis (cURL & Integration Tests):
1. Test registrasi/login kasir -> Dapatkan Bearer Token.
2. Test buka shift kasir dengan modal awal Rp 200.000.
3. Test fetch kategori & produk kasir berbasis outlet.
4. Test kalkulasi diskon & checkout transaksi multi-payment.
5. Test tutup shift kasir & validasi perhitungan selisih kas.
6. Test isolasi multi-tenant: Memastikan tenant lain tidak dapat membaca produk atau transaksi tenant ini.
