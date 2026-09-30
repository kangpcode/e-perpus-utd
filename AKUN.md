# 🔐 AKUN PENGGUNA, PERAN & HAK AKSES
## DIGIPUS — E-Perpustakaan Terpadu Digitech University (UTD)

> Dokumen ini memuat seluruh kredensial akun pengguna pra-konfigurasi (*seeded accounts*),
> arsitektur peran RBAC, matriks izin granular Spatie, kuota sirkulasi, serta panduan
> lengkap pengujian autentikasi platform DIGIPUS.

---

## 🗂️ DAFTAR ISI

1. [Kredensial Akun Login](#1-kredensial-akun-login)
2. [Profil & Karakteristik Setiap Role](#2-profil--karakteristik-setiap-role)
3. [Matriks Izin Lengkap (Spatie Permissions)](#3-matriks-izin-lengkap-spatie-permissions)
4. [Kuota & Batas Sirkulasi per Role](#4-kuota--batas-sirkulasi-per-role)
5. [Panduan Pengujian Autentikasi](#5-panduan-pengujian-autentikasi)
6. [Contoh Response API Lengkap](#6-contoh-response-api-lengkap)
7. [Perintah Database & Sinkronisasi](#7-perintah-database--sinkronisasi)

---

## 1. Kredensial Akun Login

> [!NOTE]
> Semua akun menggunakan password default: **`password`**
> Server API berjalan di `http://127.0.0.1:8000` · Database: **MariaDB** `db_digipus`

### 👑 Role: ADMIN (Super Administrator)

| Field | Akun 1 | Akun 2 |
|:---|:---|:---|
| **Nama** | Bima Administrator, S.Kom. | David Pratama, M.Kom. |
| **Email** | `admin@digitech.ac.id` | `it.support@digitech.ac.id` |
| **Password** | `password` | `password` |
| **ID / Kode** | `ADM-SYS-01` | `ADM-SYS-02` |
| **Unit Kerja** | Biro Sistem Informasi & Teknologi | Divisi Keamanan Jaringan & Database |
| **Kuota Pinjam** | **99 buku** | **99 buku** |
| **Permissions** | Semua 24 permissions | Semua 24 permissions |

---

### 📚 Role: PUSTAKAWAN (Staff Layanan Perpustakaan)

| Field | Akun 1 | Akun 2 |
|:---|:---|:---|
| **Nama** | Siti Rahmawati, S.Sos., M.I.Kom. | Ahmad Fauzi, A.Md. |
| **Email** | `pustakawan@digitech.ac.id` | `sirkulasi@digitech.ac.id` |
| **Password** | `password` | `password` |
| **NIP** | `NIP: 19880415201201` | `NIP: 19940822201902` |
| **Jabatan** | Kepala Layanan Sirkulasi & Koleksi | Staff Meja Sirkulasi & Barcode |
| **Unit Kerja** | UPT Perpustakaan Terpadu | UPT Perpustakaan Terpadu |
| **Kuota Pinjam** | **99 buku** | **50 buku** |
| **Permissions** | 21 permissions (tanpa: `users.delete`, `roles.manage`, `settings.manage`) | 21 permissions |

---

### 🎓 Role: DOSEN (Tenaga Pengajar & Peneliti)

| Field | Akun 1 | Akun 2 |
|:---|:---|:---|
| **Nama** | Dr. Hendra Gunawan, S.Kom., M.T. | Dr. Ir. Rian Setiawan, S.E., M.M. |
| **Email** | `dosen@digitech.ac.id` | `dosen.bisnis@digitech.ac.id` |
| **Password** | `password` | `password` |
| **NIDN** | `NIDN: 0412097801` | `NIDN: 0405108202` |
| **Prodi** | Teknik Informatika (S1) | Bisnis Digital & FinTech (S1) |
| **Fakultas** | Fakultas Ilmu Komputer | Fakultas Ekonomi & Bisnis Digital |
| **Kuota Pinjam** | **15 buku** | **15 buku** |
| **Permissions** | 11 permissions | 11 permissions |

---

### 🧑‍🎓 Role: MAHASISWA (Sivitas Akademika)

| Field | Akun 1 | Akun 2 |
|:---|:---|:---|
| **Nama** | Rafi Pratama | Anisa Putri Ramadhani |
| **Email** | `mahasiswa@digitech.ac.id` | `mahasiswa2@digitech.ac.id` |
| **Password** | `password` | `password` |
| **NIM** | `NIM: 22010884` | `NIM: 23020119` |
| **Prodi** | Teknik Informatika (S1) | Desain Komunikasi Visual (S1) |
| **Fakultas** | Fakultas Ilmu Komputer | Fakultas Desain & Industri Kreatif |
| **Kuota Pinjam** | **5 buku** | **5 buku** |
| **Permissions** | 8 permissions | 8 permissions |

---

### 🌐 Role: TAMU (Pengunjung Publik / Peneliti Eksternal)

| Field | Akun 1 | Akun 2 |
|:---|:---|:---|
| **Nama** | Surya Wijaya (Peneliti Eksternal) | Pengunjung Umum |
| **Email** | `tamu@digitech.ac.id` | `guest@digitech.ac.id` |
| **Password** | `password` | `password` |
| **Kode** | `GUEST-2026-001` | `GUEST-PUBLIC` |
| **Afiliasi** | Komunitas Riset / Eksternal | Publik / Calon Mahasiswa |
| **Kuota Pinjam** | **0 buku** | **0 buku** |
| **Permissions** | 2 permissions | 2 permissions |

---

## 2. Profil & Karakteristik Setiap Role

### 🔴 `admin` — Super Administrator
Memegang otoritas penuh terhadap seluruh sistem. Dapat mengelola role & permission semua pengguna, mengonfigurasi kebijakan sirkulasi (durasi pinjam, denda), memantau statistik global, dan mengakses audit log aktivitas.

**Kemampuan Eksklusif:**
- Hapus akun pengguna (`users.delete`)
- Atur penetapan role Spatie (`roles.manage`)
- Konfigurasi parameter operasional sistem (`settings.manage`)
- Akses seluruh endpoint API tanpa pembatasan

---

### 🔵 `pustakawan` — Staff Layanan Perpustakaan
Menjalankan operasional harian: manajemen barcode/RFID eksemplar fisik, proses peminjaman loket, verifikasi pengembalian, kurasi ulasan, dan approval pengadaan koleksi.

**Kemampuan Utama:**
- Tambah/edit/hapus koleksi buku & e-book
- Kelola eksemplar fisik (barcode, kondisi, rak)
- Proses sirkulasi seluruh anggota (`loans.manage`)
- Moderasi ulasan & rating civitas
- Ekspor laporan sirkulasi

---

### 🟡 `dosen` — Tenaga Pengajar & Peneliti
Akses prioritas koleksi akademik dengan kuota lebih besar, dapat membaca/mengunduh e-book lengkap, dan mengunggah materi riset ke repositori kampus.

**Kemampuan Utama:**
- Kuota pinjam fisik hingga 15 buku
- Baca e-book full + unduh berkas
- Unggah modul perkuliahan / artikel ilmiah
- Perpanjang pinjaman hingga 3x
- Akses laporan koleksi untuk keperluan riset

---

### 🟢 `mahasiswa` — Sivitas Akademika Aktif
Peminjaman mandiri (self-checkout) buku fisik, baca e-book online via e-reader terintegrasi, simpan koleksi ke rak favorit, dan berikan ulasan.

**Kemampuan Utama:**
- Pinjam hingga 5 buku fisik bersamaan
- Baca e-book online (DRM watermark)
- Perpanjang pinjaman mandiri hingga 2x
- Wishlist / Rak Buku Favorit
- Beri ulasan & rating bintang 1–5

---

### ⚫ `tamu` — Pengunjung Publik / Eksternal
Akses baca-saja katalog publik, cocok untuk optimasi mesin pencari (SEO) dan referensi masyarakat luas tanpa perlu login khusus.

**Kemampuan Utama:**
- Jelajah seluruh katalog repositori
- Lihat status ketersediaan stok buku fisik
- Baca cuplikan sampel bab pertama e-book
- ❌ Tidak dapat meminjam atau mengunduh

---

## 3. Matriks Izin Lengkap (Spatie Permissions)

Platform mengimplementasikan **24 hak akses granular** yang dibagi dalam 6 modul:

| Modul | Permission | Deskripsi | Admin | Pustakawan | Dosen | Mahasiswa | Tamu |
|:---:|:---|:---|:---:|:---:|:---:|:---:|:---:|
| **Katalog** | `books.view` | Lihat daftar & detail buku | ✅ | ✅ | ✅ | ✅ | ✅ |
| | `books.create` | Tambah koleksi buku baru | ✅ | ✅ | ❌ | ❌ | ❌ |
| | `books.edit` | Edit info buku & rak | ✅ | ✅ | ❌ | ❌ | ❌ |
| | `books.delete` | Hapus koleksi dari sistem | ✅ | ✅ | ❌ | ❌ | ❌ |
| | `books.manage_copies` | Kelola barcode eksemplar | ✅ | ✅ | ❌ | ❌ | ❌ |
| **E-Book** | `ebooks.read_sample` | Baca cuplikan bab publik | ✅ | ✅ | ✅ | ✅ | ✅ |
| | `ebooks.read_full` | Baca e-book lengkap online | ✅ | ✅ | ✅ | ✅ | ❌ |
| | `ebooks.download` | Unduh berkas e-book | ✅ | ✅ | ✅ | ❌ | ❌ |
| | `ebooks.upload` | Unggah modul/artikel | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Sirkulasi** | `loans.view_own` | Lihat pinjaman sendiri | ✅ | ✅ | ✅ | ✅ | ❌ |
| | `loans.borrow` | Ajukan peminjaman | ✅ | ✅ | ✅ | ✅ | ❌ |
| | `loans.extend` | Perpanjang masa pinjam | ✅ | ✅ | ✅ | ✅ | ❌ |
| | `loans.return` | Kembalikan buku | ✅ | ✅ | ✅ | ✅ | ❌ |
| | `loans.manage` | Proses sirkulasi semua anggota | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Ulasan** | `reviews.create` | Beri ulasan & bintang | ✅ | ✅ | ✅ | ✅ | ❌ |
| | `reviews.moderate` | Hapus/filter ulasan | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Pengguna** | `users.view` | Lihat daftar anggota | ✅ | ✅ | ❌ | ❌ | ❌ |
| | `users.create` | Daftarkan anggota baru | ✅ | ✅ | ❌ | ❌ | ❌ |
| | `users.edit` | Edit profil & kuota pinjam | ✅ | ✅ | ❌ | ❌ | ❌ |
| | `users.delete` | Nonaktifkan akun pengguna | ✅ | ❌ | ❌ | ❌ | ❌ |
| | `roles.manage` | Atur penetapan role & izin | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Sistem** | `reports.view` | Akses dasbor analitik | ✅ | ✅ | ✅ | ❌ | ❌ |
| | `reports.export` | Ekspor rekap laporan | ✅ | ✅ | ❌ | ❌ | ❌ |
| | `settings.manage` | Konfigurasi parameter sistem | ✅ | ❌ | ❌ | ❌ | ❌ |

**Ringkasan jumlah izin per role:**

| Role | Jumlah Permissions |
|:---|:---:|
| `admin` | **24 / 24** |
| `pustakawan` | **21 / 24** |
| `dosen` | **11 / 24** |
| `mahasiswa` | **8 / 24** |
| `tamu` | **2 / 24** |

---

## 4. Kuota & Batas Sirkulasi per Role

| Role | Maks. Pinjam Bersamaan | Durasi Default | Maks. Perpanjangan | Prioritas Antrean |
|:---|:---:|:---:|:---:|:---:|
| `admin` | **99 buku** | 30 hari | Tak terbatas | ⭐⭐⭐ |
| `pustakawan` | **99 / 50 buku** | 30 hari | Tak terbatas | ⭐⭐⭐ |
| `dosen` | **15 buku** | 30 hari | 3× (+7 hari/perpanjangan) | ⭐⭐ |
| `mahasiswa` | **5 buku** | 14 hari | 2× (+7 hari/perpanjangan) | ⭐ |
| `tamu` | **0 buku** | — | — | — |

---

## 5. Panduan Pengujian Autentikasi

### A. Via Simulator Role di Web (Frontend)

1. Buka `http://localhost:8000` di browser.
2. Navigasikan ke menu **⚙️ Role** (ikon perisai di bottom nav).
3. Klik salah satu pill: **Super Admin · Pustakawan · Dosen · Mahasiswa · Tamu**.
4. Klik tombol **"Terapkan Role ke Sesi Ini"**.
5. Sistem otomatis login via `POST /api/auth/login` → simpan token Sanctum → perbarui header profil dan permissions sesi aktif.

---

### B. Via REST API — cURL / Postman

#### `POST /api/auth/login` — Login & Terima Token
```bash
# Contoh: Login sebagai Dosen
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "dosen@digitech.ac.id",
    "password": "password"
  }'
```

#### `GET /api/auth/me` — Profil Pengguna Aktif
```bash
curl -X GET http://127.0.0.1:8000/api/auth/me \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <TOKEN>"
```

#### `POST /api/auth/logout` — Cabut Token Aktif
```bash
curl -X POST http://127.0.0.1:8000/api/auth/logout \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <TOKEN>"
```

#### `GET /api/books` — Katalog Publik (Tanpa Login)
```bash
curl "http://127.0.0.1:8000/api/books?q=kecerdasan&category=ti&sort=popular&per_page=5"
```

#### `POST /api/loans/borrow` — Pinjam Buku (Login Diperlukan)
```bash
curl -X POST http://127.0.0.1:8000/api/loans/borrow \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <TOKEN>" \
  -d '{"book_id": 1}'
```

---

## 6. Contoh Response API Lengkap

### Response Login Sukses — Role `dosen`
```json
{
  "message": "Login berhasil",
  "token": "3|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
  "user": {
    "id": 5,
    "name": "Dr. Hendra Gunawan, S.Kom., M.T.",
    "email": "dosen@digitech.ac.id",
    "nim_nidn": "NIDN: 0412097801",
    "faculty": "Fakultas Ilmu Komputer",
    "major": "Teknik Informatika (S1)",
    "max_borrow_quota": 15,
    "current_borrowed": 1,
    "roles": ["dosen"],
    "permissions": [
      "books.view",
      "ebooks.read_sample",
      "ebooks.read_full",
      "ebooks.download",
      "ebooks.upload",
      "loans.view_own",
      "loans.borrow",
      "loans.extend",
      "loans.return",
      "reviews.create",
      "reports.view"
    ]
  }
}
```

### Response Login Sukses — Role `mahasiswa`
```json
{
  "message": "Login berhasil",
  "token": "4|yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy",
  "user": {
    "id": 4,
    "name": "Rafi Pratama",
    "email": "mahasiswa@digitech.ac.id",
    "nim_nidn": "NIM: 22010884",
    "faculty": "Fakultas Ilmu Komputer",
    "major": "Teknik Informatika (S1)",
    "max_borrow_quota": 5,
    "current_borrowed": 2,
    "roles": ["mahasiswa"],
    "permissions": [
      "books.view",
      "ebooks.read_sample",
      "ebooks.read_full",
      "loans.view_own",
      "loans.borrow",
      "loans.extend",
      "loans.return",
      "reviews.create"
    ]
  }
}
```

### Response Error — Kuota Pinjam Penuh
```json
{
  "message": "Batas kuota peminjaman Anda (5 buku) telah tercapai!"
}
```

### Response Error — Tidak Ada Izin (403 Forbidden)
```json
{
  "message": "User does not have the right roles."
}
```

---

## 7. Perintah Database & Sinkronisasi

### Reset & Seed Ulang Seluruh Data
```bash
php artisan migrate:fresh --seed
```

### Hanya Jalankan Seeder (Perbarui Permissions & Akun)
```bash
php artisan db:seed
```

### Reset Cache Izin Spatie (Wajib Setelah Ubah Permissions di Produksi)
```bash
php artisan permission:cache-reset
```

### Lihat Daftar User & Role via Tinker
```bash
php artisan tinker --execute="
  \App\Models\User::with('roles')->get()->each(function(\$u) {
    echo sprintf('[%s] %s — %s (Kuota: %d)\n',
      \$u->roles->pluck('name')->implode(','),
      \$u->name,
      \$u->email,
      \$u->max_borrow_quota
    );
  });
"
```

### Verifikasi Jumlah Permissions
```bash
php artisan tinker --execute="
  echo 'Users: ' . \App\Models\User::count() . PHP_EOL;
  echo 'Permissions: ' . \Spatie\Permission\Models\Permission::count() . PHP_EOL;
  echo 'Roles: ' . \Spatie\Permission\Models\Role::count() . PHP_EOL;
"
```

---

> [!CAUTION]
> Jangan gunakan password `password` di lingkungan **produksi**. Ganti semua password akun sebelum deploy ke server publik menggunakan perintah:
> ```bash
> php artisan tinker --execute="\App\Models\User::find(1)->update(['password' => bcrypt('kata_sandi_baru')]);"
> ```
