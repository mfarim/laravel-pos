# Implementation Plan of Entrance Loading Animation ("PA POS AISYAH" with FLIP Coordinate Snapping)

Menerapkan Entrance Preloader Animation yang elegan, performant, dan seamless pada landing page `laravel-pos` menggunakan monogram geometris **"PA"** dan tipografi bertahap **"POS AISYAH"** dengan docking presisi ke elemen Navbar Header via prinsip **FLIP (First, Last, Invert, Play)**.

## User Review Required
> [!NOTE]
> Animasi ini dirancang menggunakan CSS modern, SVG stroke dashoffset, dan JavaScript vanilla murni. Preloader hanya berjalan pada kunjungan pertama sesi browser (`sessionStorage.getItem('app_has_visited')`), dengan skrip inline anti-FOUC (Zero-FOUC) di `<head>` dan fitur *instant skip* (klik di mana saja / tombol ESC).

## Key Architecture & Timeline Orchestration

```
[Kunjungan Pertama]
       │
       ▼
1. Zero-FOUC Guard (<head> synchronous check)
   • Cek sessionStorage('app_has_visited')
   • Jika ada, tambahkan class .has-visited ke <html> agar CSS langsung menyembunyikan overlay (0ms)
       │
       ▼
2. Fase 1: Intro (0.0s – 1.0s)
   • Monogram SVG "PA" digambar di tengah layar via stroke-dasharray & stroke-dashoffset
       │
       ▼
3. Fase 2: Reveal (1.0s – 1.8s)
   • Teks brand "POS AISYAH" muncul di samping monogram secara staggered (huruf per huruf)
       │
       ▼
4. Fase 3: Glow & Stabilization (1.8s – 2.15s)
   • Transisi background icon badge ke gradien biru (#2563eb -> #1d4ed8) dan ambient glow
       │
       ▼
5. Fase 4: Fly to Navbar (2.15s – 2.85s)
   • getBoundingClientRect() mengukur stage center vs .navbar-brand-logo:
     deltaX = navRect.left - stageRect.left
     deltaY = navRect.top - stageRect.top
     targetScale = navRect.height / stageRect.height
   • CSS transform: translate3d(deltaX, deltaY, 0) scale(targetScale) durasi 700ms cubic-bezier(0.16, 1, 0.3, 1)
   • Overlay memudar dari solid putih ke transparan
       │
       ▼
6. Fase 5: Handoff Sempurna (2.85s)
   • Unmount overlay preloader, hapus .preloader-active dari <body>
   • Logo navbar (.navbar-brand-logo) muncul halus di posisi persis pendaratan (opacity: 1)
   • Set sessionStorage('app_has_visited', 'true')
```

---

## Proposed Changes

### 1. Styling & Animations (`public/css/landing.css`)
- Tambahkan CSS state classes:
  - `body.preloader-active`: cegah scroll (`overflow: hidden`) dan sembunyikan navbar logo (`opacity: 0 !important; visibility: hidden !important;`).
  - `html.has-visited .splash-overlay { display: none !important; }`.
  - `html.has-visited body.preloader-active .navbar-brand-logo { opacity: 1 !important; visibility: visible !important; }`.
- Style `.navbar-brand-logo` & `.pa-brand-icon`: monogram SVG PA dengan gradien biru dan tipografi "POS AISYAH".
- Style `.splash-overlay`, `.splash-stage`, `.splash-brand-icon`, dan `.splash-brand-text`.
- SVG stroke keyframe animations (`drawStroke`, `stroke-dasharray`, `stroke-dashoffset`).
- Staggered character reveal styles (`--char-index`).
- Instant skip pill styles di bagian bawah preloader.

### 2. Template Blade & Markup (`resources/views/landing.blade.php`)
- Tambahkan inline Zero-FOUC guard `<script>` di `<head>`.
- Ganti shopping bag emoji `🛍️` pada navbar dengan `.navbar-brand-logo` (PA Monogram SVG + "POS AISYAH").
- Tambahkan markup `.splash-overlay` dengan `.splash-stage`, monogram SVG, dan huruf-huruf "POS AISYAH".
- Tambahkan orkestrator JavaScript preloader (Fase 1 s/d Fase 5, FLIP coordinate calculation, event listener klik & Escape key).
- Update footer brand icon agar seragam menggunakan PA monogram.

### 3. Server Preview Sync (`public/index.html`)
- Sinkronisasikan perubahan dari `landing.blade.php` ke `public/index.html` untuk dev server preview di `http://127.0.0.1:8181/`.

---

## Verification Plan

### Automated / Terminal Checks
- Validasi sintaks CSS di `public/css/landing.css`.
- Periksa HTTP 200 response dari `http://127.0.0.1:8181/`.

### Visual & Interactive Browser Verification
- Buka browser subagent di `http://127.0.0.1:8181/` (fresh session).
- Rekam dan verifikasi:
  1. Fase 1: Monogram "PA" tergambar via SVG stroke.
  2. Fase 2: Teks "POS AISYAH" muncul staggered.
  3. Fase 3: Ambient glow dan stabilisasi warna brand.
  4. Fase 4: Meluncur mulus ke posisi `.navbar-brand-logo`.
  5. Fase 5: Handoff ke navbar tanpa kedipan/flicker.
  6. Reload halaman: pastikan preloader langsung dilewati (Zero-FOUC bypass) karena `sessionStorage('app_has_visited')` sudah bernilai `true`.
  7. Uji tombol ESC / klik untuk instant skip.
