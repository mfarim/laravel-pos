<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaaS POS Multi-Tenant - Aplikasi Kasir Cloud, Resep BOM & Mobile API (Gratis)</title>
    <meta name="description" content="Platform Point of Sale (POS) Multi-Tenant & Multi-Outlet berbasis Cloud. Lengkap dengan resep bahan baku (BOM), shift kasir, parkir pesanan, offline sync, dan REST API untuk React Native & Flutter. 100% Gratis cukup Login dengan Google.">
    <meta name="keywords" content="POS SaaS, Point of Sale Indonesia, Aplikasi Kasir Gratis, Multi Tenant POS, Resep BOM, Kasir Mobile React Native Flutter, POS Multi Cabang">
    <meta name="author" content="POS Aisyah Cloud">

    <!-- Open Graph / Meta Social -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="SaaS POS Multi-Tenant - Aplikasi Kasir Cloud Modern">
    <meta property="og:description" content="Kelola penjualan toko, resep bahan baku otomatis, multi-cabang, dan aplikasi mobile kasir. Gratis dengan login Google!">
    <meta property="og:url" content="{{ url('/') }}">

    <!-- Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>

    <!-- ==========================================================================
       Header / Navbar
       ========================================================================== -->
    <header class="site-header" id="navbar">
        <div class="container">
            <nav class="nav-wrap" aria-label="Navigasi Utama">
                <a href="{{ url('/') }}" class="nav-brand" id="brand-logo-link">
                    <div class="brand-icon">🛍️</div>
                    <span>POS Aisyah</span>
                    <span class="brand-badge">SaaS Cloud</span>
                </a>

                <ul class="nav-links" id="main-menu">
                    <li><a href="#fitur" id="nav-link-fitur">Fitur Unggulan</a></li>
                    <li><a href="#demo" id="nav-link-demo">Demo Visual</a></li>
                    <li><a href="#mobile" id="nav-link-mobile">Mobile REST API</a></li>
                    <li><a href="#harga" id="nav-link-harga">Paket Gratis</a></li>
                    <li><a href="#faq" id="nav-link-faq">FAQ</a></li>
                </ul>

                <div class="nav-actions">
                    <a href="{{ route('login') }}" class="btn-login-outline" id="btn-nav-login">Login Kasir</a>
                    <a href="{{ route('auth.google') }}" class="btn-google-nav" id="btn-nav-google">
                        <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                        <span>Masuk Google</span>
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <!-- ==========================================================================
           Hero Section
           ========================================================================== -->
        <section class="hero-section" id="hero">
            <div class="hero-glow"></div>
            <div class="container">
                <div class="hero-content">
                    <div class="pill-announcement" id="hero-badge">
                        <span class="pill-tag">100% GRATIS</span>
                        <span>Cukup Login dengan Google • Langsung Pakai Tanpa Kartu Kredit</span>
                    </div>

                    <h1 class="hero-title">
                        Platform Kasir <span class="gradient-text">Multi-Tenant SaaS</span> dengan Resep BOM & Mobile API
                    </h1>

                    <p class="hero-subtitle">
                        Kelola penjualan multi-cabang, resep bahan baku otomatis, shift laci kasir, dan sinkronisasi offline. Siap terhubung langsung ke aplikasi kasir mobile berbasis <strong>React Native</strong> dan <strong>Flutter</strong>.
                    </p>

                    <div class="hero-cta-group">
                        <a href="{{ route('auth.google') }}" class="btn-google-primary" id="hero-cta-google">
                            <svg width="22" height="22" viewBox="0 0 24 24" aria-hidden="true"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                            <span>Mulai Gratis dengan Google</span>
                        </a>

                        <a href="#fitur" class="btn-demo-secondary" id="hero-cta-features">
                            <span>Jelajahi Fitur</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </a>
                    </div>

                    <div class="hero-trust-badges">
                        <div class="trust-item">
                            <span class="icon">✓</span>
                            <span>Gratis 1 Tahun</span>
                        </div>
                        <div class="trust-item">
                            <span class="icon">✓</span>
                            <span>Tanpa Kartu Kredit</span>
                        </div>
                        <div class="trust-item">
                            <span class="icon">✓</span>
                            <span>Aktivasi Instan via Google</span>
                        </div>
                        <div class="trust-item">
                            <span class="icon">✓</span>
                            <span>Dukungan React Native & Flutter</span>
                        </div>
                    </div>
                </div>

                <!-- Showcase Preview Window -->
                <div class="hero-mockup-wrapper">
                    <div class="mockup-inner">
                        <div class="mockup-header-bar">
                            <div class="browser-dots">
                                <span class="dot dot-red"></span>
                                <span class="dot dot-yellow"></span>
                                <span class="dot dot-green"></span>
                            </div>
                            <div class="browser-url">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                <span>https://pos-saas.app/pemilik/transaksi</span>
                            </div>
                        </div>

                        <div class="mockup-media">
                            <img src="{{ asset('images/screenshots/penjaga-transaksi-penjualan.png') }}" alt="Tampilan Kasir Point of Sale" class="mockup-img">
                            
                            <!-- Floating Stat Widgets -->
                            <div class="floating-widget widget-left">
                                <div class="widget-label">Total Omset Shift Ini</div>
                                <div class="widget-value">Rp 4.850.000</div>
                            </div>

                            <div class="floating-widget widget-right">
                                <div class="widget-label">Stok Bahan Baku (BOM)</div>
                                <div class="widget-value" style="color: #38bdf8;">Otomatis Terpotong 100%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==========================================================================
           Stats Strip
           ========================================================================== -->
        <section class="stats-section" aria-label="Statistik Platform">
            <div class="container">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number">Rp 0</div>
                        <div class="stat-label">Biaya Awal (Gratis via Google)</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">&lt; 50ms</div>
                        <div class="stat-label">Latensi REST API Mobile</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">2 Framework</div>
                        <div class="stat-label">React Native & Flutter Ready</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">99.9%</div>
                        <div class="stat-label">Toleransi Offline Jaringan</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==========================================================================
           Section: Key Features (Bento Grid)
           ========================================================================== -->
        <section class="features-section" id="fitur">
            <div class="container">
                <div class="section-header">
                    <span class="section-tag">Arsitektur Modern</span>
                    <h2 class="section-title">Solusi POS Terlengkap untuk Skala Usaha Mandiri hingga Waralaba</h2>
                    <p class="section-subtitle">
                        Dibangun dengan fondasi arsitektur multi-tenant, menjamin data toko Anda terisolasi aman, performa secepat kilat, dan siap dikembangkan kapan saja.
                    </p>
                </div>

                <div class="features-grid">
                    <!-- Feature 1 -->
                    <article class="feature-card highlight" id="feature-tenant">
                        <div class="feature-icon-box">🏢</div>
                        <h3>Multi-Tenant & Multi-Outlet</h3>
                        <p>
                            Satu akun dapat menaungi banyak cabang usaha. Setiap outlet memiliki katalog harga, stok gudang, dan staf kasirnya masing-masing dengan isolasi data terenkripsi.
                        </p>
                    </article>

                    <!-- Feature 2 -->
                    <article class="feature-card" id="feature-bom">
                        <div class="feature-icon-box emerald">🥣</div>
                        <h3>Resep & Bill of Materials (BOM)</h3>
                        <p>
                            Ideal untuk F&B dan retail produksi. Saat menu <em>Es Kopi Susu</em> terjual, sistem otomatis menghitung dan memotong gramatur biji kopi, susu, dan sirup di gudang.
                        </p>
                    </article>

                    <!-- Feature 3 -->
                    <article class="feature-card highlight" id="feature-mobile">
                        <div class="feature-icon-box purple">📱</div>
                        <h3>Mobile POS (React Native & Flutter)</h3>
                        <p>
                            Dilengkapi REST API V1 yang teruji untuk integrasi aplikasi mobile di tablet kasir, smartphone Android/iOS, maupun mesin POS portabel (Sunmi, Pax, dsb).
                        </p>
                    </article>

                    <!-- Feature 4 -->
                    <article class="feature-card" id="feature-shifts">
                        <div class="feature-icon-box amber">⏱️</div>
                        <h3>Manajemen Shift & Laci Kasir</h3>
                        <p>
                            Kontrol uang kasir secara ketat dengan pencatatan kas modal awal, kas masuk/keluar, kas akhir saat pergantian shift, dan kalkulasi otomatis selisih kas.
                        </p>
                    </article>

                    <!-- Feature 5 -->
                    <article class="feature-card" id="feature-park">
                        <div class="feature-icon-box emerald">⏸️</div>
                        <h3>Parkir Pesanan & Split-Payment</h3>
                        <p>
                            Tahan sementara pesanan pelanggan meja atau antrean dan lanjutkan kembali saat pembayaran. Terima kombinasi bayar Tunai, QRIS, dan Kartu Debit dalam satu struk.
                        </p>
                    </article>

                    <!-- Feature 6 -->
                    <article class="feature-card" id="feature-pin">
                        <div class="feature-icon-box">🛡️</div>
                        <h3>Otorisasi PIN Supervisor</h3>
                        <p>
                            Cegah kecurangan kasir. Aksi pembatalan (void), pengembalian dana (refund), dan diskon khusus mewajibkan verifikasi 6-digit PIN Supervisor dengan audit log lengkap.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <!-- ==========================================================================
           Section: Interactive Demo Tabs
           ========================================================================== -->
        <section class="demo-section" id="demo">
            <div class="container">
                <div class="section-header">
                    <span class="section-tag">Antarmuka Sistem</span>
                    <h2 class="section-title">Lihat Kemudahan Operasional Langsung dari Layar</h2>
                    <p class="section-subtitle">
                        Desain UI yang bersih, responsif, dan mudah digunakan bahkan oleh staf kasir baru tanpa perlu pelatihan berhari-hari.
                    </p>
                </div>

                <div class="tabs-nav" role="tablist">
                    <button class="tab-btn active" data-tab="tab-pos" id="tab-btn-pos">
                        <span>🛒</span> Terminal Penjualan Kasir
                    </button>
                    <button class="tab-btn" data-tab="tab-restock" id="tab-btn-restock">
                        <span>📦</span> Pembelian & Restock
                    </button>
                    <button class="tab-btn" data-tab="tab-analytics" id="tab-btn-analytics">
                        <span>📈</span> Analitik Grafik Penjualan
                    </button>
                    <button class="tab-btn" data-tab="tab-catalog" id="tab-btn-catalog">
                        <span>🏷️</span> Master Data Barang & BOM
                    </button>
                </div>

                <!-- Tab Panel 1: Kasir -->
                <div class="tab-content-panel active" id="tab-pos">
                    <div class="tab-text">
                        <h3>Layar Kasir Cepat & Praktis</h3>
                        <p>
                            Pencarian instan melalui barcode scanner atau nama produk. Hitung diskon member, pajak otomatis, dan cetak struk belanja thermal dalam hitungan detik.
                        </p>
                        <ul class="tab-checklist">
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Mendukung barcode scanner USB & Bluetooth</span>
                            </li>
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Multi-pembayaran: Cash, QRIS, EDC Bank</span>
                            </li>
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Fitur parkir keranjang belanja untuk melayani antrean lain</span>
                            </li>
                        </ul>
                        <a href="{{ route('auth.google') }}" class="btn-google-primary">Uji Coba Sekarang (Gratis)</a>
                    </div>
                    <div class="tab-media-box">
                        <img src="{{ asset('images/screenshots/penjaga-transaksi-penjualan.png') }}" alt="Terminal Penjualan Kasir">
                    </div>
                </div>

                <!-- Tab Panel 2: Pembelian -->
                <div class="tab-content-panel" id="tab-restock">
                    <div class="tab-text">
                        <h3>Pencatatan Masuk Stok & Supplier</h3>
                        <p>
                            Catat pembelian barang dagangan atau bahan baku mentah dari supplier secara rapi. Stok gudang otomatis bertambah dan jurnal pengeluaran kas tercatat otomatis.
                        </p>
                        <ul class="tab-checklist">
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Cetak Purchase Order (PO) & Bukti Masuk Barang</span>
                            </li>
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Riwayat histori harga beli per pemasok</span>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-media-box">
                        <img src="{{ asset('images/screenshots/pemilik-transaksi-pembelian.png') }}" alt="Transaksi Pembelian Barang">
                    </div>
                </div>

                <!-- Tab Panel 3: Analitik -->
                <div class="tab-content-panel" id="tab-analytics">
                    <div class="tab-text">
                        <h3>Grafik Penjualan & Laba Real-Time</h3>
                        <p>
                            Pantau tren omset tahunan, produk terlaris, dan kinerja kasir dalam bentuk visual interaktif. Membantu pemilik usaha mengambil keputusan ekspansi berbasis data.
                        </p>
                        <ul class="tab-checklist">
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Laporan laba kotor & buku kas otomatis</span>
                            </li>
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Kartu persediaan barang masuk & keluar</span>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-media-box">
                        <img src="{{ asset('images/screenshots/penjaga-grafik-penjualan.png') }}" alt="Grafik Penjualan POS">
                    </div>
                </div>

                <!-- Tab Panel 4: Katalog -->
                <div class="tab-content-panel" id="tab-catalog">
                    <div class="tab-text">
                        <h3>Katalog Produk, Varian & Resep</h3>
                        <p>
                            Kelola ribuan SKU barang dengan kategori bertingkat, satuan unit (Pcs, Kg, Liter), varian harga, serta formula resep bahan baku yang fleksibel.
                        </p>
                        <ul class="tab-checklist">
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Auto-generate barcode format EAN/Code128</span>
                            </li>
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Notifikasi peringatan saat stok mendekati batas minimum</span>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-media-box">
                        <img src="{{ asset('images/screenshots/penjaga-data-barang.png') }}" alt="Data Master Barang">
                    </div>
                </div>
            </div>
        </section>

        <!-- ==========================================================================
           Section: Mobile REST API (React Native & Flutter)
           ========================================================================== -->
        <section class="api-mobile-section" id="mobile">
            <div class="container">
                <div class="api-grid">
                    <div class="api-text">
                        <div class="api-badges">
                            <span class="badge-tech">⚛️ React Native</span>
                            <span class="badge-tech">🐦 Flutter</span>
                            <span class="badge-tech">⚡ REST API V1</span>
                        </div>
                        <h2 class="section-title" style="color: #ffffff;">Arsitektur REST API Terintegrasi untuk Mobile POS</h2>
                        <p style="color: #94a3b8; font-size: 1.05rem; margin-bottom: 24px; line-height: 1.6;">
                            Bangun antarmuka kasir mobile impian Anda menggunakan <strong>React Native</strong> atau <strong>Flutter</strong>. Backend POS kami menyediakan endpoint RESTful yang ringan, terisolasi per tenant, dan dilengkapi mekanisme <em>Offline Sync Queue</em>.
                        </p>
                        <ul class="tab-checklist">
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Autentikasi aman berbasis Bearer API Token</span>
                            </li>
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Sinkronisasi offline: Kasir tetap aktif saat internet terputus</span>
                            </li>
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Kompatibel dengan POS hardware Android (Sunmi, Pax, iMin)</span>
                            </li>
                        </ul>
                    </div>

                    <div class="code-preview-window">
                        <div class="code-header">
                            <div class="browser-dots">
                                <span class="dot dot-red"></span>
                                <span class="dot dot-yellow"></span>
                                <span class="dot dot-green"></span>
                            </div>
                            <span class="code-title">POST /api/v1/orders/checkout</span>
                        </div>
                        <pre class="code-body"><code><span class="code-comment">// Sample Request from React Native / Flutter Client</span>
{
  <span class="code-keyword">"outlet_id"</span>: <span class="code-string">1</span>,
  <span class="code-keyword">"pos_session_id"</span>: <span class="code-string">42</span>,
  <span class="code-keyword">"customer_name"</span>: <span class="code-string">"Budi Santoso"</span>,
  <span class="code-keyword">"items"</span>: [
    {
      <span class="code-keyword">"product_id"</span>: <span class="code-string">10</span>,
      <span class="code-keyword">"quantity"</span>: <span class="code-string">2</span>,
      <span class="code-keyword">"unit_price"</span>: <span class="code-string">25000</span>
    }
  ],
  <span class="code-keyword">"payments"</span>: [
    { <span class="code-keyword">"payment_method_code"</span>: <span class="code-string">"QRIS"</span>, <span class="code-keyword">"amount"</span>: <span class="code-string">50000</span> }
  ]
}</code></pre>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==========================================================================
           Section: Free Pricing & Google Login
           ========================================================================== -->
        <section class="pricing-section" id="harga">
            <div class="container">
                <div class="section-header">
                    <span class="section-tag">Akses Gratis</span>
                    <h2 class="section-title">Mulai Sekarang — 100% Gratis dengan Google</h2>
                    <p class="section-subtitle">
                        Tanpa formulir panjang, tanpa biaya tersembunyi, dan tanpa perlu memasukkan nomor kartu kredit. Cukup satu klik untuk mengaktifkan sistem POS toko Anda.
                    </p>
                </div>

                <div class="pricing-card-free">
                    <div class="free-ribbon">GRATIS</div>
                    <div class="free-info">
                        <div class="free-price-tag">Rp 0</div>
                        <div class="free-price-sub">Gratis 1 Tahun untuk Toko & UMKM</div>

                        <ul class="tab-checklist" style="margin-bottom: 0;">
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Akses Penuh Fitur Kasir & Penjualan</span>
                            </li>
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Manajemen Produk & Varian SKU</span>
                            </li>
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Akses REST API Mobile (React Native & Flutter)</span>
                            </li>
                            <li>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Manajemen Shift & Laporan Keuangan Harian</span>
                            </li>
                        </ul>
                    </div>

                    <div class="free-cta-box">
                        <a href="{{ route('auth.google') }}" class="btn-google-primary" style="width: 100%; justify-content: center; box-shadow: 0 4px 14px rgba(0,0,0,0.12);" id="pricing-cta-google">
                            <svg width="20" height="20" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                            <span>Daftar Cepat via Google</span>
                        </a>
                        <p>
                            Setup otomatis instan dalam 10 detik. Langsung masuk ke dashboard manajemen toko Anda.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==========================================================================
           Section: Frequently Asked Questions (FAQ)
           ========================================================================== -->
        <section class="faq-section" id="faq">
            <div class="container">
                <div class="section-header">
                    <span class="section-tag">Pertanyaan Populer</span>
                    <h2 class="section-title">Frequently Asked Questions</h2>
                    <p class="section-subtitle">
                        Segala hal yang perlu Anda ketahui mengenai akses gratis dan penggunaan sistem POS ini.
                    </p>
                </div>

                <div class="faq-container">
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Apakah benar-benar gratis hanya dengan login Google?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-inner">
                                Ya, benar! Anda cukup mengklik tombol "Masuk dengan Google". Sistem kami akan secara otomatis membuatkan akun Tenant toko gratis Anda lengkap dengan outlet utama, paket lisensi aktif, dan hak akses pemilik tanpa memungut biaya langganan.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Apakah aplikasi mobile mendukung React Native dan Flutter?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-inner">
                                Sangat mendukung! Backend POS ini dilengkapi dengan REST API V1 standar yang dirancang khusus untuk klien mobile modern, baik yang dikembangkan dengan <strong>React Native</strong> maupun <strong>Flutter</strong>, termasuk dukungan hardware POS Android seperti Sunmi dan Pax.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Bagaimana jika koneksi internet kasir tiba-tiba terputus?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-inner">
                                Arsitektur REST API V1 kami memiliki endpoint <code>/api/v1/sync/pull</code> dan <code>/api/v1/sync/push</code>. Klien mobile dapat menyimpan antrean transaksi di penyimpanan lokal dan mengunggahnya secara otomatis ketika sinyal internet pulih.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Bagaimana cara kerja pengurangan stok resep (BOM)?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-inner">
                                Anda dapat menautkan resep (Bill of Materials) pada suatu menu makanan/minuman. Setiap kali kasir menyelesaikan pembayaran untuk produk tersebut, sistem secara otomatis menghitung dan memotong stok bahan baku mentah di gudang outlet secara akurat.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==========================================================================
           Bottom Final CTA
           ========================================================================== -->
        <section class="cta-bottom-section">
            <div class="container">
                <h2 class="cta-bottom-title">Siap Meningkatkan Penjualan Toko Anda Hari Ini?</h2>
                <p class="cta-bottom-subtitle">
                    Bergabung sekarang dan nikmati seluruh kemudahan platform POS modern secara cuma-cuma hanya dengan satu klik akun Google Anda.
                </p>
                <a href="{{ route('auth.google') }}" class="btn-google-primary" id="bottom-cta-google">
                    <svg width="22" height="22" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                    <span>Masuk dengan Google (100% Gratis)</span>
                </a>
            </div>
        </section>
    </main>

    <!-- ==========================================================================
       Footer
       ========================================================================== -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-top">
                <div class="nav-brand" style="color: #ffffff;">
                    <div class="brand-icon">🛍️</div>
                    <span>POS Aisyah Cloud</span>
                </div>
                <div class="footer-links">
                    <a href="#fitur">Fitur</a>
                    <a href="#demo">Demo Visual</a>
                    <a href="#mobile">Mobile API</a>
                    <a href="#harga">Paket Gratis</a>
                    <a href="{{ route('login') }}">Login Kasir</a>
                </div>
            </div>
            <div class="footer-bottom">
                <div>&copy; {{ date('Y') }} POS Aisyah SaaS. Solusi Kasir Multi-Tenant & Mobile POS Modern.</div>
                <div>Dirancang untuk efisiensi retail, resto, kafe, dan franchise.</div>
            </div>
        </div>
    </footer>

    <!-- Interactive Scripts for Tabs and FAQ Accordions -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Tab Switcher
            var tabBtns = document.querySelectorAll('.tab-btn');
            var tabPanels = document.querySelectorAll('.tab-content-panel');

            tabBtns.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var targetId = this.getAttribute('data-tab');

                    tabBtns.forEach(function (b) { b.classList.remove('active'); });
                    tabPanels.forEach(function (p) { p.classList.remove('active'); });

                    this.classList.add('active');
                    var targetPanel = document.getElementById(targetId);
                    if (targetPanel) {
                        targetPanel.classList.add('active');
                    }
                });
            });

            // FAQ Accordion
            var faqQuestions = document.querySelectorAll('.faq-question');
            faqQuestions.forEach(function (question) {
                question.addEventListener('click', function () {
                    var parentItem = this.closest('.faq-item');
                    var isActive = parentItem.classList.contains('active');

                    // Close other items
                    document.querySelectorAll('.faq-item').forEach(function (item) {
                        item.classList.remove('active');
                    });

                    if (!isActive) {
                        parentItem.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>
</html>
