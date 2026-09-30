# 📖 PANDUAN PENGEMBANGAN PLATFORM
## DIGIPUS — E-Perpustakaan Terpadu Digitech University (UTD)

> Dokumentasi resmi pengembangan untuk *software engineer*, kontributor backend, frontend,
> dan desainer sistem. Mencakup arsitektur platform, panduan penambahan fitur baru end-to-end,
> pengembangan modul fungsi backend, desain komponen UI Claymorphism, serta deployment.

---

## 📑 DAFTAR ISI

1. [Arsitektur Platform](#1-arsitektur-platform)
2. [Stack Teknologi](#2-stack-teknologi)
3. [Setup Lingkungan Pengembangan](#3-setup-lingkungan-pengembangan)
4. [Struktur Direktori](#4-struktur-direktori)
5. [Konvensi & Standar Kode](#5-konvensi--standar-kode)
6. [Pengembangan Fitur Baru (End-to-End)](#6-pengembangan-fitur-baru-end-to-end)
7. [Pengembangan Modul Fungsi Backend](#7-pengembangan-modul-fungsi-backend)
8. [Pengembangan UI & Desain Claymorphism](#8-pengembangan-ui--desain-claymorphism)
9. [REST API Reference](#9-rest-api-reference)
10. [Pengujian & Quality Assurance](#10-pengujian--quality-assurance)
11. [Build & Deployment](#11-build--deployment)
12. [Troubleshooting Umum](#12-troubleshooting-umum)

---

## 1. Arsitektur Platform

DIGIPUS mengadopsi arsitektur **Decoupled SPA (Single Page Application)** — backend Laravel menyediakan REST API stateless, sementara Vue.js 3 mengonsumsinya sebagai klien independen.

```
┌──────────────────────────────────────────────────────────────────┐
│                      PENGGUNA / BROWSER                          │
├──────────────────────────────────────────────────────────────────┤
│  Vue.js 3.5 (Composition API · <script setup> · Pinia 4.x)       │
│  Tailwind CSS v4 · Claymorphism Design System · Lucide Icons     │
│  Vue Router 4 · PWA (Vite PWA Plugin · Workbox ServiceWorker)   │
└─────────────────────────┬────────────────────────────────────────┘
                          │  HTTP REST API  ·  JSON  ·  Bearer Token
                          │  Proxy: Vite → http://127.0.0.1:8000
┌─────────────────────────▼────────────────────────────────────────┐
│                     BACKEND — LARAVEL                            │
│  Laravel 13.x · PHP 8.3+ · Laravel Sanctum (Token Auth)         │
│  Spatie Laravel Permission v8.3 (RBAC: 5 Roles · 24 Perms)      │
│  Eloquent ORM · Repository Pattern · Service Layer               │
└─────────────────────────┬────────────────────────────────────────┘
                          │  Eloquent ORM  ·  Query Builder
┌─────────────────────────▼────────────────────────────────────────┐
│                  DATA LAYER & STORAGE                            │
│  Database: MariaDB (db_digipus) · Migrasi Bertahap               │
│  Cache: Database Cache · Session: Database Driver                │
│  File Storage: local disk · storage:link → public/storage        │
└──────────────────────────────────────────────────────────────────┘
```

### Prinsip Desain Sistem

| Prinsip | Implementasi |
|:---|:---|
| **Stateless Auth** | Token Sanctum (`PersonalAccessToken`) dikirim via `Authorization: Bearer` |
| **Dual-Mode Fallback** | Pinia store otomatis fallback ke mock data bila API offline |
| **RBAC Granular** | 5 roles hierarkis + 24 permissions modular via Spatie |
| **Offline-First PWA** | ServiceWorker Workbox precache assets statis untuk mode offline |
| **Claymorphism UI** | Desain taktil 3D dengan dual-shadow, rounded-3xl, backdrop-blur |

---

## 2. Stack Teknologi

### Backend
| Teknologi | Versi | Peran |
|:---|:---:|:---|
| PHP | `8.3+` | Runtime bahasa backend |
| Laravel | `13.x` | Framework web utama |
| Laravel Sanctum | `4.0` | Autentikasi API token stateless |
| Spatie Permission | `8.3` | RBAC — roles & permissions granular |
| Eloquent ORM | bawaan | Abstraksi query database |
| MariaDB | `10.x` | Basis data relasional |
| Laravel Tinker | `3.0` | REPL interaktif debug & eksplorasi |

### Frontend
| Teknologi | Versi | Peran |
|:---|:---:|:---|
| Vue.js | `3.5` | Framework UI reaktif (Composition API) |
| Vite | `8.x` | Build tool & dev server dengan HMR |
| Pinia | `4.x` | State management — reactive global store |
| Vue Router | `4.x` | Routing SPA — history mode |
| Tailwind CSS | `v4` | Utility-first CSS framework |
| Lucide Vue Next | latest | Icon library — konsisten & ringan |
| Vite PWA Plugin | `1.3` | PWA manifest + Workbox service worker |

---

## 3. Setup Lingkungan Pengembangan

### Prasyarat
- **PHP** `8.2+` dengan ekstensi: `pdo_mysql`, `pdo_sqlite`, `mbstring`, `openssl`, `fileinfo`, `curl`, `json`, `bcmath`
- **Composer** `2.x`
- **Node.js** `18.x` atau `20.x LTS` + `npm 9+`
- **MariaDB / MySQL** `10.x+` (atau SQLite untuk pengembangan cepat)

### Instalasi Langkah-demi-Langkah

#### Langkah 1 — Clone & Konfigurasi Environment
```bash
git clone <url-repositori> e-perpus-utd
cd e-perpus-utd

# Salin file env
cp .env.example .env

# Generate application key
php artisan key:generate
```

Edit `.env` sesuai konfigurasi lokal:
```env
APP_NAME=Digipus
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_digipus
DB_USERNAME=root
DB_PASSWORD=root

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

#### Langkah 2 — Instalasi Dependensi
```bash
# Backend PHP
composer install

# Frontend JavaScript
cd frontend && npm install && cd ..
```

#### Langkah 3 — Migrasi & Seeding Database
```bash
# Buat database terlebih dahulu di MariaDB:
# CREATE DATABASE db_digipus CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

php artisan migrate:fresh --seed
```

Perintah ini akan membuat:
- ✅ Seluruh tabel skema (users, books, loans, ebook, dll)
- ✅ Tabel Sanctum personal access tokens
- ✅ Tabel Spatie (permissions, roles, model_has_roles, dll)
- ✅ 24 permissions + 5 roles + permission mapping
- ✅ 10 akun pengguna seeded (lihat `AKUN.md`)
- ✅ 8 kategori, 5 penerbit, 8 pengarang
- ✅ 8 koleksi buku (fisik + e-book) + eksemplar + data pinjaman awal

#### Langkah 4 — Storage Symlink
```bash
php artisan storage:link
```

#### Langkah 5 — Jalankan Server Pengembangan

**Terminal 1 — Backend API:**
```bash
php artisan serve --port=8000
# API tersedia di http://127.0.0.1:8000
```

**Terminal 2 — Frontend Dev Server:**
```bash
cd frontend
npm run dev
# Frontend tersedia di http://localhost:5173
# Vite proxy otomatis teruskan /api/* ke port 8000
```

---

## 4. Struktur Direktori

```
e-perpus-utd/                          ← Root proyek
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/                   ← REST API Controllers
│   │   │       ├── AuthController.php     login, me, logout
│   │   │       ├── BookController.php     katalog, CRUD, review
│   │   │       ├── LoanController.php     borrow, extend, return
│   │   │       └── StatsController.php    statistik publik
│   │   └── Middleware/                ← Custom HTTP middlewares
│   ├── Models/                        ← Eloquent ORM Models
│   │   ├── User.php                      HasApiTokens, HasRoles
│   │   ├── Book.php                      relasi category, authors, copies, ebook
│   │   ├── BookCopy.php                  eksemplar fisik + barcode
│   │   ├── Loan.php                      record peminjaman aktif
│   │   ├── Ebook.php                     metadata e-book + sample_content
│   │   ├── Category.php                  kategori buku
│   │   ├── Author.php                    pengarang
│   │   ├── Publisher.php                 penerbit
│   │   ├── Review.php                    ulasan & rating
│   │   ├── Fine.php                      denda keterlambatan
│   │   └── Wishlist.php                  rak favorit virtual
│   └── Providers/
│       └── AppServiceProvider.php
│
├── bootstrap/
│   └── app.php                        ← Konfigurasi middleware aliases (Spatie)
│
├── config/
│   ├── auth.php
│   ├── cors.php                       ← CORS untuk akses API dari Vite dev server
│   ├── permission.php                 ← Konfigurasi Spatie permission
│   └── sanctum.php
│
├── database/
│   ├── migrations/                    ← File skema migrasi berurutan
│   └── seeders/
│       └── DatabaseSeeder.php         ← Seeder utama (roles, perms, users, katalog)
│
├── frontend/                          ← Aplikasi SPA Vue.js 3
│   ├── public/
│   │   ├── icons/                     ← Favicon & PWA icons
│   │   └── manifest.webmanifest       ← PWA manifest
│   ├── src/
│   │   ├── assets/                    ← Font, gambar, CSS tambahan
│   │   ├── components/
│   │   │   ├── books/                 ← Komponen domain buku
│   │   │   │   ├── BookCard.vue           kartu buku dalam grid katalog
│   │   │   │   ├── BookDetailModal.vue    modal detail + aksi pinjam
│   │   │   │   ├── BookAddModal.vue       form tambah koleksi (pustakawan)
│   │   │   │   ├── EbookReaderModal.vue   pembaca e-book in-browser + DRM
│   │   │   │   └── BarcodeScannerModal.vue scanner barcode simulasi
│   │   │   ├── layout/                ← Komponen layout navigasi
│   │   │   │   ├── MobileBottomNav.vue    navigasi bawah mobile (4 item)
│   │   │   │   └── ...
│   │   │   └── ui/                    ← Design system Claymorphism
│   │   │       ├── ClayButton.vue         tombol taktil (5 varian, 4 ukuran)
│   │   │       ├── ClayBadge.vue          pill status warna dinamis
│   │   │       ├── ClayCard.vue           container kartu clay
│   │   │       ├── ClayInput.vue          input field clay style
│   │   │       └── ClayModal.vue          overlay modal dialog
│   │   ├── data/
│   │   │   └── mockBooks.js           ← Dataset fallback reaktif (API offline)
│   │   ├── router/
│   │   │   └── index.js               ← Konfigurasi Vue Router + SEO meta title
│   │   ├── stores/
│   │   │   └── library.js             ← Pinia store utama (state + actions)
│   │   ├── views/                     ← Halaman-halaman utama SPA
│   │   │   ├── HomeView.vue               beranda hero + statistik + katalog sorotan
│   │   │   ├── CatalogView.vue            halaman katalog lengkap + filter
│   │   │   ├── BookshelfView.vue          rak virtual + riwayat pinjam
│   │   │   ├── RoleDashboardView.vue      simulasi 5 role RBAC
│   │   │   └── ClayPlaygroundView.vue     lab komponen UI (referensi internal)
│   │   ├── App.vue                    ← Root component + navigasi utama
│   │   ├── main.js                    ← Entry point Vue 3 + plugin registrasi
│   │   └── style.css                  ← CSS global + clay token utilities
│   ├── index.html                     ← HTML shell + SEO meta tags
│   ├── vite.config.js                 ← Vite config + API proxy + PWA plugin
│   └── package.json
│
├── routes/
│   ├── api.php                        ← Endpoint REST API (publik + Sanctum + RBAC)
│   ├── web.php                        ← Laravel health-check route
│   └── console.php                    ← Perintah Artisan kustom
│
├── AKUN.md                            ← 🔐 Kredensial & matriks izin
├── AGENTS.md                          ← 🤖 Panduan AI agent development
├── DOCUMENTATION.md                   ← 📖 Panduan pengembangan (file ini)
├── LICENSE.md                         ← ⚖️ Lisensi MIT
└── README.md                          ← 🌟 Gambaran umum & showcase proyek
```

---

## 5. Konvensi & Standar Kode

### Backend PHP
- Standar PSR-12 — jalankan `./vendor/bin/pint` untuk format otomatis
- Nama method: `camelCase` → `activeLoans()`, `borrowBook()`
- Nama class/model: `PascalCase` → `BookCopy`, `EbookReader`
- Validasi selalu di Controller menggunakan `$request->validate([...])`
- Response selalu kembalikan `JsonResponse` dengan tipe return yang eksplisit
- Gunakan `DB::transaction()` untuk operasi multi-tabel

### Frontend Vue.js
- Selalu gunakan `<script setup>` (Composition API)
- Nama file komponen: `PascalCase` → `ClayButton.vue`, `BookDetailModal.vue`
- Nama file halaman: `NamaHalamanView.vue` (suffix `View`)
- Props, emit dan state: `camelCase`
- Import icon dari `lucide-vue-next` — satu baris per icon
- Semua fetch API melalui actions di `library.js` store, bukan langsung di komponen

### Naming Convention Database
- Nama tabel: `snake_case` plural → `book_copies`, `personal_access_tokens`
- Foreign key: `{model}_id` → `user_id`, `book_id`
- Kolom boolean: prefix `is_` → `is_physical`, `is_digital`
- Kolom timestamp: `created_at`, `updated_at` (Eloquent auto)

---

## 6. Pengembangan Fitur Baru (End-to-End)

Setiap fitur baru mengikuti siklus 7 langkah. Studi kasus: **Fitur Reservasi Buku**.

```
Migration → Model → Permission → Controller → Route → Pinia Store → Vue Component
```

### Langkah 1 — Buat Migration
```bash
php artisan make:migration create_reservations_table
```
```php
// database/migrations/xxxx_create_reservations_table.php
Schema::create('reservations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('book_id')->constrained()->cascadeOnDelete();
    $table->string('reservation_code', 20)->unique();
    $table->date('reserved_date');
    $table->date('expiry_date');
    $table->enum('status', ['pending', 'ready_for_pickup', 'fulfilled', 'cancelled'])
          ->default('pending');
    $table->text('notes')->nullable();
    $table->timestamps();
});
```
```bash
php artisan migrate
```

### Langkah 2 — Buat Model Eloquent
```bash
php artisan make:model Reservation
```
```php
// app/Models/Reservation.php
class Reservation extends Model
{
    protected $fillable = [
        'user_id', 'book_id', 'reservation_code',
        'reserved_date', 'expiry_date', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'reserved_date' => 'date',
            'expiry_date'   => 'date',
        ];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function book(): BelongsTo { return $this->belongsTo(Book::class); }
}
```

Tambah relasi di `User.php` dan `Book.php`:
```php
public function reservations(): HasMany
{
    return $this->hasMany(Reservation::class);
}
```

### Langkah 3 — Daftarkan Permission Baru
Di `DatabaseSeeder.php`, tambahkan ke array `$permissions`:
```php
'reservations.create',
'reservations.view_own',
'reservations.manage',
```
Sinkronkan ke role yang sesuai lalu jalankan:
```bash
php artisan db:seed
php artisan permission:cache-reset
```

### Langkah 4 — Buat API Controller
```bash
php artisan make:controller Api/ReservationController
```
```php
// app/Http/Controllers/Api/ReservationController.php
class ReservationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $reservations = Reservation::with('book')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('id')->get();

        return response()->json($reservations);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'notes'   => 'nullable|string|max:255',
        ]);

        $user = $request->user();

        // Cegah duplikasi reservasi aktif
        $exists = Reservation::where('user_id', $user->id)
            ->where('book_id', $validated['book_id'])
            ->whereIn('status', ['pending', 'ready_for_pickup'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Anda sudah memiliki reservasi aktif untuk buku ini.',
            ], 422);
        }

        $now = Carbon::now();
        $reservation = Reservation::create([
            'user_id'          => $user->id,
            'book_id'          => $validated['book_id'],
            'reservation_code' => 'RESV-' . date('Y') . '-' . strtoupper(Str::random(5)),
            'reserved_date'    => $now->toDateString(),
            'expiry_date'      => $now->addDays(3)->toDateString(),
            'status'           => 'pending',
            'notes'            => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'message'     => 'Reservasi berhasil! Koleksi akan disiapkan dalam 1 hari kerja.',
            'reservation' => $reservation->load('book'),
        ], 201);
    }
}
```

### Langkah 5 — Daftarkan Rute di `routes/api.php`
```php
Route::middleware('auth:sanctum')->group(function () {
    // ... rute yang sudah ada ...

    // Reservasi Buku
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservations', [ReservationController::class, 'store']);

    // Manajemen reservasi (admin & pustakawan)
    Route::middleware('role:admin|pustakawan')->group(function () {
        Route::patch('/reservations/{id}/status', [ReservationController::class, 'updateStatus']);
    });
});
```

### Langkah 6 — Tambahkan State & Action di Pinia Store
```javascript
// frontend/src/stores/library.js — di dalam state():
reservations: [],

// di dalam actions:
async fetchReservations() {
  if (!this.authToken) return
  try {
    const res = await fetch('/api/reservations', {
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${this.authToken}`
      }
    })
    if (res.ok) this.reservations = await res.json()
  } catch (err) { console.error('Gagal ambil reservasi:', err) }
},

async makeReservation(bookId, notes = '') {
  if (!this.authToken) {
    this.showToast('Silakan login untuk melakukan reservasi.', 'error')
    return false
  }
  try {
    const res = await fetch('/api/reservations', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${this.authToken}`
      },
      body: JSON.stringify({ book_id: bookId, notes })
    })
    const data = await res.json()
    if (res.ok) {
      this.reservations.unshift(data.reservation)
      this.showToast(data.message, 'success')
      return true
    }
    this.showToast(data.message || 'Gagal reservasi.', 'error')
    return false
  } catch {
    this.showToast('Gagal menghubungi server.', 'error')
    return false
  }
}
```

### Langkah 7 — Tambahkan Komponen UI
```vue
<!-- Di dalam BookDetailModal.vue, tampilkan saat stok = 0 -->
<template>
  <ClayButton
    v-if="book.available_stock === 0 && libraryStore.currentRole !== 'tamu'"
    variant="slate"
    size="md"
    @click="libraryStore.makeReservation(book.id)"
  >
    <Bell class="w-4 h-4 mr-2" />
    Daftar Antrean Reservasi
  </ClayButton>
</template>
```

---

## 7. Pengembangan Modul Fungsi Backend

### Otorisasi RBAC di Controller
```php
// Cek permission tunggal secara eksplisit
if (!$request->user()->can('books.create')) {
    return response()->json(['message' => 'Akses ditolak.'], 403);
}

// Cek role
if ($request->user()->hasRole(['admin', 'pustakawan'])) {
    // Jalankan operasi eksklusif staff
}

// Cek kombinasi role ATAU permission
if ($request->user()->hasAnyRole(['admin', 'pustakawan']) ||
    $request->user()->can('loans.manage')) {
    // Akses diizinkan
}
```

### Middleware Otorisasi di Route Level
```php
// Berdasarkan role (satu atau lebih dengan pipe |)
Route::middleware(['auth:sanctum', 'role:admin|pustakawan'])->group(fn() => [
    Route::post('/books', [BookController::class, 'store']),
    Route::delete('/books/{id}', [BookController::class, 'destroy']),
]);

// Berdasarkan permission spesifik
Route::middleware(['auth:sanctum', 'permission:reports.export'])->group(fn() => [
    Route::get('/reports/export', [ReportController::class, 'export']),
]);

// Role ATAU permission
Route::middleware(['auth:sanctum', 'role_or_permission:admin|books.manage_copies'])->group(...);
```

### Transaksi Database Atomik
Selalu gunakan `DB::transaction()` untuk operasi yang menyentuh lebih dari satu tabel:
```php
use Illuminate\Support\Facades\DB;

$loan = DB::transaction(function () use ($user, $book, $now, $dueDate) {
    // Kunci baris untuk cegah race condition (concurrent request)
    $lockedBook = Book::where('id', $book->id)->lockForUpdate()->firstOrFail();

    if ($lockedBook->available_stock <= 0) {
        throw new \Exception('Stok habis');
    }

    // Potong stok & tambah borrow count atomik
    $lockedBook->decrement('available_stock');
    $lockedBook->increment('borrow_count');

    // Kunci eksemplar fisik
    $copy = BookCopy::where('book_id', $lockedBook->id)
        ->where('status', 'available')
        ->lockForUpdate()
        ->first();

    if ($copy) {
        $copy->update(['status' => 'borrowed']);
    }

    // Buat record pinjaman
    return Loan::create([
        'loan_code'    => 'PINJ-' . date('Y') . '-' . strtoupper(Str::random(5)),
        'user_id'      => $user->id,
        'book_id'      => $lockedBook->id,
        'book_copy_id' => $copy?->id,
        'borrow_date'  => $now->toDateString(),
        'due_date'     => $dueDate->toDateString(),
        'status'       => 'borrowed',
        'extend_count' => 0,
        'max_extend'   => $user->hasRole('dosen') ? 3 : 2,
    ]);
});
```

### Menambahkan Model Eloquent Baru
```bash
# Model + Migration + Controller sekaligus
php artisan make:model NamaModel -mc

# Atau terpisah
php artisan make:model NamaModel
php artisan make:migration create_nama_models_table
php artisan make:controller Api/NamaModelController
```

---

## 8. Pengembangan UI & Desain Claymorphism

### Filosofi Visual DIGIPUS
Claymorphism menciptakan antarmuka yang **taktil, ramah, dan hidup** — seolah elemen-elemen di layar memiliki dimensi fisik yang bisa disentuh.

### Token Desain Utama

```css
/* frontend/src/style.css — Token dasar Clay */

/* Kartu clay utama (bright/elevated) */
.clay-card {
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(12px);
  border-radius: 1.25rem;          /* rounded-2xl = 20px */
  border: 1px solid rgba(255,255,255, 0.8);
  box-shadow:
    8px 8px 16px rgba(166, 175, 195, 0.25),   /* shadow bawah kanan */
    -8px -8px 16px rgba(255, 255, 255, 0.8),  /* highlight atas kiri */
    inset 1px 1px 2px rgba(255, 255, 255, 0.9); /* inner rim */
}

/* Area terbenam / inset (form background, stat boxes) */
.clay-inset {
  background: #EEF2F6;
  border-radius: 1rem;             /* rounded-xl = 16px */
  box-shadow:
    inset 4px 4px 8px rgba(166, 175, 195, 0.3),
    inset -4px -4px 8px rgba(255, 255, 255, 0.8);
}

/* Navigasi bottom nav mobile */
.clay-nav {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(20px);
  box-shadow:
    0 -2px 20px rgba(166, 175, 195, 0.15),
    0 4px 20px rgba(166, 175, 195, 0.2);
}
```

### Palet Warna Identitas Digitech University

| Nama | Hex | Penggunaan |
|:---|:---:|:---|
| Navy Blue (Primer) | `#1E4FA3` | CTA utama, link aktif, badge primary |
| Crimson Red (Aksen) | `#D92B3E` | Notifikasi, badge error, tombol aksi kritis |
| Slate Background | `#F1F5F9` | Latar halaman |
| Card White | `#FFFFFF` (85% opacity) | Background kartu clay |

### Komponen UI yang Tersedia

#### `ClayButton.vue` — Tombol Interaktif
```vue
<ClayButton variant="blue" size="md" @click="handleBorrow">
  <BookOpen class="w-4 h-4 mr-2" />
  Pinjam Buku
</ClayButton>

<!-- Varian: blue | red | slate | ghost | outline -->
<!-- Ukuran:  xs | sm | md | lg                   -->
```

#### `ClayBadge.vue` — Pill Label Status
```vue
<ClayBadge variant="emerald" size="sm">Tersedia</ClayBadge>
<ClayBadge variant="amber" size="sm">Hampir Jatuh Tempo</ClayBadge>
<ClayBadge variant="red" size="sm">Sedang Dipinjam</ClayBadge>
<ClayBadge variant="blue" size="sm">E-Book PDF</ClayBadge>
<ClayBadge variant="slate" size="sm">Akses Publik</ClayBadge>

<!-- Varian: blue | red | emerald | amber | slate -->
<!-- Ukuran:  xs | sm | md                        -->
```

#### `ClayCard.vue` — Kontainer Kartu
```vue
<ClayCard class="p-6 space-y-4 hover:shadow-lg transition-shadow">
  <h3 class="font-bold font-heading text-slate-800">Judul Section</h3>
  <p class="text-sm text-slate-500">Deskripsi konten section ini.</p>
</ClayCard>
```

#### `ClayInput.vue` — Field Input
```vue
<ClayInput
  v-model="searchQuery"
  placeholder="Cari judul, ISBN, pengarang..."
  :icon="Search"
/>
```

#### `ClayModal.vue` — Dialog Overlay
```vue
<ClayModal :is-open="showModal" @close="showModal = false">
  <template #title>Judul Modal</template>
  <template #body>
    <!-- Konten modal di sini -->
  </template>
  <template #footer>
    <ClayButton variant="blue" @click="confirmAction">Konfirmasi</ClayButton>
  </template>
</ClayModal>
```

### Menambahkan Halaman View Baru

#### 1. Buat file View
```bash
touch frontend/src/views/ReservasiView.vue
```

```vue
<!-- frontend/src/views/ReservasiView.vue -->
<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-6 pb-20 md:pb-12">
    <h1 class="text-2xl font-extrabold font-heading text-slate-900">
      Antrean Reservasi Saya
    </h1>
    <!-- ... konten halaman ... -->
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useLibraryStore } from '../stores/library'
import ClayCard from '../components/ui/ClayCard.vue'

const libraryStore = useLibraryStore()
onMounted(() => libraryStore.fetchReservations())
</script>
```

#### 2. Daftarkan Rute di Router
```javascript
// frontend/src/router/index.js
{
  path: '/reservasi',
  name: 'reservasi',
  component: () => import('../views/ReservasiView.vue'),
  meta: { title: 'Antrean Reservasi — DIGIPUS Digitech University' }
}
```

#### 3. Tambahkan Link Navigasi (opsional)
```vue
<!-- Di App.vue (desktop nav) atau MobileBottomNav.vue -->
<router-link to="/reservasi" class="...">
  <Bell class="w-5 h-5" />
  <span class="text-[10px] mt-0.5">Reservasi</span>
</router-link>
```

---

## 9. REST API Reference

### Endpoint Publik (Tanpa Autentikasi)

| Method | Endpoint | Deskripsi |
|:---:|:---|:---|
| `GET` | `/api/stats` | Statistik perpustakaan publik |
| `GET` | `/api/categories` | Daftar kategori + jumlah buku |
| `GET` | `/api/books` | Katalog buku (filter, search, sort, paginate) |
| `GET` | `/api/books/{slug}` | Detail satu buku by slug/id |
| `POST` | `/api/auth/login` | Login — terima Bearer token |

### Parameter Query Katalog `GET /api/books`

| Parameter | Tipe | Contoh | Keterangan |
|:---|:---:|:---:|:---|
| `q` | string | `kecerdasan` | Cari di judul, ISBN, sinopsis, pengarang |
| `category` | string | `ti` | Filter slug kategori |
| `format` | string | `pdf` | Filter format: `physical`, `pdf`, `epub` |
| `available_only` | boolean | `1` | Hanya tampilkan yang tersedia |
| `sort` | string | `popular` | Urutan: `popular`, `latest`, `rating` |
| `per_page` | integer | `12` | Jumlah per halaman (default: 12) |

### Endpoint Terautentikasi (Sanctum Token)

| Method | Endpoint | Role | Deskripsi |
|:---:|:---|:---:|:---|
| `GET` | `/api/auth/me` | Semua | Profil user + permissions aktif |
| `POST` | `/api/auth/logout` | Semua | Cabut token aktif |
| `POST` | `/api/books` | admin, pustakawan | Tambah koleksi buku baru |
| `DELETE` | `/api/books/{id}` | admin, pustakawan | Hapus koleksi |
| `POST` | `/api/books/{id}/reviews` | Terautentikasi | Tambah ulasan & rating |
| `GET` | `/api/loans` | Terautentikasi | Daftar pinjaman sendiri |
| `POST` | `/api/loans/borrow` | Terautentikasi | Ajukan peminjaman |
| `POST` | `/api/loans/{id}/extend` | Terautentikasi | Perpanjang pinjaman |
| `POST` | `/api/loans/{id}/return` | Terautentikasi | Kembalikan buku |

---

## 10. Pengujian & Quality Assurance

### Backend Testing (PHPUnit)
```bash
# Jalankan seluruh test suite
php artisan test

# Jalankan file test tertentu
php artisan test tests/Feature/LoanTest.php

# Dengan coverage (butuh Xdebug)
php artisan test --coverage
```

### Linting & Code Style
```bash
# Cek kepatuhan PSR-12 tanpa mengubah file
./vendor/bin/pint --test

# Auto-fix semua pelanggaran gaya kode
./vendor/bin/pint
```

### Pengujian API Manual via cURL
```bash
# 1. Login sebagai mahasiswa
TOKEN=$(curl -s -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -d '{"email":"mahasiswa@digitech.ac.id","password":"password"}' \
  | grep -o '"token":"[^"]*"' | cut -d'"' -f4)

# 2. Cek profil
curl http://127.0.0.1:8000/api/auth/me \
  -H "Accept: application/json" -H "Authorization: Bearer $TOKEN"

# 3. Pinjam buku ID=1
curl -X POST http://127.0.0.1:8000/api/loans/borrow \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN" -d '{"book_id":1}'
```

---

## 11. Build & Deployment

### Build Produksi Frontend
```bash
npm run build
# Output: frontend/dist/ → otomatis di-copy ke public/ via build script
```

### Optimasi Cache Laravel
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Checklist Sebelum Deploy ke Produksi

- [ ] Ubah `APP_ENV=production` dan `APP_DEBUG=false`
- [ ] Generate `APP_KEY` baru: `php artisan key:generate`
- [ ] Ganti semua password default akun (lihat `AKUN.md`)
- [ ] Set `APP_URL` ke domain/subdomain produksi
- [ ] Pastikan `CORS_ALLOWED_ORIGINS` sesuai domain frontend
- [ ] Jalankan `php artisan migrate --force` di server
- [ ] Jalankan `php artisan db:seed` untuk data awal produksi
- [ ] Jalankan `php artisan storage:link` di server
- [ ] Jalankan `php artisan permission:cache-reset`
- [ ] Build frontend: `npm run build`
- [ ] Optimasi cache: `php artisan optimize`
- [ ] Tambahkan cron job Laravel Scheduler:
  ```bash
  * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
  ```

---

## 12. Troubleshooting Umum

| Masalah | Kemungkinan Penyebab | Solusi |
|:---|:---|:---|
| `419 CSRF token mismatch` | Request web tanpa token CSRF | Pastikan semua endpoint API ada di `routes/api.php` |
| `401 Unauthenticated` | Token tidak dikirim / expired | Periksa header `Authorization: Bearer <token>` |
| `403 User does not have the right roles` | Role/permission belum sesuai | Jalankan `php artisan db:seed` + `permission:cache-reset` |
| `CORS error` di browser | Konfigurasi CORS tidak mengizinkan origin Vite | Periksa `config/cors.php` → `allowed_origins` |
| API tidak terdeteksi di Vite dev | Proxy tidak dikonfigurasi | Periksa `frontend/vite.config.js` → blok `server.proxy` |
| Permissions tidak terupdate | Cache Spatie masih lama | `php artisan permission:cache-reset` |
| `Class "Permission" not found` | Import belum ditambahkan | Tambahkan `use Spatie\Permission\Models\Permission;` |
| Frontend menampilkan data lama | localStorage masih menyimpan token lama | Clear localStorage di DevTools → Application → Local Storage |

---

*Dokumen ini dikelola oleh Tim Pengembang Sistem Informasi (SI) & UPT Perpustakaan Terpadu — Digitech University. Terakhir diperbarui: 30 September 2026.*
