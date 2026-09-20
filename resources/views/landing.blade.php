<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-i18n="meta_title">Multi-Tenant SaaS POS - Cloud Point of Sale, Recipe BOM & Mobile API (Free)</title>
    <meta name="description" content="Modern Cloud Multi-Tenant & Multi-Outlet Point of Sale (POS) platform. Complete with Recipe BOM auto-deduction, shift management, held orders, offline sync, and REST API for React Native & Flutter. 100% Free with Google Sign-In." data-i18n-content="meta_description">
    <meta name="keywords" content="POS SaaS, Point of Sale, Free POS App, Multi Tenant POS, Recipe BOM, Mobile POS React Native Flutter, Multi Outlet POS, Light Blue Theme">
    <meta name="author" content="POS Aisyah Cloud">

    <!-- Open Graph / Meta Social -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Multi-Tenant SaaS POS - Modern Cloud Point of Sale" data-i18n-content="og_title">
    <meta property="og:description" content="Manage multi-outlet sales, automated recipe BOM inventory, and mobile cashier apps. 100% Free with Google Sign-In!" data-i18n-content="og_description">
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
       Header / Navbar (Tema Terang Biru dengan Language Selector)
       ========================================================================== -->
    <header class="site-header" id="navbar">
        <div class="container">
            <nav class="nav-wrap" aria-label="Main Navigation">
                <a href="{{ url('/') }}" class="nav-brand" id="brand-logo-link">
                    <div class="brand-icon">🛍️</div>
                    <span>POS Aisyah</span>
                    <span class="brand-badge" data-i18n="nav_badge">SaaS Cloud</span>
                </a>

                <ul class="nav-links" id="main-menu">
                    <li><a href="#fitur" id="nav-link-fitur" data-i18n="nav_features">Features</a></li>
                    <li><a href="#demo" id="nav-link-demo" data-i18n="nav_demo">Visual Demo</a></li>
                    <li><a href="#mobile" id="nav-link-mobile" data-i18n="nav_mobile">Mobile REST API</a></li>
                    <li><a href="#harga" id="nav-link-harga" data-i18n="nav_pricing">Free Plan</a></li>
                    <li><a href="#faq" id="nav-link-faq" data-i18n="nav_faq">FAQ</a></li>
                </ul>

                <div class="nav-actions">
                    <!-- Language Selector (English Default / Bahasa Indonesia) -->
                    <div class="lang-switcher" id="lang-switcher" role="group" aria-label="Language Selector">
                        <button type="button" class="lang-btn active" data-lang="en" id="btn-lang-en" title="English">
                            <span class="flag">🇺🇸</span> EN
                        </button>
                        <button type="button" class="lang-btn" data-lang="id" id="btn-lang-id" title="Bahasa Indonesia">
                            <span class="flag">🇮🇩</span> ID
                        </button>
                    </div>

                    <a href="{{ route('login') }}" class="btn-login-outline" id="btn-nav-login" data-i18n="btn_login">Cashier Login</a>
                    <a href="{{ route('auth.google') }}" class="btn-google-nav" id="btn-nav-google">
                        <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path fill="#ffffff" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#ffffff" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#ffffff" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#ffffff" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                        <span data-i18n="btn_google_nav">Sign in with Google</span>
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <!-- ==========================================================================
           Hero Section (Tema Terang Biru)
           ========================================================================== -->
        <section class="hero-section" id="hero">
            <div class="hero-glow"></div>
            <div class="container">
                <div class="hero-content">
                    <div class="pill-announcement" id="hero-badge">
                        <span class="pill-tag" data-i18n="hero_pill_tag">100% FREE</span>
                        <span data-i18n="hero_pill_text">Sign in with Google • Instant Setup with Zero Credit Card Required</span>
                    </div>

                    <h1 class="hero-title" data-i18n="hero_title">
                        Modern Cloud <span class="gradient-text">Multi-Tenant POS</span> for Retail &amp; F&amp;B Businesses
                    </h1>

                    <p class="hero-subtitle" data-i18n="hero_subtitle">
                        Streamline your multi-outlet sales operations with automated Recipe Bill of Materials (BOM), cashier cash drawer shifts, parked orders, and lightning-fast REST API ready for React Native and Flutter mobile POS apps.
                    </p>

                    <div class="hero-cta-group">
                        <a href="{{ route('auth.google') }}" class="btn-google-primary" id="hero-cta-google">
                            <svg width="22" height="22" viewBox="0 0 24 24" aria-hidden="true"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                            <span data-i18n="btn_hero_google">Start Free with Google</span>
                        </a>

                        <a href="#fitur" class="btn-demo-secondary" id="hero-cta-features">
                            <span data-i18n="btn_explore_features">Explore Features</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </a>
                    </div>

                    <div class="hero-trust-badges">
                        <div class="trust-item">
                            <span class="icon">✓</span>
                            <span data-i18n="trust_free">Free Forever</span>
                        </div>
                        <div class="trust-item">
                            <span class="icon">✓</span>
                            <span data-i18n="trust_no_cc">No Credit Card Needed</span>
                        </div>
                        <div class="trust-item">
                            <span class="icon">✓</span>
                            <span data-i18n="trust_instant">Instant Google Onboarding</span>
                        </div>
                        <div class="trust-item">
                            <span class="icon">✓</span>
                            <span data-i18n="trust_mobile">React Native &amp; Flutter Ready</span>
                        </div>
                    </div>
                </div>

                <!-- Showcase Preview Window (Light Frame) -->
                <div class="hero-mockup-wrapper">
                    <div class="mockup-inner">
                        <div class="mockup-header-bar">
                            <div class="browser-dots">
                                <span class="dot dot-red"></span>
                                <span class="dot dot-yellow"></span>
                                <span class="dot dot-green"></span>
                            </div>
                            <div class="browser-url">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                <span>https://pos-saas.app/pemilik/transaksi</span>
                            </div>
                        </div>

                        <div class="mockup-media">
                            <img src="{{ asset('images/screenshots/penjaga-transaksi-penjualan.png') }}" alt="Point of Sale Cashier Terminal Preview" class="mockup-img">
                            
                            <!-- Floating Stat Widgets -->
                            <div class="floating-widget widget-left">
                                <div class="widget-label" data-i18n="mockup_stat_revenue_label">Today's Shift Revenue</div>
                                <div class="widget-value" data-i18n="mockup_stat_revenue_val">Rp 4.850.000</div>
                            </div>

                            <div class="floating-widget widget-right">
                                <div class="widget-label" data-i18n="mockup_stat_bom_label">Raw Ingredient BOM</div>
                                <div class="widget-value" data-i18n="mockup_stat_bom_val">100% Auto-Deducted</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==========================================================================
           Stats Strip (Tema Terang Biru)
           ========================================================================== -->
        <section class="stats-section" aria-label="Platform Statistics">
            <div class="container">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number" data-i18n="stat_1_num">$0 / Free</div>
                        <div class="stat-label" data-i18n="stat_1_lbl">Zero Upfront Cost (Free via Google)</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" data-i18n="stat_2_num">&lt; 50ms</div>
                        <div class="stat-label" data-i18n="stat_2_lbl">Mobile REST API Latency</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" data-i18n="stat_3_num">2 Mobile SDKs</div>
                        <div class="stat-label" data-i18n="stat_3_lbl">React Native &amp; Flutter Ready</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" data-i18n="stat_4_num">99.9%</div>
                        <div class="stat-label" data-i18n="stat_4_lbl">Offline Network Tolerance</div>
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
                    <span class="section-tag" data-i18n="features_tag">Full Features</span>
                    <h2 class="section-title" data-i18n="features_title">All Your Retail &amp; F&amp;B Operations in One Unified App</h2>
                    <p class="section-subtitle" data-i18n="features_subtitle">
                        Powered by enterprise-grade multi-tenant architecture to guarantee isolated tenant data, high-speed checkout performance, and effortless multi-branch scaling.
                    </p>
                </div>

                <div class="features-grid">
                    <!-- Feature 1 -->
                    <article class="feature-card highlight" id="feature-tenant">
                        <div class="feature-icon-box">🏢</div>
                        <h3 data-i18n="feat_1_title">Multi-Tenant &amp; Multi-Outlet</h3>
                        <p data-i18n="feat_1_desc">
                            Manage headquarters and branch franchise outlets in one unified system. Each outlet has its own inventory stocks, localized price tiers, and cashier staff access.
                        </p>
                    </article>

                    <!-- Feature 2 -->
                    <article class="feature-card" id="feature-bom">
                        <div class="feature-icon-box emerald">🥣</div>
                        <h3 data-i18n="feat_2_title">Recipe &amp; Bill of Materials (BOM)</h3>
                        <p data-i18n="feat_2_desc">
                            Perfect for restaurants, coffee shops, and food retail. When an item like Iced Latte is sold, the system automatically calculates and deducts raw coffee beans, milk, and syrup from stock.
                        </p>
                    </article>

                    <!-- Feature 3 -->
                    <article class="feature-card highlight" id="feature-mobile">
                        <div class="feature-icon-box cyan">📱</div>
                        <h3 data-i18n="feat_3_title">Mobile POS (React Native &amp; Flutter)</h3>
                        <p data-i18n="feat_3_desc">
                            Backed by our ultra-fast V1 REST API specifically designed for mobile cashier tablets, Android/iOS smartphones, and dedicated handheld POS devices (Sunmi, Pax, iMin).
                        </p>
                    </article>

                    <!-- Feature 4 -->
                    <article class="feature-card" id="feature-shifts">
                        <div class="feature-icon-box amber">⏱️</div>
                        <h3 data-i18n="feat_4_title">Cashier Shifts &amp; Cash Drawer</h3>
                        <p data-i18n="feat_4_desc">
                            Opening float tracking, cash in/out recording, end-of-shift cash counting, and automated discrepancy audits to eliminate cashier drawer shortages.
                        </p>
                    </article>

                    <!-- Feature 5 -->
                    <article class="feature-card" id="feature-park">
                        <div class="feature-icon-box emerald">⏸️</div>
                        <h3 data-i18n="feat_5_title">Held Orders &amp; Multi-Payment</h3>
                        <p data-i18n="feat_5_desc">
                            Hold temporary table orders or queue carts and resume checkout anytime. Accept split-payments combining Cash, dynamic QRIS, and Credit/Debit Cards on a single receipt.
                        </p>
                    </article>

                    <!-- Feature 6 -->
                    <article class="feature-card" id="feature-pin">
                        <div class="feature-icon-box">🛡️</div>
                        <h3 data-i18n="feat_6_title">Supervisor PIN Authorization</h3>
                        <p data-i18n="feat_6_desc">
                            High-level operational security. Item voids, order refunds, and custom manager discounts require 6-digit Supervisor PIN verification with full audit logging.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <!-- ==========================================================================
           Section: Interactive Demo Tabs (Tema Terang Biru)
           ========================================================================== -->
        <section class="demo-section" id="demo">
            <div class="container">
                <div class="section-header">
                    <span class="section-tag" data-i18n="demo_tag">App Interface</span>
                    <h2 class="section-title" data-i18n="demo_title">Live Store Operational Screen Previews</h2>
                    <p class="section-subtitle" data-i18n="demo_subtitle">
                        Clean, responsive, and intuitive interface. Empowering cashiers and store owners to operate faster with zero steep learning curve.
                    </p>
                </div>

                <div class="tabs-nav" role="tablist">
                    <button class="tab-btn active" data-tab="tab-pos" id="tab-btn-pos">
                        <span>🛒</span> <span data-i18n="tab_nav_pos">Cashier Terminal</span>
                    </button>
                    <button class="tab-btn" data-tab="tab-restock" id="tab-btn-restock">
                        <span>📦</span> <span data-i18n="tab_nav_restock">Purchasing &amp; Restock</span>
                    </button>
                    <button class="tab-btn" data-tab="tab-analytics" id="tab-btn-analytics">
                        <span>📈</span> <span data-i18n="tab_nav_analytics">Sales Analytics &amp; Charts</span>
                    </button>
                    <button class="tab-btn" data-tab="tab-catalog" id="tab-btn-catalog">
                        <span>🏷️</span> <span data-i18n="tab_nav_catalog">Master Products &amp; BOM</span>
                    </button>
                </div>

                <!-- Tab Panel 1: Kasir -->
                <div class="tab-content-panel active" id="tab-pos">
                    <div class="tab-text">
                        <h3 data-i18n="tab_pos_heading">Fast &amp; Responsive Cashier Terminal</h3>
                        <p data-i18n="tab_pos_desc">
                            Instant search by barcode scanning or product name. Apply automatic member discounts, tax calculations, and print thermal receipts in seconds.
                        </p>
                        <ul class="tab-checklist">
                            <li>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span data-i18n="tab_pos_item1">Supports USB &amp; Bluetooth barcode scanners</span>
                            </li>
                            <li>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span data-i18n="tab_pos_item2">Multi-payment: Cash, QRIS, Bank EDC</span>
                            </li>
                            <li>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span data-i18n="tab_pos_item3">Cart parking to serve waiting customers in queue</span>
                            </li>
                        </ul>
                        <a href="{{ route('auth.google') }}" class="btn-demo-secondary" style="display: inline-flex;" data-i18n="btn_try_live">Try Live (100% Free)</a>
                    </div>
                    <div class="tab-media-box">
                        <img src="{{ asset('images/screenshots/penjaga-transaksi-penjualan.png') }}" alt="Cashier Sales Terminal">
                    </div>
                </div>

                <!-- Tab Panel 2: Pembelian -->
                <div class="tab-content-panel" id="tab-restock">
                    <div class="tab-text">
                        <h3 data-i18n="tab_restock_heading">Restock &amp; Supplier Management</h3>
                        <p data-i18n="tab_restock_desc">
                            Record inbound merchandise and raw ingredients from suppliers. Stock levels update in real-time and cash disbursement ledgers are logged automatically.
                        </p>
                        <ul class="tab-checklist">
                            <li>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span data-i18n="tab_restock_item1">Print Purchase Orders (PO) &amp; Goods Receipts</span>
                            </li>
                            <li>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span data-i18n="tab_restock_item2">Supplier purchase price history for profit margin tracking</span>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-media-box">
                        <img src="{{ asset('images/screenshots/pemilik-transaksi-pembelian.png') }}" alt="Purchasing & Restock Transactions">
                    </div>
                </div>

                <!-- Tab Panel 3: Analitik -->
                <div class="tab-content-panel" id="tab-analytics">
                    <div class="tab-text">
                        <h3 data-i18n="tab_analytics_heading">Real-Time Sales &amp; Revenue Analytics</h3>
                        <p data-i18n="tab_analytics_desc">
                            Monitor monthly sales trends, best-selling menu items, and cashier performance with interactive charts to drive data-backed business growth.
                        </p>
                        <ul class="tab-checklist">
                            <li>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span data-i18n="tab_analytics_item1">Automated gross profit &amp; cash flow reports</span>
                            </li>
                            <li>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span data-i18n="tab_analytics_item2">Inventory balance cards tracking SKU ins and outs</span>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-media-box">
                        <img src="{{ asset('images/screenshots/penjaga-grafik-penjualan.png') }}" alt="Sales Analytics Chart">
                    </div>
                </div>

                <!-- Tab Panel 4: Katalog -->
                <div class="tab-content-panel" id="tab-catalog">
                    <div class="tab-text">
                        <h3 data-i18n="tab_catalog_heading">Master Products, Variants &amp; Recipe Formulas</h3>
                        <p data-i18n="tab_catalog_desc">
                            Organize thousands of product SKUs with multi-level categories, units (Pcs, Grams, Liters), variant pricing, and flexible BOM recipe formulas.
                        </p>
                        <ul class="tab-checklist">
                            <li>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span data-i18n="tab_catalog_item1">Auto-generate EAN/Code128 barcodes</span>
                            </li>
                            <li>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span data-i18n="tab_catalog_item2">Minimum stock warning before ingredients run dry</span>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-media-box">
                        <img src="{{ asset('images/screenshots/penjaga-data-barang.png') }}" alt="Master Product Data">
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
                        <h2 class="section-title" data-i18n="api_title">Standardized REST API for Mobile POS Applications</h2>
                        <p style="font-size: 1.05rem; margin-bottom: 24px; line-height: 1.65; color: var(--text-body);" data-i18n="api_desc">
                            Connect your custom mobile POS apps built with <strong>React Native</strong> or <strong>Flutter</strong>. Our backend provides lightweight, secure RESTful endpoints equipped with an <em>Offline Sync Queue</em>.
                        </p>
                        <ul class="tab-checklist">
                            <li>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span data-i18n="api_item1">Secure Bearer Token authentication</span>
                            </li>
                            <li>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span data-i18n="api_item2">Offline synchronization: Cashiers stay operational during internet outages</span>
                            </li>
                            <li>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span data-i18n="api_item3">Ready for Android POS hardware (Sunmi, Pax, iMin)</span>
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
           Section: Free Pricing & Google Login (Tema Terang Biru)
           ========================================================================== -->
        <section class="pricing-section" id="harga">
            <div class="container">
                <div class="section-header">
                    <span class="section-tag" data-i18n="pricing_tag">Free Access</span>
                    <h2 class="section-title" data-i18n="pricing_title">Start Today — 100% Free with Google</h2>
                    <p class="section-subtitle" data-i18n="pricing_subtitle">
                        No lengthy paperwork, no hidden costs, and zero credit card needed. Just one click with your Google account to launch your store POS immediately.
                    </p>
                </div>

                <div class="pricing-card-free">
                    <div class="free-ribbon" data-i18n="pricing_ribbon">FREE</div>
                    <div class="free-info">
                        <div class="free-price-tag" data-i18n="pricing_price">$0 / Free</div>
                        <div class="free-price-sub" data-i18n="pricing_sub">Free Forever for Stores &amp; SMB Merchants</div>

                        <ul class="tab-checklist" style="margin-bottom: 0;">
                            <li>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span data-i18n="pricing_item1">Full Access to Cashier &amp; Sales Transactions</span>
                            </li>
                            <li>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span data-i18n="pricing_item2">Product, Category &amp; SKU Variant Management</span>
                            </li>
                            <li>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span data-i18n="pricing_item3">Mobile REST API Access (React Native &amp; Flutter)</span>
                            </li>
                            <li>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span data-i18n="pricing_item4">Cashier Shifts &amp; Daily Cash Flow Reports</span>
                            </li>
                        </ul>
                    </div>

                    <div class="free-cta-box">
                        <a href="{{ route('auth.google') }}" class="btn-google-primary" style="width: 100%; justify-content: center;" id="pricing-cta-google">
                            <svg width="20" height="20" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                            <span data-i18n="btn_signup_google">Instant Sign-Up with Google</span>
                        </a>
                        <p data-i18n="pricing_note">
                            Instant 10-second setup. Jump straight into your store dashboard.
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
                    <span class="section-tag" data-i18n="faq_tag">Questions &amp; Answers</span>
                    <h2 class="section-title" data-i18n="faq_title">Frequently Asked Questions</h2>
                    <p class="section-subtitle" data-i18n="faq_subtitle">
                        Everything you need to know about free access and using this cloud POS platform.
                    </p>
                </div>

                <div class="faq-container">
                    <div class="faq-item">
                        <button class="faq-question">
                            <span data-i18n="faq_q1">Is it truly 100% free just by logging in with Google?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-inner" data-i18n="faq_a1">
                                Yes, absolutely! Just click "Sign in with Google". Our system automatically provisions your free store Tenant account complete with a primary outlet, active license, and store owner privileges without any monthly subscription fees.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span data-i18n="faq_q2">Does the mobile API support React Native and Flutter?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-inner" data-i18n="faq_a2">
                                Fully supported! This POS backend includes standard V1 REST endpoints specifically designed for modern mobile clients developed with React Native and Flutter, including built-in support for Android POS terminal hardware such as Sunmi, Pax, and iMin.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span data-i18n="faq_q3">What happens if the cashier's internet connection drops?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-inner" data-i18n="faq_a3">
                                Our REST API V1 architecture includes dedicated /api/v1/sync/pull and /api/v1/sync/push endpoints. Mobile clients store offline transaction queues locally and automatically reconcile them once connectivity is restored.
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span data-i18n="faq_q4">How does Recipe / Bill of Materials (BOM) deduction work?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-inner" data-i18n="faq_a4">
                                You can link BOM recipes to menu items. Whenever a cashier completes a sale for that product, the engine automatically calculates and deducts raw ingredient balances from your outlet warehouse in real time.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==========================================================================
           Bottom Final CTA (Vibrant Blue Theme)
           ========================================================================== -->
        <section class="cta-bottom-section">
            <div class="container">
                <h2 class="cta-bottom-title" data-i18n="bottom_cta_title">Ready to Supercharge Your Store Operations?</h2>
                <p class="cta-bottom-subtitle" data-i18n="bottom_cta_subtitle">
                    Join thousands of smart retailers today and enjoy the complete power of a modern cloud POS platform for free with a single Google sign-in.
                </p>
                <a href="{{ route('auth.google') }}" class="btn-google-primary" id="bottom-cta-google" style="background: #ffffff; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.25);">
                    <svg width="22" height="22" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                    <span data-i18n="btn_bottom_google">Sign in with Google (100% Free)</span>
                </a>
            </div>
        </section>
    </main>

    <!-- ==========================================================================
       Footer (Tema Terang)
       ========================================================================== -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-top">
                <div class="nav-brand">
                    <div class="brand-icon">🛍️</div>
                    <span>POS Aisyah Cloud</span>
                </div>
                <div class="footer-links">
                    <a href="#fitur" data-i18n="nav_features">Features</a>
                    <a href="#demo" data-i18n="nav_demo">Visual Demo</a>
                    <a href="#mobile" data-i18n="nav_mobile">Mobile API</a>
                    <a href="#harga" data-i18n="nav_pricing">Free Plan</a>
                    <a href="{{ route('login') }}" data-i18n="btn_login">Cashier Login</a>
                </div>
            </div>
            <div class="footer-bottom">
                <div data-i18n="footer_copy">&copy; {{ date('Y') }} POS Aisyah SaaS. Multi-Tenant Cloud POS &amp; Mobile Solution.</div>
                <div data-i18n="footer_sub">Designed for retail stores, restaurants, cafes, and multi-branch franchises.</div>
            </div>
        </div>
    </footer>

    <!-- Interactive Scripts for Tabs, FAQ Accordions, and Language Switching -->
    <script>
        // Internationalization Dictionary (English Default vs Bahasa Indonesia)
        var translations = {
            en: {
                meta_title: "Multi-Tenant SaaS POS - Cloud Point of Sale, Recipe BOM & Mobile API (Free)",
                nav_badge: "SaaS Cloud",
                nav_features: "Features",
                nav_demo: "Visual Demo",
                nav_mobile: "Mobile REST API",
                nav_pricing: "Free Plan",
                nav_faq: "FAQ",
                btn_login: "Cashier Login",
                btn_google_nav: "Sign in with Google",

                hero_pill_tag: "100% FREE",
                hero_pill_text: "Sign in with Google • Instant Setup with Zero Credit Card Required",
                hero_title: 'Modern Cloud <span class="gradient-text">Multi-Tenant POS</span> for Retail &amp; F&amp;B Businesses',
                hero_subtitle: "Streamline your multi-outlet sales operations with automated Recipe Bill of Materials (BOM), cashier cash drawer shifts, parked orders, and lightning-fast REST API ready for React Native and Flutter mobile POS apps.",
                btn_hero_google: "Start Free with Google",
                btn_explore_features: "Explore Features",
                trust_free: "Free Forever",
                trust_no_cc: "No Credit Card Needed",
                trust_instant: "Instant Google Onboarding",
                trust_mobile: "React Native &amp; Flutter Ready",

                mockup_stat_revenue_label: "Today's Shift Revenue",
                mockup_stat_revenue_val: "Rp 4.850.000",
                mockup_stat_bom_label: "Raw Ingredient BOM",
                mockup_stat_bom_val: "100% Auto-Deducted",

                stat_1_num: "$0 / Free",
                stat_1_lbl: "Zero Upfront Cost (Free via Google)",
                stat_2_num: "< 50ms",
                stat_2_lbl: "Mobile REST API Latency",
                stat_3_num: "2 Mobile SDKs",
                stat_3_lbl: "React Native &amp; Flutter Ready",
                stat_4_num: "99.9%",
                stat_4_lbl: "Offline Network Tolerance",

                features_tag: "Full Features",
                features_title: "All Your Retail &amp; F&amp;B Operations in One Unified App",
                features_subtitle: "Powered by enterprise-grade multi-tenant architecture to guarantee isolated tenant data, high-speed checkout performance, and effortless multi-branch scaling.",
                feat_1_title: "Multi-Tenant &amp; Multi-Outlet",
                feat_1_desc: "Manage headquarters and branch franchise outlets in one unified system. Each outlet has its own inventory stocks, localized price tiers, and cashier staff access.",
                feat_2_title: "Recipe &amp; Bill of Materials (BOM)",
                feat_2_desc: "Perfect for restaurants, coffee shops, and food retail. When an item like Iced Latte is sold, the system automatically calculates and deducts raw coffee beans, milk, and syrup from stock.",
                feat_3_title: "Mobile POS (React Native &amp; Flutter)",
                feat_3_desc: "Backed by our ultra-fast V1 REST API specifically designed for mobile cashier tablets, Android/iOS smartphones, and dedicated handheld POS devices (Sunmi, Pax, iMin).",
                feat_4_title: "Cashier Shifts &amp; Cash Drawer",
                feat_4_desc: "Opening float tracking, cash in/out recording, end-of-shift cash counting, and automated discrepancy audits to eliminate cashier drawer shortages.",
                feat_5_title: "Held Orders &amp; Multi-Payment",
                feat_5_desc: "Hold temporary table orders or queue carts and resume checkout anytime. Accept split-payments combining Cash, dynamic QRIS, and Credit/Debit Cards on a single receipt.",
                feat_6_title: "Supervisor PIN Authorization",
                feat_6_desc: "High-level operational security. Item voids, order refunds, and custom manager discounts require 6-digit Supervisor PIN verification with full audit logging.",

                demo_tag: "App Interface",
                demo_title: "Live Store Operational Screen Previews",
                demo_subtitle: "Clean, responsive, and intuitive interface. Empowering cashiers and store owners to operate faster with zero steep learning curve.",
                tab_nav_pos: "Cashier Terminal",
                tab_nav_restock: "Purchasing &amp; Restock",
                tab_nav_analytics: "Sales Analytics &amp; Charts",
                tab_nav_catalog: "Master Products &amp; BOM",

                tab_pos_heading: "Fast &amp; Responsive Cashier Terminal",
                tab_pos_desc: "Instant search by barcode scanning or product name. Apply automatic member discounts, tax calculations, and print thermal receipts in seconds.",
                tab_pos_item1: "Supports USB &amp; Bluetooth barcode scanners",
                tab_pos_item2: "Multi-payment: Cash, QRIS, Bank EDC",
                tab_pos_item3: "Cart parking to serve waiting customers in queue",
                btn_try_live: "Try Live (100% Free)",

                tab_restock_heading: "Restock &amp; Supplier Management",
                tab_restock_desc: "Record inbound merchandise and raw ingredients from suppliers. Stock levels update in real-time and cash disbursement ledgers are logged automatically.",
                tab_restock_item1: "Print Purchase Orders (PO) &amp; Goods Receipts",
                tab_restock_item2: "Supplier purchase price history for profit margin tracking",

                tab_analytics_heading: "Real-Time Sales &amp; Revenue Analytics",
                tab_analytics_desc: "Monitor monthly sales trends, best-selling menu items, and cashier performance with interactive charts to drive data-backed business growth.",
                tab_analytics_item1: "Automated gross profit &amp; cash flow reports",
                tab_analytics_item2: "Inventory balance cards tracking SKU ins and outs",

                tab_catalog_heading: "Master Products, Variants &amp; Recipe Formulas",
                tab_catalog_desc: "Organize thousands of product SKUs with multi-level categories, units (Pcs, Grams, Liters), variant pricing, and flexible BOM recipe formulas.",
                tab_catalog_item1: "Auto-generate EAN/Code128 barcodes",
                tab_catalog_item2: "Minimum stock warning before ingredients run dry",

                api_title: "Standardized REST API for Mobile POS Applications",
                api_desc: 'Connect your custom mobile POS apps built with <strong>React Native</strong> or <strong>Flutter</strong>. Our backend provides lightweight, secure RESTful endpoints equipped with an <em>Offline Sync Queue</em>.',
                api_item1: "Secure Bearer Token authentication",
                api_item2: "Offline synchronization: Cashiers stay operational during internet outages",
                api_item3: "Ready for Android POS hardware (Sunmi, Pax, iMin)",

                pricing_tag: "Free Access",
                pricing_title: "Start Today — 100% Free with Google",
                pricing_subtitle: "No lengthy paperwork, no hidden costs, and zero credit card needed. Just one click with your Google account to launch your store POS immediately.",
                pricing_ribbon: "FREE",
                pricing_price: "$0 / Free",
                pricing_sub: "Free Forever for Stores &amp; SMB Merchants",
                pricing_item1: "Full Access to Cashier &amp; Sales Transactions",
                pricing_item2: "Product, Category &amp; SKU Variant Management",
                pricing_item3: "Mobile REST API Access (React Native &amp; Flutter)",
                pricing_item4: "Cashier Shifts &amp; Daily Cash Flow Reports",
                btn_signup_google: "Instant Sign-Up with Google",
                pricing_note: "Instant 10-second setup. Jump straight into your store dashboard.",

                faq_tag: "Questions &amp; Answers",
                faq_title: "Frequently Asked Questions",
                faq_subtitle: "Everything you need to know about free access and using this cloud POS platform.",
                faq_q1: "Is it truly 100% free just by logging in with Google?",
                faq_a1: 'Yes, absolutely! Just click "Sign in with Google". Our system automatically provisions your free store Tenant account complete with a primary outlet, active license, and store owner privileges without any monthly subscription fees.',
                faq_q2: "Does the mobile API support React Native and Flutter?",
                faq_a2: "Fully supported! This POS backend includes standard V1 REST endpoints specifically designed for modern mobile clients developed with React Native and Flutter, including built-in support for Android POS terminal hardware such as Sunmi, Pax, and iMin.",
                faq_q3: "What happens if the cashier's internet connection drops?",
                faq_a3: "Our REST API V1 architecture includes dedicated /api/v1/sync/pull and /api/v1/sync/push endpoints. Mobile clients store offline transaction queues locally and automatically reconcile them once connectivity is restored.",
                faq_q4: "How does Recipe / Bill of Materials (BOM) deduction work?",
                faq_a4: "You can link BOM recipes to menu items. Whenever a cashier completes a sale for that product, the engine automatically calculates and deducts raw ingredient balances from your outlet warehouse in real time.",

                bottom_cta_title: "Ready to Supercharge Your Store Operations?",
                bottom_cta_subtitle: "Join thousands of smart retailers today and enjoy the complete power of a modern cloud POS platform for free with a single Google sign-in.",
                btn_bottom_google: "Sign in with Google (100% Free)",

                footer_copy: "&copy; " + new Date().getFullYear() + " POS Aisyah SaaS. Multi-Tenant Cloud POS &amp; Mobile Solution.",
                footer_sub: "Designed for retail stores, restaurants, cafes, and multi-branch franchises."
            },
            id: {
                meta_title: "SaaS POS Multi-Tenant - Aplikasi Kasir Cloud, Resep BOM & Mobile API (Gratis)",
                nav_badge: "SaaS Cloud",
                nav_features: "Fitur Unggulan",
                nav_demo: "Demo Visual",
                nav_mobile: "Mobile REST API",
                nav_pricing: "Paket Gratis",
                nav_faq: "FAQ",
                btn_login: "Login Kasir",
                btn_google_nav: "Masuk Google",

                hero_pill_tag: "100% GRATIS",
                hero_pill_text: "Cukup Login Akun Google • Langsung Aktif Tanpa Kartu Kredit",
                hero_title: 'Sistem Kasir Modern <span class="gradient-text">Multi-Tenant Cloud</span> untuk Bisnis Ritel &amp; F&amp;B',
                hero_subtitle: "Tingkatkan efisiensi operasional toko dan cabang Anda. Dilengkapi resep bahan baku otomatis (BOM), kontrol shift laci kasir, parkir pesanan, serta integrasi REST API V1 siap pakai untuk aplikasi kasir React Native dan Flutter.",
                btn_hero_google: "Mulai Gratis dengan Google",
                btn_explore_features: "Jelajahi Fitur",
                trust_free: "Gratis Selamanya",
                trust_no_cc: "Tanpa Kartu Kredit",
                trust_instant: "Aktivasi Instan via Google",
                trust_mobile: "React Native &amp; Flutter Ready",

                mockup_stat_revenue_label: "Total Omset Shift Ini",
                mockup_stat_revenue_val: "Rp 4.850.000",
                mockup_stat_bom_label: "Stok Bahan Baku (BOM)",
                mockup_stat_bom_val: "Otomatis Terpotong 100%",

                stat_1_num: "Rp 0",
                stat_1_lbl: "Biaya Awal (Gratis via Google)",
                stat_2_num: "< 50ms",
                stat_2_lbl: "Latensi REST API Mobile",
                stat_3_num: "2 Mobile SDK",
                stat_3_lbl: "React Native &amp; Flutter Ready",
                stat_4_num: "99.9%",
                stat_4_lbl: "Toleransi Offline Jaringan",

                features_tag: "Fitur Lengkap",
                features_title: "Semua Kebutuhan Bisnis Ritel &amp; F&amp;B dalam Satu Aplikasi",
                features_subtitle: "Didukung arsitektur multi-tenant berstandar enterprise untuk menjamin isolasi data toko yang aman, performa transaksi cepat, dan skalabilitas tanpa batas.",
                feat_1_title: "Multi-Tenant &amp; Multi-Cabang",
                feat_1_desc: "Kelola outlet pusat dan cabang-cabang waralaba dalam satu sistem terpadu. Tiap outlet memiliki data inventaris, daftar harga, dan akses staf kasir tersendiri.",
                feat_2_title: "Resep &amp; Bill of Materials (BOM)",
                feat_2_desc: "Sangat cocok untuk bisnis kuliner dan kafe. Setiap kali minuman atau makanan terjual di kasir, gramatur bahan baku di gudang otomatis terpotong secara akurat.",
                feat_3_title: "Mobile POS (React Native &amp; Flutter)",
                feat_3_desc: "Didukung REST API V1 ultra-cepat yang dirancang khusus untuk integrasi kasir tablet, smartphone Android/iOS, maupun mesin POS cerdas (Sunmi, Pax, iMin).",
                feat_4_title: "Manajemen Shift &amp; Laci Kasir",
                feat_4_desc: "Pencatatan kas modal awal, kas masuk/keluar, kas akhir saat pergantian shift, dan kalkulasi otomatis selisih kas untuk mencegah kebocoran uang kasir.",
                feat_5_title: "Parkir Pesanan &amp; Multi-Payment",
                feat_5_desc: "Tahan sementara pesanan meja atau antrean dan lanjutkan pembayaran nanti. Terima kombinasi pembayaran Tunai, QRIS dinamis, dan Kartu Debit/Kredit dalam satu struk.",
                feat_6_title: "Otorisasi PIN Supervisor",
                feat_6_desc: "Perlindungan transaksi tingkat tinggi. Pembatalan pesanan (void), diskon khusus, dan retur barang mewajibkan verifikasi 6-digit PIN Supervisor dengan audit log lengkap.",

                demo_tag: "Antarmuka Aplikasi",
                demo_title: "Pratinjau Layar Operasional Toko",
                demo_subtitle: "Antarmuka pengguna yang bersih, responsif, dan intuitif. Memudahkan kasir dan pemilik toko bekerja lebih cepat tanpa hambatan teknis.",
                tab_nav_pos: "Terminal Kasir",
                tab_nav_restock: "Pembelian &amp; Restock",
                tab_nav_analytics: "Analitik Grafik Penjualan",
                tab_nav_catalog: "Master Data Barang &amp; BOM",

                tab_pos_heading: "Layar Transaksi Kasir Responsif",
                tab_pos_desc: "Pencarian instan melalui scan barcode atau ketik nama produk. Hitung diskon member, kalkulasi pajak otomatis, dan cetak struk thermal dalam hitungan detik.",
                tab_pos_item1: "Mendukung barcode scanner USB &amp; Bluetooth",
                tab_pos_item2: "Multi-pembayaran: Tunai, QRIS, EDC Bank",
                tab_pos_item3: "Parkir keranjang belanja untuk melayani antrean lain",
                btn_try_live: "Coba Langsung (100% Gratis)",

                tab_restock_heading: "Pencatatan Restock &amp; Supplier",
                tab_restock_desc: "Catat transaksi masuk barang dagangan atau bahan baku mentah dari supplier. Stok gudang otomatis bertambah dan jurnal pengeluaran kas tercatat rapi.",
                tab_restock_item1: "Cetak Purchase Order (PO) &amp; Bukti Penerimaan Barang",
                tab_restock_item2: "Histori harga beli supplier untuk evaluasi margin laba",

                tab_analytics_heading: "Visualisasi Penjualan &amp; Laba Bersih",
                tab_analytics_desc: "Pantau tren omset penjualan bulanan, menu terlaris, dan kinerja kasir dalam bentuk grafik visual yang informatif untuk memudahkan pengambilan keputusan bisnis.",
                tab_analytics_item1: "Laporan laba kotor &amp; arus kas otomatis",
                tab_analytics_item2: "Kartu persediaan barang masuk dan keluar per SKU",

                tab_catalog_heading: "Master Produk, Varian &amp; Formula Resep",
                tab_catalog_desc: "Kelola ribuan SKU produk dengan kategori bertingkat, satuan unit (Pcs, Gram, Liter), varian harga, serta formula resep bahan baku yang fleksibel.",
                tab_catalog_item1: "Auto-generate barcode format EAN/Code128",
                tab_catalog_item2: "Peringatan stok minimum sebelum bahan baku habis",

                api_title: "REST API Terstandarisasi untuk Aplikasi Mobile POS",
                api_desc: 'Hubungkan aplikasi kasir mobile buatan Anda menggunakan <strong>React Native</strong> atau <strong>Flutter</strong>. Backend kami menyediakan endpoint RESTful yang ringan, aman, dan dilengkapi mekanisme <em>Offline Sync Queue</em>.',
                api_item1: "Autentikasi aman berbasis Bearer Token",
                api_item2: "Sinkronisasi offline: Kasir tetap aktif saat internet terputus",
                api_item3: "Siap digunakan di POS hardware Android (Sunmi, Pax, iMin)",

                pricing_tag: "Akses Gratis",
                pricing_title: "Mulai Sekarang — 100% Gratis dengan Google",
                pricing_subtitle: "Tanpa formulir panjang, tanpa biaya tersembunyi, dan tanpa kartu kredit. Cukup satu klik akun Google untuk mengaktifkan sistem POS toko Anda seketika.",
                pricing_ribbon: "GRATIS",
                pricing_price: "Rp 0",
                pricing_sub: "Gratis Selamanya untuk Toko &amp; Pelaku UMKM",
                pricing_item1: "Akses Penuh Fitur Kasir &amp; Transaksi Penjualan",
                pricing_item2: "Manajemen Produk, Kategori &amp; Varian SKU",
                pricing_item3: "Akses REST API Mobile (React Native &amp; Flutter)",
                pricing_item4: "Manajemen Shift Kasir &amp; Laporan Arus Kas",
                btn_signup_google: "Daftar Cepat via Google",
                pricing_note: "Setup otomatis instan dalam 10 detik. Langsung masuk ke dashboard manajemen toko Anda.",

                faq_tag: "Tanya Jawab",
                faq_title: "Frequently Asked Questions",
                faq_subtitle: "Segala hal yang perlu Anda ketahui mengenai akses gratis dan penggunaan sistem POS ini.",
                faq_q1: "Apakah benar-benar gratis hanya dengan login Google?",
                faq_a1: 'Ya, 100% benar! Anda cukup mengklik tombol "Masuk dengan Google". Sistem kami otomatis membuatkan akun Tenant toko gratis Anda lengkap dengan outlet utama, lisensi aktif, dan hak akses pemilik tanpa biaya langganan bulanan.',
                faq_q2: "Apakah aplikasi mobile mendukung React Native dan Flutter?",
                faq_a2: "Sangat mendukung! Backend POS ini dilengkapi dengan REST API V1 standar yang dirancang khusus untuk klien mobile modern, baik yang dikembangkan dengan React Native maupun Flutter, termasuk dukungan perangkat hardware POS Android seperti Sunmi dan Pax.",
                faq_q3: "Bagaimana jika koneksi internet kasir tiba-tiba terputus?",
                faq_a3: "Arsitektur REST API V1 kami memiliki endpoint /api/v1/sync/pull dan /api/v1/sync/push. Klien mobile dapat menyimpan antrean transaksi di penyimpanan lokal dan mengunggahnya secara otomatis saat koneksi internet kembali normal.",
                faq_q4: "Bagaimana cara kerja pengurangan stok resep (BOM)?",
                faq_a4: "Anda dapat menautkan resep (Bill of Materials) pada menu makanan/minuman. Setiap kali kasir menyelesaikan transaksi untuk produk tersebut, sistem secara otomatis menghitung dan memotong stok bahan baku mentah di gudang outlet secara akurat.",

                bottom_cta_title: "Siap Memajukan Operasional Toko Anda?",
                bottom_cta_subtitle: "Bergabung sekarang dan nikmati seluruh kemudahan platform POS modern secara cuma-cuma hanya dengan satu klik akun Google Anda.",
                btn_bottom_google: "Masuk dengan Google (100% Gratis)",

                footer_copy: "&copy; " + new Date().getFullYear() + " POS Aisyah SaaS. Solusi Kasir Multi-Tenant &amp; Mobile POS Modern.",
                footer_sub: "Dirancang untuk efisiensi ritel, resto, kafe, dan franchise."
            }
        };

        function setLanguage(lang) {
            if (!translations[lang]) lang = 'en';

            // 1. Update HTML lang attribute
            document.documentElement.lang = lang;

            // 2. Update document title
            if (translations[lang].meta_title) {
                document.title = translations[lang].meta_title;
            }

            // 3. Update all elements with data-i18n
            var i18nElements = document.querySelectorAll('[data-i18n]');
            i18nElements.forEach(function(el) {
                var key = el.getAttribute('data-i18n');
                if (translations[lang][key]) {
                    el.innerHTML = translations[lang][key];
                }
            });

            // 4. Update switcher buttons active state
            var langBtns = document.querySelectorAll('.lang-btn');
            langBtns.forEach(function(btn) {
                if (btn.getAttribute('data-lang') === lang) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            // 5. Persist preference in localStorage
            try {
                localStorage.setItem('pos_landing_lang', lang);
            } catch (e) {}
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Determine initial language: localStorage -> URL param -> default 'en'
            var urlParams = new URLSearchParams(window.location.search);
            var langParam = urlParams.get('lang');
            var savedLang = null;
            try {
                savedLang = localStorage.getItem('pos_landing_lang');
            } catch (e) {}

            var initialLang = langParam || savedLang || 'en';
            setLanguage(initialLang);

            // Bind click events on language switcher buttons
            var langBtns = document.querySelectorAll('.lang-btn');
            langBtns.forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    var selectedLang = this.getAttribute('data-lang');
                    setLanguage(selectedLang);
                });
            });

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
