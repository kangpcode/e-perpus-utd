# 📖 PANDUAN PENGEMBANGAN PLATFORM, FITUR, MODUL FUNGSI & UI
## E-Perpustakaan Terpadu (Digitech University - UTD)

Selamat datang di dokumentasi resmi arsitektur dan pengembangan platform **DIGIPUS (Digital Library & Pustaka Digitech University)**. Dokumen ini dirancang khusus bagi *software engineer*, pengembang backend, pengembang frontend, dan kontributor sistem untuk memahami prinsip rancang bangun, standar penulisan kode, serta tata cara penambahan fitur, modul fungsi, dan antarmuka (UI).

---

## 📑 DAFTAR ISI

1. [Arsitektur & Gambaran Umum Platform](#1-arsitektur--gambaran-umum-platform)
2. [Prasyarat & Setup Lingkungan Pengembangan](#2-prasyarat--setup-lingkungan-pengembangan)
3. [Struktur Direktori & Konvensi Kode](#3-struktur-direktori--konvensi-kode)
4. [Panduan Pengembangan Fitur Baru (End-to-End)](#4-panduan-pengembangan-fitur-baru-end-to-end)
5. [Panduan Pengembangan Modul Fungsi (Backend Logic & RBAC)](#5-panduan-pengembangan-modul-fungsi-backend-logic--rbac)
6. [Panduan Pengembangan UI & Desain Claymorphism](#6-panduan-pengembangan-ui--desain-claymorphism)
7. [Pengujian, Deployment & Pemeliharaan Sistem](#7-pengujian-deployment--pemeliharaan-sistem)

---

## 1. ARSITEKTUR & GAMBARAN UMUM PLATFORM

Platform DIGIPUS mengadopsi arsitektur **Decoupled Single Page Application (SPA)** yang modern, cepat, dan tangguh:

```
┌─────────────────────────────────────────────────────────────┐
│                       KLIEN PENGGUNA                        │
│   Vue.js 3.5 (Composition API + <script setup>) + Vite 8   │
│   Tailwind CSS v4 + Claymorphism Design System              │
│   Pinia State Management + PWA (Offline-First ServiceWorker)│
└──────────────────────────────┬──────────────────────────────┘
                               │  REST API (JSON over HTTPS)
                               │  Bearer Token (Sanctum)
┌──────────────────────────────▼──────────────────────────────┐
│                      BACKEND SERVICES                       │
│   Laravel 11.x / 13.x (PHP 8.3+)                            │
│   Laravel Sanctum (Stateless API Authentication)            │
│   Spatie Laravel Permission (Granular RBAC - 24 Perms)      │
└──────────────────────────────┬──────────────────────────────┘
                               │  Eloquent ORM
┌──────────────────────────────▼──────────────────────────────┐
│                    DATA STORAGE & CACHE                     │
│   Database: SQLite / MySQL / PostgreSQL                     │
│   File Storage: Storage Symlink (E-Book & Cover Assets)     │
│   Cache & Session: Redis / File Cache                       │
└─────────────────────────────────────────────────────────────┘
```

### Prinsip Utama Sistem
1. **Stateless Authentication**: Autentikasi menggunakan token berbasis Laravel Sanctum (`PersonalAccessToken`). Setiap request dari frontend menyertakan header `Authorization: Bearer <token>`.
2. **Dual-Mode Reactive Fallback**: Frontend dirancang cerdas dengan Pinia Store; jika server backend sedang offline atau dalam tahap *maintenance*, aplikasi secara otomatis menyediakan *mock reactive data* sehingga demonstrasi UI tetap berjalan mulus.
3. **Role-Based Access Control (RBAC)**: Menggunakan Spatie Permission dengan 5 tingkatan peran hierarkis (`admin`, `pustakawan`, `dosen`, `mahasiswa`, `tamu`) dan 24 hak akses modular.
4. **Desain Claymorphism yang Taktil**: Pendekatan visual 3D modern dengan sudut lengkung lembut (`rounded-2xl`/`rounded-3xl`), bayangan ganda (*inner & drop shadows*), dan warna identitas Digitech University (*Navy Blue* `#1E4FA3` dan *Crimson Red* `#D92B3E`).

---

## 2. PRASYARAT & SETUP LINGKUNGAN PENGEMBANGAN

### Prasyarat Perangkat Lunak
- **PHP**: Versi `8.2` atau `8.3+` (ekstensi aktif: `pdo_sqlite`, `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `curl`, `json`, `bcmath`).
- **Composer**: Versi `2.x+`.
- **Node.js**: Versi `18.x` atau `20.x LTS` (beserta `npm` versi `9+`).
- **Git**: Versi terbaru.

### Langkah Instalasi Langkah demi Langkah

#### 1. Clone Repositori
```bash
git clone <repository_url> e-perpus-utd
cd e-perpus-utd
```

#### 2. Konfigurasi Backend Laravel
Salin berkas konfigurasi `.env` dan pasang dependensi Composer:
```bash
cp .env.example .env
composer install
php artisan key:generate
```

Pastikan konfigurasi koneksi database di `.env` sudah sesuai (secara default menggunakan SQLite atau MySQL):
```env
DB_CONNECTION=sqlite
# Atau jika menggunakan MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=eperpus_utd
# DB_USERNAME=root
# DB_PASSWORD=
```

#### 3. Migrasi & Seeding Basis Data
Jalankan migrasi untuk membuat seluruh tabel skema (termasuk tabel personal access token Sanctum dan tabel izin Spatie):
```bash
php artisan migrate:fresh --seed
```
*Perintah ini akan secara otomatis memicu `DatabaseSeeder` yang membuat 24 permissions, 5 roles, 10 akun default civitas, kategori buku, penerbit, pengarang, koleksi buku fisik & e-book, serta data sirkulasi awal.*

#### 4. Buat Symlink Storage Berkas
Agar berkas e-book dan cover buku dapat diakses secara publik oleh web browser:
```bash
php artisan storage:link
```

#### 5. Konfigurasi Frontend (Vue 3 + Vite)
Masuk ke direktori `frontend` dan pasang paket dependensi NPM:
```bash
cd frontend
npm install
cd ..
```

#### 6. Menjalankan Server Pengembangan Lokal
Jalankan dua terminal secara paralel:

**Terminal 1 (Backend API Server):**
```bash
php artisan serve --port=8000
```
*API akan aktif di `http://127.0.0.1:8000`.*

**Terminal 2 (Frontend Vite Server):**
```bash
cd frontend
npm run dev
```
*Frontend akan aktif di `http://localhost:5173` dengan reverse proxy Vite yang otomatis meneruskan request `/api/*` ke backend Laravel.*

---

## 3. STRUKTUR DIREKTORI & KONVENSI KODE

### Peta Folder Proyek
```
e-perpus-utd/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/                 # REST API Controllers (Auth, Book, Loan, Stats)
│   │   └── Middleware/              # HTTP Middlewares kustom
│   ├── Models/                      # Eloquent Models (User, Book, Loan, Ebook, Category, dll)
│   └── Providers/                   # Service Providers
├── bootstrap/
│   └── app.php                      # Registrasi routing, exceptions, & middleware aliases
├── config/                          # File konfigurasi (auth, permission, database, cors, dll)
├── database/
│   ├── migrations/                  # Skema tabel database berurutan
│   └── seeders/
│       └── DatabaseSeeder.php       # Seeder utama untuk roles, perms, users, & catalog
├── frontend/                        # Aplikasi SPA Vue.js 3
│   ├── public/                      # Static assets, icons, PWA manifest.json
│   ├── src/
│   │   ├── assets/                  # CSS global & visual assets
│   │   ├── components/
│   │   │   ├── books/               # Komponen buku (Card, Modal, BarcodeScanner)
│   │   │   ├── reader/              # Komponen pembaca e-book in-browser & DRM watermark
│   │   │   └── ui/                  # Desain Sistem Claymorphism (Button, Card, Badge, Modal)
│   │   ├── data/                    # Fallback dataset (mockBooks.js)
│   │   ├── router/                  # Konfigurasi Vue Router (rute, guards, meta SEO)
│   │   ├── stores/                  # Pinia state stores (library.js)
│   │   ├── views/                   # Tampilan halaman utama (Home, Catalog, Bookshelf, RoleDashboard)
│   │   ├── App.vue                  # Root layout & navigasi aplikasi
│   │   ├── main.js                  # Entry point Vue 3
│   │   └── style.css                # Utilitas CSS kustom & Claymorphism shadow tokens
│   ├── index.html                   # HTML template dengan SEO meta tags
│   ├── package.json                 # Dependensi frontend & build scripts
│   └── vite.config.js               # Konfigurasi server & proxy Vite
├── routes/
│   ├── api.php                      # Endpoint API publik & terlindungi (Sanctum + RBAC)
│   ├── console.php                  # Perintah Artisan kustom
│   └── web.php                      # Health check & web root fallback
├── AKUN.md                          # Dokumentasi kredensial login & matriks hak akses
├── DOCUMENTATION.md                 # Dokumentasi panduan arsitektur & pengembangan
├── LICENSE.md                       # Lisensi MIT perangkat lunak
└── README.md                        # Gambaran umum proyek & showcase fitur
```

### Konvensi Standar Kode
- **PHP / Backend**: Mengikuti standar **PSR-12** dan konvensi penamaan Laravel. Jalankan `./vendor/bin/pint` untuk pemformatan otomatis.
- **Method Naming**: Gunakan *camelCase* untuk nama metode di Controller dan Model (`public function activeLoans()`).
- **Vue / Frontend**: Gunakan sintaks **Vue 3 Composition API** dengan tag `<script setup>`.
- **Komponen**: Penamaan file komponen menggunakan *PascalCase* (contoh: `ClayButton.vue`, `BookDetailModal.vue`).
- **Stores**: Satu store Pinia mengelola satu domain konteks menggunakan *action* asynchronous yang teratur.

---

## 4. PANDUAN PENGEMBANGAN FITUR BARU (END-TO-END)

Setiap penambahan fitur baru harus mengikuti siklus terstruktur mulai dari pemodelan data, otorisasi, logika endpoint, hingga representasi antarmuka.

### Studi Kasus: Menambahkan Fitur "Reservasi Buku" (*Book Reservation*)

Berikut adalah tutorial lengkap penambahan fitur baru dari backend ke frontend:

```
[1. Migration] ──> [2. Model] ──> [3. Spatie Perms] ──> [4. Controller]
                                                               │
[7. Vue View]  <── [6. Pinia Store] <── [5. API Route] <───────┘
```

#### Langkah 1: Buat Migration Tabel
Jalankan perintah Artisan untuk membuat file migrasi baru:
```bash
php artisan make:migration create_reservations_table
```
Isi skema tabel di `database/migrations/xxxx_xx_xx_create_reservations_table.php`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->string('reservation_code')->unique();
            $table->date('reserved_date');
            $table->date('expiry_date');
            $table->enum('status', ['pending', 'ready_for_pickup', 'fulfilled', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
```
Jalankan migrasi:
```bash
php artisan migrate
```

#### Langkah 2: Definisikan Model Eloquent
Buat model `app/Models/Reservation.php`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'reservation_code',
        'reserved_date',
        'expiry_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'reserved_date' => 'date',
            'expiry_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
```
Tambahkan juga relasi `reservations()` pada model `app/Models/User.php` dan `app/Models/Book.php`:
```php
public function reservations(): HasMany
{
    return $this->hasMany(Reservation::class);
}
```

#### Langkah 3: Tambahkan Izin Baru pada Spatie Permission
Daftarkan permission baru di `database/seeders/DatabaseSeeder.php`:
```php
'reservations.create',
'reservations.view_own',
'reservations.manage',
```
Sinkronkan izin ini ke peran `mahasiswa`, `dosen` (untuk `create` & `view_own`), serta `admin` & `pustakawan` (untuk `manage`). Jalankan:
```bash
php artisan db:seed
```

#### Langkah 4: Buat API Controller
Buat controller `app/Http/Controllers/Api/ReservationController.php`:
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $reservations = Reservation::with('book')
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($reservations);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'notes' => 'nullable|string|max:255',
        ]);

        $user = $request->user();
        $book = Book::findOrFail($validated['book_id']);

        // Cegah reservasi ganda yang masih pending
        $existing = Reservation::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->whereIn('status', ['pending', 'ready_for_pickup'])
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Anda sudah memiliki antrean aktif untuk buku ini.',
            ], 422);
        }

        $now = Carbon::now();
        $reservation = Reservation::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'reservation_code' => 'RESV-' . date('Y') . '-' . strtoupper(Str::random(5)),
            'reserved_date' => $now->toDateString(),
            'expiry_date' => $now->copy()->addDays(3)->toDateString(),
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'message' => 'Reservasi berhasil didaftarkan!',
            'reservation' => $reservation->load('book'),
        ], 201);
    }
}
```

#### Langkah 5: Registrasikan Rute di `routes/api.php`
Buka `routes/api.php` dan tambahkan rute di dalam grup middleware `auth:sanctum`:
```php
Route::middleware('auth:sanctum')->group(function () {
    // ... rute yang sudah ada
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservations', [ReservationController::class, 'store']);
});
```

#### Langkah 6: Tambahkan Aksi di Pinia Store Frontend
Buka `frontend/src/stores/library.js`:
```javascript
// Di dalam state():
reservations: [],

// Di dalam actions:
async fetchReservations() {
  if (!this.authToken) return
  try {
    const res = await fetch('/api/reservations', {
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${this.authToken}`
      }
    })
    if (res.ok) {
      this.reservations = await res.json()
    }
  } catch (err) {
    console.error('Gagal mengambil data reservasi:', err)
  }
},

async makeReservation(bookId, notes = '') {
  if (!this.authToken) {
    this.showToast('Silakan login terlebih dahulu untuk melakukan reservasi.', 'error')
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
    } else {
      this.showToast(data.message || 'Gagal melakukan reservasi.', 'error')
      return false
    }
  } catch (err) {
    this.showToast('Gagal menghubungi server.', 'error')
    return false
  }
}
```

#### Langkah 7: Pasang Komponen UI Claymorphism
Gunakan komponen `ClayButton` dan panggil `libraryStore.makeReservation(book.id)` ketika stok buku sedang 0 pada `BookDetailModal.vue` atau halaman detail katalog!

---

## 5. PANDUAN PENGEMBANGAN MODUL FUNGSI (BACKEND LOGIC & RBAC)

### 1. Pola Otorisasi Peran & Izin Spatie di Controller
Gunakan metode resmi Spatie untuk memeriksa otorisasi pengguna secara eksplisit:

```php
// Cek izin tunggal
if (!$request->user()->can('books.create')) {
    return response()->json(['message' => 'Anda tidak memiliki hak untuk menambah koleksi.'], 403);
}

// Cek peran
if ($request->user()->hasRole('admin')) {
    // Jalankan operasi istimewa admin
}
```

### 2. Otorisasi pada Rute Middleware
Anda dapat membatasi rute langsung di `routes/api.php` menggunakan alias yang telah dikonfigurasi di `bootstrap/app.php`:
```php
// Membatasi berdasarkan Role tunggal atau jamak:
Route::middleware(['auth:sanctum', 'role:admin|pustakawan'])->group(function () {
    Route::post('/books', [BookController::class, 'store']);
    Route::delete('/books/{id}', [BookController::class, 'destroy']);
});

// Membatasi berdasarkan Permission spesifik:
Route::middleware(['auth:sanctum', 'permission:reports.view'])->group(function () {
    Route::get('/reports/circulation', [ReportController::class, 'circulation']);
});
```

### 3. Penggunaan Transaksi Basis Data Atomik (`DB::transaction`)
Untuk setiap operasi yang mengubah lebih dari satu tabel (contoh: sirkulasi pinjam buku yang melibatkan tabel `loans`, pemotongan stok pada `books`, dan penguncian status pada `book_copies`), selalu bungkus di dalam blok transaksi:

```php
use Illuminate\Support\Facades\DB;

DB::transaction(function () use ($user, $book, $now, $dueDate) {
    // 1. Kunci baris buku untuk mencegah race-condition
    $lockedBook = Book::where('id', $book->id)->lockForUpdate()->first();
    
    // 2. Potong stok
    $lockedBook->decrement('available_stock');
    $lockedBook->increment('borrow_count');

    // 3. Catat entri pinjaman baru
    Loan::create([
        'loan_code' => 'PINJ-' . date('Y') . '-' . strtoupper(Str::random(5)),
        'user_id' => $user->id,
        'book_id' => $lockedBook->id,
        'borrow_date' => $now->toDateString(),
        'due_date' => $dueDate->toDateString(),
        'status' => 'borrowed',
    ]);
});
```

---

## 6. PANDUAN PENGEMBANGAN UI & DESAIN CLAYMORPHISM

Antarmuka DIGIPUS dibangun di atas filosofi **Claymorphism** yang taktil, ramah, dan memanjakan mata civitas akademika.

### 1. Token Desain & Anatomi Claymorphism
Semua komponen clay menggunakan kombinasi CSS berikut:
- **Lengkung Sudut Lebar**: Menggunakan `rounded-2xl` (16px) atau `rounded-3xl` (24px).
- **Border Tipis Berkilau**: Menggunakan `border border-white/60` atau `border-white/80`.
- **Bayangan Ganda (Drop Shadow + Inset Highlight)**:
  ```css
  /* Kelas utilitas di style.css */
  .clay-card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(12px);
    box-shadow: 
      8px 8px 16px rgba(166, 175, 195, 0.25),
      -8px -8px 16px rgba(255, 255, 255, 0.8),
      inset 1px 1px 2px rgba(255, 255, 255, 0.9);
  }

  .clay-inset {
    background: #EEF2F6;
    box-shadow: 
      inset 4px 4px 8px rgba(166, 175, 195, 0.3),
      inset -4px -4px 8px rgba(255, 255, 255, 0.8);
  }
  ```

### 2. Pustaka Komponen UI Tersedia (`frontend/src/components/ui/`)

#### A. `ClayButton.vue`
Tombol interaktif dengan efek *press-in* fisik:
```html
<script setup>
import ClayButton from '@/components/ui/ClayButton.vue'
import { BookOpen } from 'lucide-vue-next'
</script>

<template>
  <!-- Varian: blue, red, slate, ghost, outline -->
  <!-- Ukuran: xs, sm, md, lg -->
  <ClayButton variant="blue" size="md" @click="handleAction">
    <BookOpen class="w-4 h-4 mr-2" />
    Baca E-Book Sekarang
  </ClayButton>
</template>
```

#### B. `ClayBadge.vue`
Pill status dengan warna dinamis:
```html
<ClayBadge variant="blue" size="sm">E-Book PDF</ClayBadge>
<ClayBadge variant="emerald" size="sm">Tersedia di Rak</ClayBadge>
<ClayBadge variant="red" size="sm">Stok Dipinjam</ClayBadge>
<ClayBadge variant="amber" size="sm">Jatuh Tempo H-2</ClayBadge>
```

#### C. `ClayCard.vue`
Kontainer kartu taktil serbaguna:
```html
<ClayCard class="p-6 space-y-4">
  <h3 class="font-bold text-slate-800">Statistik Sirkulasi</h3>
  <p class="text-xs text-slate-500">Ringkasan aktivitas minggu ini.</p>
</ClayCard>
```

### 3. Pembuatan Halaman Baru (*Views*) & Routing
1. Buat file tampilan baru di `frontend/src/views/NamaHalamanView.vue`.
2. Buka `frontend/src/router/index.js` dan daftarkan rute baru:
   ```javascript
   {
     path: '/nama-halaman',
     name: 'nama-halaman',
     component: () => import('../views/NamaHalamanView.vue'),
     meta: {
       title: 'Nama Halaman — DIGIPUS Digitech University',
       requiresAuth: false
     }
   }
   ```
3. Tambahkan tautan menu di `frontend/src/App.vue` bila diperlukan di bilah navigasi utama atau navigasi bawah mobile.

---

## 7. PENGUJIAN, DEPLOYMENT & PEMELIHARAAN SISTEM

### Pengujian Otomatis (Automated Testing)
Jalankan pengujian unit dan fitur backend dengan PHPUnit:
```bash
php artisan test
```

Periksa kepatuhan gaya kode dengan Laravel Pint:
```bash
./vendor/bin/pint --test
```

### Checklist Deployment ke Server Produksi
1. **Konfigurasi Environment**:
   - Pastikan `APP_ENV=production` dan `APP_DEBUG=false`.
   - Ubah `APP_KEY` dan pastikan kata sandi database aman.
2. **Build Aset Frontend**:
   ```bash
   cd frontend
   npm run build
   ```
   *Hasil kompilasi file statis akan berada di `frontend/dist` atau dapat diarahkan ke direktori `public` Laravel.*
3. **Optimasi Cache Laravel**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
4. **Pembersihan & Reset Cache Izin Spatie**:
   Jika Anda mengubah relasi izin pada database produksi, selalu jalankan:
   ```bash
   php artisan permission:cache-reset
   ```
5. **Penjadwalan Otomatis (Cron Job)**:
   Tambahkan entri crontab di server untuk menjalankan kalkulasi keterlambatan dan denda harian secara berkala:
   ```bash
   * * * * * cd /path/to/e-perpus-utd && php artisan schedule:run >> /dev/null 2>&1
   ```

---

*Dokumen ini dikelola secara berkala oleh Tim Pengembang Sistem Informasi & UPT Perpustakaan Terpadu Universitas Teknologi Digital (Digitech University).*
