# DIGIPUS — Digital Library & Pustaka Digitech University
## Prompt Pengembangan Platform (Full Spec Document)

---

## 1. RINGKASAN PROYEK

**Nama Platform:** DIGIPUS (Digital Library & Pustaka Digitech University)
**Tujuan:** Membangun platform perpustakaan digital terintegrasi untuk Digitech University yang mengelola koleksi buku fisik, ebook, jurnal, dan repository karya ilmiah, dengan pengalaman pengguna yang mulus di desktop maupun mobile (PWA, terasa seperti aplikasi native).

**Karakteristik Utama:**
- Multi-role dashboard (Admin, Pustakawan/Staff, Dosen, Mahasiswa, Tamu/Publik)
- Peminjaman buku fisik + baca/pinjam ebook digital
- Skalabel untuk diakses ribuan user bersamaan (concurrent access)
- SEO kuat untuk katalog publik (agar bisa diindeks Google — riset akademik, katalog buku)
- PWA installable, offline-first untuk fitur tertentu
- Desain **Claymorphism** dengan warna dominan **Merah – Biru – Putih**

---

## 2. TECH STACK

| Layer | Teknologi |
|---|---|
| Backend | Laravel 11.x (PHP 8.3+) |
| Frontend | Vue.js 3 (Composition API) + Inertia.js *atau* Vue SPA + Laravel API (REST) |
| State Management | Pinia |
| Database | MariaDB 10.11+ |
| Cache/Queue | Redis (cache, session, queue driver) |
| Search Engine | Meilisearch / Laravel Scout (untuk pencarian katalog cepat & relevan) |
| Realtime | Laravel Reverb / WebSocket (notifikasi, status pinjaman) |
| Storage | Laravel Filesystem (S3-compatible / local) untuk file ebook (PDF/EPUB) |
| PWA | Vite PWA Plugin (Workbox) — service worker, manifest.json, offline caching |
| Styling | Tailwind CSS (custom claymorphism utility layer) |
| Build Tool | Vite |
| Testing | Pest (backend), Vitest + Cypress/Playwright (frontend/E2E) |
| CI/CD | GitHub Actions / GitLab CI |
| Monitoring | Laravel Telescope (dev), Sentry (error tracking), Laravel Pulse (performance) |
| Load Balancing | Nginx + PHP-FPM (multi-worker), Horizon untuk queue worker scaling |
| E-Reader Engine | PDF.js (untuk baca PDF di browser) & EPUB.js (untuk EPUB) dengan DRM-lite (watermark, batas device) |

---

## 3. DESAIN & UI/UX

### 3.1 Gaya Visual: Claymorphism
- Elemen UI menyerupai "tanah liat" — permukaan lembut, membulat (border-radius besar 20–32px), dengan **soft shadow ganda** (outer shadow gelap + inner highlight terang) untuk efek 3D empuk.
- Hindari flat design biasa; setiap card, button, input harus punya depth ringan tapi tidak berlebihan (bukan neumorphism tajam, bukan skeuomorphism realistis).
- Micro-interaction: tombol "menekan" (press-in effect) saat diklik/tap.

### 3.2 Skema Warna
- **Merah** (primary accent — CTA, notifikasi penting, status "terlambat/overdue"): contoh `#D92B3E` / `#C81E3C`
- **Biru** (secondary — branding utama, header, link, status "aktif/dipinjam"): contoh `#1E4FA3` / `#2563EB`
- **Putih/Netral** (background, card base): `#FFFFFF`, `#F5F6FA`, abu lembut untuk shadow claymorphism
- Gradient lembut merah-biru bisa dipakai di hero section/landing page
- Pastikan kontras warna memenuhi standar aksesibilitas WCAG AA

### 3.3 Tipografi
- Font modern, mudah dibaca: contoh **Poppins** / **Plus Jakarta Sans** untuk heading, **Inter** untuk body text
- Hierarki jelas: H1–H6, badge, caption

### 3.4 Layout Responsif
- **Desktop:** layout multi-kolom, sidebar dashboard, data table dengan filter lanjutan
- **Mobile:** 
  - Bottom navigation bar (seperti app native: Home, Katalog, Pinjaman, Rak/Bookshelf, Profil)
  - Gesture-friendly (swipe untuk aksi cepat, pull-to-refresh)
  - Card-based scroll vertikal, bukan tabel padat
  - Safe-area aware (notch, gesture bar)
- Breakpoint: mobile (<768px), tablet (768–1024px), desktop (>1024px)
- Dark mode opsional (ikut skema clay tapi versi gelap)

### 3.5 PWA
- `manifest.json` dengan ikon adaptif, theme-color merah/biru
- Service worker: cache-first untuk aset statis, network-first untuk data dinamis
- Fitur offline: bisa lihat riwayat pinjaman & buku yang sudah diunduh untuk dibaca offline (ebook yang diizinkan)
- Install prompt custom (Add to Home Screen) dengan UI clay
- Push notification (jatuh tempo pinjaman, buku tersedia kembali, pengumuman)

---

## 4. ARSITEKTUR ROLE & DASHBOARD

### 4.1 Super Admin
- Manajemen seluruh user & role
- Manajemen master data (kategori, rak, lokasi cabang perpustakaan)
- Pengaturan sistem (kebijakan denda, durasi pinjam, kuota pinjam per role)
- Laporan & analitik global (statistik peminjaman, buku terpopuler, tren kunjungan)
- Manajemen konten SEO (meta, sitemap, slug katalog)
- Audit log aktivitas sistem

### 4.2 Pustakawan / Staff Perpustakaan
- Manajemen koleksi (tambah/edit/hapus buku fisik & ebook, upload file ebook, scan ISBN)
- Manajemen stok & kondisi buku fisik per eksemplar (barcode/RFID ready)
- Proses sirkulasi: peminjaman, pengembalian, perpanjangan, reservasi (booking antre)
- Manajemen denda & pembayaran
- Approval permintaan pengadaan buku baru dari user
- Laporan sirkulasi harian/bulanan

### 4.3 Dosen
- Dashboard koleksi rekomendasi mata kuliah (reading list per course)
- Akses ebook & jurnal penuh tanpa batas kuota (atau kuota lebih besar)
- Unggah referensi/bahan ajar ke repository (jika diizinkan)
- Riwayat & statistik peminjaman pribadi

### 4.4 Mahasiswa
- Pencarian & jelajah katalog (buku fisik + ebook)
- Peminjaman buku fisik (booking, antre reservasi jika stok habis)
- Baca ebook langsung (reader online) + unduh terbatas (offline reading dengan expiry)
- Rak virtual / bookshelf pribadi, riwayat pinjam, wishlist
- Notifikasi jatuh tempo & denda
- Ulasan & rating buku
- Riwayat denda & pembayaran online (integrasi payment gateway)

### 4.5 Tamu / Publik (Guest, tanpa login)
- Bisa browsing katalog publik (untuk SEO & promosi), tidak bisa pinjam
- CTA daftar/login untuk akses penuh

---

## 5. FITUR LENGKAP

### 5.1 Manajemen Katalog
- CRUD buku (judul, penulis, penerbit, ISBN, kategori, tahun, sinopsis, cover)
- Dukung multi-format ebook: PDF, EPUB
- Multi-eksemplar per judul (tracking per copy/barcode)
- Kategori & tag, filter lanjutan (genre, bahasa, tahun, ketersediaan)
- Related books / rekomendasi otomatis (based on kategori/riwayat)

### 5.2 Sirkulasi & Peminjaman
- Peminjaman buku fisik: scan barcode/QR, self-checkout kiosk mode opsional
- Reservasi/antre jika buku dipinjam orang lain
- Perpanjangan pinjaman (online, dengan batas maksimal perpanjangan)
- Pengembalian otomatis tercatat + notifikasi jatuh tempo (H-3, H-1, hari-H)
- Sistem denda otomatis (per hari keterlambatan) + histori pembayaran
- Baca ebook online (in-browser reader, tanpa unduh, proteksi copy-paste dasar)
- Unduh ebook offline dengan watermark & expiry (lisensi digital sederhana)
- Kuota pinjam berbeda per role (mahasiswa vs dosen vs staff)

### 5.3 Pencarian & SEO
- Full-text search cepat (Meilisearch/Scout) dengan autocomplete & typo-tolerance
- Filter faceted search (kategori, penulis, tahun, format, ketersediaan)
- URL SEO-friendly (`/katalog/judul-buku-penulis`)
- Server-side rendering / SSR untuk halaman katalog publik (Inertia SSR atau prerender) agar terindeks Google
- Sitemap.xml otomatis, structured data (schema.org `Book`), meta tag dinamis, Open Graph untuk share sosial
- Optimasi Core Web Vitals (lazy load gambar, image webp, caching agresif untuk halaman publik)

### 5.4 Interaksi & Engagement
- Rating & review buku
- Wishlist / rak favorit
- Rekomendasi personalisasi (berdasarkan histori pinjam)
- Forum diskusi buku (opsional)
- Badge/gamifikasi (rajin membaca, dsb — opsional)

### 5.5 Notifikasi
- In-app notification center
- Email notifikasi (jatuh tempo, buku tersedia, pengumuman)
- Push notification via PWA (service worker)

### 5.6 Laporan & Analitik
- Dashboard statistik: buku terpopuler, kategori favorit, tren peminjaman per waktu
- Export laporan (Excel/PDF)
- Laporan inventaris & kondisi buku fisik

### 5.7 Integrasi
- SSO/Single Sign-On dengan sistem akademik kampus (SIAKAD) — opsional
- Payment gateway untuk denda (Midtrans/Xendit)
- Barcode/QR scanner (kamera mobile) untuk checkout mandiri
- API terbuka (untuk integrasi OPAC/aplikasi lain)

### 5.8 Keamanan & Skalabilitas
- Autentikasi: Laravel Sanctum/Fortify, 2FA opsional
- RBAC (Role-Based Access Control) granular via Spatie Laravel-Permission
- Rate limiting API, protection dari brute force
- Caching berlapis (Redis) untuk katalog & query berat
- Queue (Horizon) untuk proses berat (generate laporan, kirim notifikasi massal, indexing search)
- Database read-replica ready (untuk skala besar), indexing tabel optimal
- Load testing target: ribuan concurrent user (gunakan tools seperti k6/JMeter saat QA)
- CDN untuk aset statis & cover buku

---

## 6. STRUKTUR DATABASE (Gambaran Utama Tabel)

- `users`, `roles`, `permissions` (Spatie)
- `books`, `book_copies` (eksemplar fisik), `book_categories`, `authors`, `publishers`
- `ebooks` (file, format, lisensi, durasi unduh)
- `loans` (peminjaman fisik), `loan_reservations` (antre), `ebook_borrows` (peminjaman digital)
- `fines` (denda), `payments`
- `reviews`, `ratings`, `wishlists`
- `notifications`
- `library_branches`, `shelves` (rak)
- `audit_logs`
- `settings` (kebijakan sistem: durasi pinjam, denda per hari, kuota, dll — configurable oleh Admin)

---

## 7. CHECKLIST PENGEMBANGAN PER FASE

### ✅ FASE 0 — Persiapan & Perencanaan
- [ ] Finalisasi requirement & user stories per role
- [ ] Wireframe low-fidelity (desktop & mobile)
- [ ] Desain sistem clay (design system: warna, komponen, shadow, radius)
- [ ] Setup repo (Laravel + Vue), struktur folder, coding standard
- [ ] Setup environment dev (Docker/Laravel Sail, MariaDB, Redis)
- [ ] Rancang ERD & skema database final

### ✅ FASE 1 — Fondasi Backend & Auth
- [ ] Setup Laravel project + konfigurasi MariaDB
- [ ] Implementasi autentikasi (Sanctum/Fortify) + 2FA opsional
- [ ] Implementasi RBAC (Spatie Permission) untuk 5 role
- [ ] Setup Redis untuk cache & session
- [ ] Setup queue & Horizon
- [ ] Migration & seeder awal (data dummy buku, user, kategori)

### ✅ FASE 2 — Manajemen Katalog (Core)
- [ ] CRUD buku fisik (judul, penulis, ISBN, kategori, cover, dsb)
- [ ] Manajemen multi-eksemplar per buku (barcode/kode unik)
- [ ] CRUD ebook (upload PDF/EPUB, metadata, lisensi)
- [ ] Integrasi Meilisearch/Scout untuk pencarian
- [ ] Halaman katalog publik (SSR/SEO-friendly)
- [ ] Filter & faceted search

### ✅ FASE 3 — Sirkulasi & Peminjaman
- [ ] Modul peminjaman buku fisik (checkout, return, extend)
- [ ] Modul reservasi/antre
- [ ] Modul denda otomatis + histori pembayaran
- [ ] Modul baca ebook online (in-browser reader PDF.js/EPUB.js)
- [ ] Modul unduh ebook offline dengan proteksi (watermark/expiry)
- [ ] Notifikasi jatuh tempo (email + in-app)

### ✅ FASE 4 — Dashboard per Role
- [ ] Dashboard Super Admin (analitik global, pengaturan sistem)
- [ ] Dashboard Pustakawan (sirkulasi harian, manajemen stok)
- [ ] Dashboard Dosen (reading list, koleksi rekomendasi)
- [ ] Dashboard Mahasiswa (rak pribadi, riwayat, wishlist)
- [ ] Landing page publik untuk Tamu (SEO-optimized)

### ✅ FASE 5 — Frontend UI/UX (Claymorphism + Responsif)
- [ ] Bangun design system komponen (button, card, input, modal — clay style)
- [ ] Implementasi layout desktop (sidebar dashboard, data table)
- [ ] Implementasi layout mobile (bottom nav, card scroll, gesture)
- [ ] Dark mode (opsional)
- [ ] Uji aksesibilitas & kontras warna

### ✅ FASE 6 — PWA & Performa
- [ ] Setup manifest.json & service worker (Vite PWA Plugin)
- [ ] Implementasi offline mode (cache katalog, riwayat, ebook terunduh)
- [ ] Push notification
- [ ] Optimasi Core Web Vitals (lazy load, image optimization, caching)
- [ ] Uji install-ability PWA di Android/iOS

### ✅ FASE 7 — Integrasi & Fitur Tambahan
- [ ] Integrasi payment gateway (denda)
- [ ] Integrasi SSO/SIAKAD (jika ada)
- [ ] Barcode/QR scanner mobile untuk checkout mandiri
- [ ] Rating, review, forum diskusi (opsional)
- [ ] API publik terdokumentasi (Swagger/OpenAPI)

### ✅ FASE 8 — SEO & Konten
- [ ] Sitemap.xml otomatis & robots.txt
- [ ] Structured data (schema.org Book)
- [ ] Meta tag dinamis per halaman buku
- [ ] Open Graph & Twitter Card untuk share sosial
- [ ] Audit SEO (Lighthouse, Google Search Console)

### ✅ FASE 9 — Testing & QA
- [ ] Unit test backend (Pest)
- [ ] Unit/component test frontend (Vitest)
- [ ] E2E testing (Cypress/Playwright) alur utama (pinjam, kembalikan, baca ebook)
- [ ] Load testing (k6/JMeter) untuk concurrent user
- [ ] Security testing (OWASP checklist, penetration test dasar)
- [ ] UAT (User Acceptance Test) dengan perwakilan tiap role

### ✅ FASE 10 — Deployment & Monitoring
- [ ] Setup CI/CD pipeline
- [ ] Setup server produksi (Nginx, PHP-FPM, MariaDB cluster/replica)
- [ ] Setup CDN untuk aset statis
- [ ] Setup monitoring (Sentry, Laravel Pulse, uptime monitor)
- [ ] Backup otomatis database & file ebook
- [ ] Dokumentasi teknis & manual pengguna per role
- [ ] Soft launch & monitoring pasca-rilis

---

## 8. CATATAN TAMBAHAN
- Pastikan kebijakan DRM ebook proporsional — jangan terlalu restriktif hingga mengganggu UX, tapi cukup untuk melindungi hak cipta konten institusi.
- Pertimbangkan skalabilitas horizontal (multi-server) sejak awal arsitektur bila target user sangat besar (>10.000 concurrent).
- Selalu uji claymorphism style di berbagai device agar shadow tidak terlalu berat dan mempengaruhi performa render, terutama di mobile low-end.