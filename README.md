# 📚 DIGIPUS — Digital Library & Pustaka Digitech University

<p align="center">
  <img src="https://raw.githubusercontent.com/lucide-icons/lucide/main/icons/library.svg" width="80" height="80" alt="DIGIPUS Logo" />
</p>

<p align="center">
  <strong>Platform Perpustakaan Digital Modern Digitech University dengan Antarmuka Claymorphism, Sirkulasi Hybrid (Buku Fisik & E-Book), Multi-Role RBAC, dan Dukungan PWA Offline-First.</strong>
</p>

<p align="center">
  <a href="#-teknologi--stack"><img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 13" /></a>
  <a href="#-teknologi--stack"><img src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.3+" /></a>
  <a href="#-teknologi--stack"><img src="https://img.shields.io/badge/Vue.js-3.5-4FC08D?style=flat-square&logo=vuedotjs&logoColor=white" alt="Vue 3" /></a>
  <a href="#-teknologi--stack"><img src="https://img.shields.io/badge/Tailwind_CSS-v4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white" alt="Tailwind CSS v4" /></a>
  <a href="#-teknologi--stack"><img src="https://img.shields.io/badge/Vite-8.x-646CFF?style=flat-square&logo=vite&logoColor=white" alt="Vite 8" /></a>
  <a href="#-teknologi--stack"><img src="https://img.shields.io/badge/Pinia-4.x-FFE56C?style=flat-square&logo=pinia&logoColor=black" alt="Pinia" /></a>
  <a href="#-arsitektur-multi-role-rbac"><img src="https://img.shields.io/badge/RBAC-Spatie_Permission-1E4FA3?style=flat-square" alt="Spatie Permission" /></a>
  <a href="#-pwa--mode-offline"><img src="https://img.shields.io/badge/PWA-Ready-D92B3E?style=flat-square&logo=pwa&logoColor=white" alt="PWA Ready" /></a>
  <a href="#-lisensi"><img src="https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square" alt="License MIT" /></a>
</p>

---

## 📌 Daftar Isi

1. [🌟 Ringkasan Proyek](#-ringkasan-proyek)
2. [🎨 Filosofi Desain Claymorphism](#-filosofi-desain-claymorphism)
3. [⚡ Fitur-Fitur Unggulan](#-fitur-fitur-unggulan)
4. [👥 Arsitektur Multi-Role (RBAC)](#-arsitektur-multi-role-rbac)
5. [🛠️ Teknologi & Stack](#️-teknologi--stack)
6. [📁 Struktur Direktori](#-struktur-direktori)
7. [🚀 Panduan Instalasi & Penggunaan](#-panduan-instalasi--penggunaan)
8. [🔑 Akun Demo Bawaan](#-akun-demo-bawaan)
9. [🔌 Dokumentasi REST API](#-dokumentasi-rest-api)
10. [📱 PWA & Mode Offline](#-pwa--mode-offline)
11. [📈 Roadmap & Status Pengembangan](#-roadmap--status-pengembangan)
12. [🤝 Kontribusi & Lisensi](#-kontribusi--lisensi)

---

## 🌟 Ringkasan Proyek

**DIGIPUS** (*Digital Library & Pustaka Digitech University*) adalah platform perpustakaan terpadu yang dirancang khusus untuk memenuhi kebutuhan sivitas akademika perguruan tinggi modern. Platform ini mengintegrasikan sirkulasi **buku fisik** berbasis eksemplar/barcode dan **bacaan digital (e-book PDF & EPUB)** dengan pengalaman membaca langsung di browser *(in-browser reader)*.

### Masalah yang Diselesaikan
- **Fragmentasi Koleksi:** Menggabungkan katalog koleksi fisik perpustakaan kampus dengan repositori e-book akademik dalam satu wadah pencarian.
- **Keterbatasan Fisik:** Pengguna dapat membaca e-book secara fleksibel di laptop maupun smartphone tanpa harus datang ke loket fisik perpustakaan.
- **Transparansi Sirkulasi:** Memudahkan mahasiswa dan dosen memantau masa aktif peminjaman, batas pengembalian, perpanjangan mandiri secara daring, hingga riwayat denda.
- **Aksesibilitas & Tampilan Kaku:** Menghadirkan antarmuka berbasis **Claymorphism** yang ramah, taktil, dan modern, menjauhkan kesan perpustakaan konvensional yang kaku.

---

## 🎨 Filosofi Desain Claymorphism

DIGIPUS mengadopsi prinsip desain **Claymorphism** yang dikombinasikan dengan identitas visual khas Digitech University:

* **Sentuhan 3D yang Lembut & Taktil:** Setiap kartu dan kontainer memiliki sudut membulat lebar (`rounded-2xl` hingga `rounded-3xl`), bayangan ganda (*dual soft shadows* luar dan dalam) yang memberikan kesan empuk seperti tanah liat digital.
* **Micro-Interaction Responsif:** Tombol memiliki efek tekanan fisik (*press-in scale & inner shadow shift*) saat diklik atau ditekan di layar sentuh.
* **Palet Warna Identitas:**
  * 🔴 **Digitech Red (`#D92B3E`):** Aksen primer, tombol tindakan utama (*CTA*), peringatan jatuh tempo, dan sorotan kategori.
  * 🔵 **Digitech Blue (`#1E4FA3`):** Warna institusi sekunder, navbar, tautan aktif, judul, dan status sirkulasi buku.
  * ⚪ **Latar Lembut & Netral (`#F4F6FB` / `#FFFFFF`):** Kanvas bersih yang menonjolkan sampul buku dan kedalaman bayangan clay.
* **Lab Komponen:** Tersedia halaman rute khusus `/clay-playground` untuk menguji token dan variasi komponen secara interaktif.

---

## ⚡ Fitur-Fitur Unggulan

### 1. 📖 Sirkulasi Ganda (Hybrid Circulation)
* **Peminjaman Buku Fisik:** Alokasi kode eksemplar unik (contoh: `A-12-001`), batas pinjam standar 14 hari, dan pengurangan stok fisik otomatis.
* **Perpanjangan Mandiri:** Fitur perpanjang pinjaman secara daring (+7 hari) dengan batas maksimal 2 kali perpanjangan selama tidak ada antrean.
* **Pengembalian Terintegrasi:** Rekap pengembalian buku dan pemulihan kuota pengguna seketika.

### 2. 📑 In-Browser E-Book Reader (PDF & EPUB)
* **Tanpa Aplikasi Tambahan:** Membaca konten e-book langsung di dalam modal peramban.
* **Pengaturan Tampilan Fleksibel:** Pengaturan ukuran font (A- / A+), tema baca (Normal, Sepia yang ramah mata, dan Dark Mode), serta bookmark progres halaman.
* **Perlindungan Konten Sederhana (DRM-Lite):** Watermark kepemilikan institusi dan pencegahan salin-tempel langsung.

### 3. 🔍 Pencarian Katalog & Filter Berjenjang (Faceted Search)
* **Pencarian Cepat:** Pencarian berdasarkan judul, nama penulis, nomor ISBN, atau kata kunci sinopsis.
* **Filter Multi-Kategori:**
  * 💻 *Teknologi Informasi & AI*
  * 📈 *Bisnis Digital & FinTech*
  * 🎨 *Desain Komunikasi Visual & UI/UX*
  * 📊 *Sains Data & Analitika*
  * 🛡️ *Keamanan Siber & Jaringan*
  * 🎓 *Karya Ilmiah & Jurnal Kampus*
* **Filter Format & Ketersediaan:** Penyaringan instan untuk buku fisik, e-book PDF, e-book EPUB, serta opsi *hanya tampilkan yang stoknya tersedia*.

### 4. 📷 Pemindai Barcode & QR Code
* Modal pemindai kamera bawaan untuk membaca barcode ISBN fisik atau kode eksemplar di rak secara instan.
* Dilengkapi opsi pencarian manual cepat jika kamera tidak aktif.

### 5. 🗄️ Rak Virtual Pribadi (My Bookshelf)
* Rekap pinjaman aktif dengan indikator hari tersisa sebelum jatuh tempo.
* Daftar keinginan (*Wishlist*) buku favorit untuk dibaca atau dipinjam kemudian.
* Riwayat peminjaman masa lalu lengkap dengan tanggal pengembalian.

### 6. ⭐ Ulasan & Rating Buku
* Mahasiswa dan dosen dapat memberikan rating bintang 1–5 serta testimoni ulasan.
* Perhitungan rata-rata skor (*average rating*) diperbarui otomatis pada katalog.

---

## 👥 Arsitektur Multi-Role (RBAC)

DIGIPUS menerapkan tata kelola hak akses berbasis peran menggunakan **Spatie Laravel-Permission**:

| Role | Kuota Pinjam | Lingkup Akses & Wewenang |
| :--- | :---: | :--- |
| **👑 Super Admin** | **99 Buku** | Akses penuh sistem, manajemen seluruh akun user, master data kategori & penerbit, audit log, dan analitik sirkulasi global. |
| **📚 Pustakawan** | **99 Buku** | Manajemen katalog koleksi (tambah/hapus buku), manajemen stok & fisik eksemplar, validasi sirkulasi meja counter. |
| **🎓 Dosen** | **15 Buku** | Akses bebas e-book akademik, kuota pinjam diperluas untuk riset, kurasi bahan ajar (*reading list*) mahasiswa. |
| **🎒 Mahasiswa** | **5 Buku** | Eksplorasi katalog, reservasi buku fisik, baca e-book online, rak virtual mandiri, review & perpanjangan online. |
| **🌐 Tamu / Publik** | *Read Only* | Penjelajahan katalog publik terbuka (SEO-friendly) tanpa fitur peminjaman. |

---

## 🛠️ Teknologi & Stack

### Backend
* **Framework:** [Laravel 13.x](https://laravel.com/) (PHP 8.3+)
* **Autentikasi:** [Laravel Sanctum](https://laravel.com/docs/sanctum) (Token-based API Authentication)
* **RBAC:** [Spatie Laravel-Permission 8.x](https://spatie.be/docs/laravel-permission)
* **Database:** SQLite (dev default) / MariaDB / MySQL
* **Routing:** RESTful JSON API (`/api/*`) + SPA Catch-all (`/{any}`)

### Frontend
* **Core:** [Vue 3](https://vuejs.org/) (Composition API, `<script setup>`)
* **Routing:** [Vue Router 4](https://router.vuejs.org/) dengan *smooth scrolling* dan *dynamic page titles*
* **State Management:** [Pinia 4](https://pinia.vuejs.org/)
* **Styling:** [Tailwind CSS v4](https://tailwindcss.com/) dengan `@tailwindcss/vite`
* **Ikon:** [Lucide Vue](https://lucide.dev/) (`lucide-vue-next` & `@lucide/vue`)
* **PWA Engine:** [Vite PWA Plugin](https://vite-pwa-org.netlify.app/) (Workbox Service Worker caching)
* **Build Tool:** [Vite 8](https://vite.dev/)

---

## 📁 Struktur Direktori

```text
e-perpus/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   │           ├── AuthController.php    # Autentikasi Sanctum & data profil pengguna
│   │           ├── BookController.php    # CRUD katalog, kategori, ulasan, & rating
│   │           ├── LoanController.php    # Sirkulasi, peminjaman, perpanjangan, pengembalian
│   │           └── StatsController.php   # Statistik publik & analitik perpustakaan
│   └── Models/                           # Model Eloquent (Book, Author, Loan, Ebook, dll.)
├── database/
│   ├── migrations/                       # Skema tabel users, RBAC, master catalog, loans
│   └── seeders/
│       └── DatabaseSeeder.php            # Seed data 5 role, master kategori, & koleksi buku
├── frontend/                             # Aplikasi Single Page Application (Vue 3 + Vite)
│   ├── public/                           # Aset statis PWA (favicon, manifest, ikon)
│   ├── src/
│   │   ├── assets/                       # Gambar & ilustrasi
│   │   ├── components/
│   │   │   ├── books/                    # Komponen buku (Card, Detail, Reader, Scanner, Add)
│   │   │   ├── layout/                   # Navbar & Mobile Bottom Navigation
│   │   │   └── ui/                       # Komponen Claymorphism (Button, Card, Badge, Modal, Input)
│   │   ├── data/                         # Data mock cadangan offline
│   │   ├── router/                       # Konfigurasi Vue Router
│   │   ├── stores/                       # Pinia Store (library.js)
│   │   ├── views/                        # Halaman view (Home, Catalog, Bookshelf, Roles, Playground)
│   │   ├── App.vue                       # Root Component
│   │   ├── main.js                       # Inisialisasi Vue & Plugin
│   │   └── style.css                     # Utility CSS Claymorphism & tema warna
│   ├── package.json                      # Dependensi frontend
│   └── vite.config.js                    # Konfigurasi Vite & Workbox PWA
├── public/                               # Web root Laravel (berisi hasil build frontend SPA)
├── routes/
│   ├── api.php                           # Definisi rute RESTful API
│   └── web.php                           # Catch-all route SPA DIGIPUS
├── composer.json                         # Dependensi PHP & Laravel
└── README.md                             # Dokumentasi proyek
```

---

## 🚀 Panduan Instalasi & Penggunaan

### Prasyarat Sistem
Pastikan perangkat pengembangan Anda telah terpasang:
- **PHP** `>= 8.3` (dengan ekstensi `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `sqlite3`)
- **Composer** `>= 2.7`
- **Node.js** `>= 20.x` & **npm** `>= 10.x`

---

### Langkah 1: Klon Repositori & Konfigurasi Lingkungan

```bash
git clone https://github.com/username/e-perpus.git
cd e-perpus

# Salin file konfigurasi lingkungan
cp .env.example .env
```

---

### Langkah 2: Instalasi Dependensi Backend & Database

```bash
# Instal dependensi PHP
composer install

# Buat application key
php artisan key:generate

# Jalankan migrasi dan seeder data awal
php artisan migrate --seed
```

> [!NOTE]
> Secara default, aplikasi menggunakan database **SQLite** (`database/database.sqlite`). Jika Anda menggunakan MySQL/MariaDB, sesuaikan nilai `DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` pada file `.env`.

---

### Langkah 3: Instalasi Dependensi Frontend

```bash
# Masuk ke folder frontend dan pasang dependensi
cd frontend
npm install
cd ..
```

---

### Langkah 4: Menjalankan Server Pengembangan

Anda dapat menjalankan backend dan frontend secara bersamaan dalam dua tab terminal terpisah:

**Terminal 1 — Backend API (Laravel):**
```bash
php artisan serve
# Berjalan di: http://127.0.0.1:8000
```

**Terminal 2 — Frontend Dev Server (Vite):**
```bash
cd frontend
npm run dev
# Berjalan di: http://localhost:5173 (dengan proxy otomatis ke port 8000)
```

---

### Langkah 5: Membangun Aplikasi untuk Produksi (Opsional)

Untuk menyatukan frontend SPA ke dalam direktori `public/` Laravel agar dapat diakses langsung melalui satu server:

```bash
# Dari root proyek:
npm run build

# Atau dari folder frontend:
cd frontend && npm run build
```

Setelah proses build selesai, buka browser dan akses `http://127.0.0.1:8000` melalui `php artisan serve`. Seluruh rute SPA akan dilayani langsung oleh Laravel.

---

## 🔑 Akun Demo Bawaan

Database seeder telah menyiapkan 4 akun uji coba untuk masing-masing tingkatan peran dengan kata sandi yang sama:

| Peran (Role) | Nama Pengguna | Alamat Email | Password | Kuota Pinjam |
| :--- | :--- | :--- | :---: | :---: |
| **Super Admin** | Bima Administrator | `admin@digitech.ac.id` | `password` | 99 Buku |
| **Pustakawan** | Siti Rahmawati, S.Sos. | `pustakawan@digitech.ac.id` | `password` | 99 Buku |
| **Dosen** | Dr. Hendra Gunawan, M.T. | `dosen@digitech.ac.id` | `password` | 15 Buku |
| **Mahasiswa** | Rafi Pratama | `mahasiswa@digitech.ac.id` | `password` | 5 Buku |
| **Tamu** | Pengunjung Umum | *(Akses langsung tanpa login)* | — | — |

---

## 🔌 Dokumentasi REST API

Semua respons endpoint dikembalikan dalam format standar **JSON**.

### Rute Publik
| Metode | Endpoint | Deskripsi |
| :--- | :--- | :--- |
| `GET` | `/api/stats` | Mengambil statistik umum perpustakaan (koleksi, anggota, peminjam aktif). |
| `GET` | `/api/categories` | Mengambil daftar semua kategori beserta jumlah buku. |
| `GET` | `/api/books` | Mengambil daftar buku terpaginasi (mendukung query `q`, `category`, `format`, `available_only`, `sort`). |
| `GET` | `/api/books/{slug}` | Mengambil detail lengkap suatu buku (penulis, penerbit, salinan eksemplar, ulasan). |
| `POST` | `/api/auth/login` | Melakukan autentikasi dan menghasilkan token akses Sanctum. |

### Rute Terproteksi (Bearer Token Sanctum)
| Metode | Endpoint | Deskripsi |
| :--- | :--- | :--- |
| `GET` | `/api/auth/me` | Mengambil data profil user yang sedang login beserta peran dan status kuota. |
| `POST` | `/api/auth/logout` | Mencabut token akses pengguna saat ini. |
| `POST` | `/api/books` | Menambahkan koleksi buku baru (khusus Pustakawan & Admin). |
| `DELETE` | `/api/books/{id}` | Menghapus koleksi buku dari sistem (Pustakawan & Admin). |
| `POST` | `/api/books/{id}/reviews` | Mengirimkan ulasan dan skor rating buku (1–5 bintang). |
| `GET` | `/api/loans` | Mengambil daftar sirkulasi pinjaman user (parameter `status=active` atau `history`). |
| `POST` | `/api/loans/borrow` | Mengajukan peminjaman buku (memeriksa kuota, ketersediaan stok fisik/digital). |
| `POST` | `/api/loans/{id}/extend` | Memperpanjang masa pinjam (+7 hari, maksimal 2 kali perpanjangan). |
| `POST` | `/api/loans/{id}/return` | Mengembalikan buku dan memulihkan stok eksemplar. |

---

## 📱 PWA & Mode Offline

DIGIPUS dirancang sebagai **Progressive Web App (PWA)** dengan konfigurasi Workbox:
* **Instalasi Mandiri:** Dapat diinstal di desktop (Chrome/Edge) maupun ponsel Android/iOS (*Add to Home Screen*) layaknya aplikasi native.
* **Strategi Caching:**
  * `CacheFirst` untuk font Google (Plus Jakarta Sans & Inter) dengan masa kedaluwarsa 1 tahun.
  * `NetworkFirst` untuk rute API katalog (`/api/books`, `/api/categories`, `/api/stats`).
* **Fallback Offline:** Jika koneksi internet terputus, aplikasi beralih secara anggun ke *local store cache* dan data katalog tersimpan.

---

## 📈 Roadmap & Status Pengembangan

- [x] **Fase 0:** Setup repo, fondasi desain Claymorphism, dan rancangan arsitektur.
- [x] **Fase 1:** Konfigurasi backend Laravel, database migrasi, auth Sanctum, dan seeder 5 role RBAC Spatie.
- [x] **Fase 2:** Katalog buku, kategori, pencarian berjenjang, filter format buku fisik/e-book.
- [x] **Fase 3:** Alur sirkulasi peminjaman, perpanjangan mandiri, pengembalian, dan in-browser reader.
- [x] **Fase 4:** Simulasi dashboard per role (Super Admin, Pustakawan, Dosen, Mahasiswa, Tamu).
- [x] **Fase 5:** Implementasi komponen UI Claymorphism responsif (Desktop & Mobile Bottom Nav).
- [x] **Fase 6:** PWA Service Worker caching & status koneksi offline.
- [ ] **Fase 7:** Integrasi Payment Gateway (Midtrans/Xendit) untuk pembayaran denda online.
- [ ] **Fase 8:** Integrasi Single Sign-On (SSO) SIAKAD kampus.
- [ ] **Fase 9:** Integrasi mesin pencari Meilisearch untuk pencarian teks penuh skala besar.

---

## 🤝 Kontribusi & Lisensi

Kontribusi selalu disambut dengan baik untuk menyempurnakan platform ini:
1. *Fork* repositori ini.
2. Buat *feature branch* baru (`git checkout -b fitur/fitur-keren-anda`).
3. Lakukan *commit* perubahan Anda (`git commit -m 'Menambahkan fitur sirkulasi otomatis'`).
4. *Push* ke branch (`git push origin fitur/fitur-keren-anda`).
5. Buat *Pull Request* baru.

Platform ini dilisensikan di bawah lisensi terbuka [MIT License](LICENSE).

---

<p align="center">
  Dikelola oleh <strong>Biro Sistem Informasi & UPT Perpustakaan Digitech University</strong><br>
  <em>Menghubungkan Literasi Akademik dengan Inovasi Digital</em>
</p>
