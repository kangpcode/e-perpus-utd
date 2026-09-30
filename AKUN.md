# 🔐 DAFTAR AKUN PENGGUNA, ROLE & HAK AKSES SISTEM
## E-Perpustakaan Terpadu (Digitech University - UTD)

Dokumen ini memuat seluruh kredensial akun pengguna pra-konfigurasi (*seeded accounts*), arsitektur peran (*Role-Based Access Control / RBAC*), matriks izin (*permissions*), kuota sirkulasi, serta panduan pengujian autentikasi pada platform **E-Perpustakaan Terpadu**.

---

## 📋 1. Tabel Kredensial Akun & Peran Pengguna

Seluruh akun default di bawah ini telah terdaftar di database dan siap digunakan untuk pengujian login pada antarmuka web maupun pengujian endpoint REST API Sanctum:

| No | Peran (*Role*) | Nama Lengkap | Email Login | Password Default | NIM / NIDN / NIP | Fakultas / Unit Kerja | Kuota Pinjam |
|:--:|:---|:---|:---|:---|:---|:---|:--:|
| 1 | **Admin** | Bima Administrator, S.Kom. | `admin@digitech.ac.id` | `password` | `ADM-SYS-01` | Biro Sistem Informasi & Teknologi | **99** |
| 2 | **Admin** | David Pratama, M.Kom. | `it.support@digitech.ac.id` | `password` | `ADM-SYS-02` | Divisi Keamanan & Database | **99** |
| 3 | **Pustakawan** | Siti Rahmawati, S.Sos., M.I.Kom. | `pustakawan@digitech.ac.id` | `password` | `NIP: 19880415201201` | UPT Perpustakaan (Kepala Sirkulasi) | **99** |
| 4 | **Pustakawan** | Ahmad Fauzi, A.Md. | `sirkulasi@digitech.ac.id` | `password` | `NIP: 19940822201902` | UPT Perpustakaan (Staff Meja Sirkulasi) | **50** |
| 5 | **Dosen** | Dr. Hendra Gunawan, S.Kom., M.T. | `dosen@digitech.ac.id` | `password` | `NIDN: 0412097801` | Fasilkom - Teknik Informatika | **15** |
| 6 | **Dosen** | Dr. Ir. Rian Setiawan, S.E., M.M. | `dosen.bisnis@digitech.ac.id` | `password` | `NIDN: 0405108202` | FEB - Bisnis Digital & FinTech | **15** |
| 7 | **Mahasiswa** | Rafi Pratama | `mahasiswa@digitech.ac.id` | `password` | `NIM: 22010884` | Fasilkom - Teknik Informatika (S1) | **5** |
| 8 | **Mahasiswa** | Anisa Putri Ramadhani | `mahasiswa2@digitech.ac.id` | `password` | `NIM: 23020119` | FDKI - Desain Komunikasi Visual (S1) | **5** |
| 9 | **Tamu** | Surya Wijaya (Peneliti Eksternal) | `tamu@digitech.ac.id` | `password` | `GUEST-2026-001` | Komunitas Riset / Eksternal | **0** |
| 10 | **Tamu** | Pengunjung Umum | `guest@digitech.ac.id` | `password` | `GUEST-PUBLIC` | Publik / Calon Mahasiswa | **0** |

> [!NOTE]
> Semua akun menggunakan password default: **`password`**. Pada tahap implementasi server produksi, seluruh pengguna wajib diarahkan untuk memperbarui kata sandi mandiri melalui fitur ubah kata sandi.

---

## 🛡️ 2. Detail Karakteristik Peran (*Role Profiles*)

### 1. `admin` (Super Administrator & Tim IT)
- **Tujuan**: Memegang otoritas penuh terhadap tata kelola sistem, manajemen hak akses seluruh pengguna, pemeliharaan data induk katalog perpustakaan, audit aktivitas, serta konfigurasi integrasi server dan cache.
- **Akses Kunci**:
  - Akses penuh terhadap seluruh 24 permissions Spatie.
  - Tambah, edit, dan hapus data buku fisik serta e-book.
  - Akses dan reset data pengguna dan penetapan role.
  - Monitoring metrik sistem (cache Redis, indexing Meilisearch, statistik sirkulasi).

### 2. `pustakawan` (Staff Layanan Sirkulasi & Pengadaan Koleksi)
- **Tujuan**: Menjalankan operasional harian perpustakaan terpadu kampus, pengelolaan barcode/RFID fisik eksemplar, validasi transaksi peminjaman & pengembalian, verifikasi pengembalian, serta kurasi ulasan buku.
- **Akses Kunci**:
  - Manajemen katalog buku: penambahan judul baru, penerbit, pengarang, dan penomoran ISBN.
  - Manajemen eksemplar fisik (*book copies*): register barcode dan pemantauan kondisi buku (*good, damaged, lost*).
  - Meja loket sirkulasi: verifikasi peminjaman, perpanjangan, serta pengembalian buku anggota.
  - Moderasi ulasan dan rating civitas akademika.

### 3. `dosen` (Tenaga Pengajar & Peneliti Sivitas Akademika)
- **Tujuan**: Menyediakan akses prioritas terhadap referensi ilmiah dan bahan ajar perkuliahan untuk mendukung Tridharma Perguruan Tinggi tanpa hambatan kuota yang sempit.
- **Akses Kunci**:
  - Kuota peminjaman hingga **15 buku** fisik secara bersamaan.
  - Durasi peminjaman lebih panjang dengan prioritas perpanjangan hingga 3 kali.
  - Akses membaca e-book lengkap dan repositori prosiding/jurnal terakreditasi kampus.
  - Pemberian ulasan serta penyusunan *reading list* rujukan perkuliahan.

### 4. `mahasiswa` (Mahasiswa Aktif Sivitas Akademika)
- **Tujuan**: Peminjaman mandiri (*self-service circulation*), eksplorasi bahan studi kuliah, dan membaca e-book interaktif.
- **Akses Kunci**:
  - Kuota peminjaman reguler hingga **5 buku** fisik secara bersamaan.
  - Fitur perpanjangan masa pinjam mandiri (*self-extend*) hingga 2 kali sebelum jatuh tempo.
  - Pembaca e-book online terintegrasi (*E-Reader*) dengan sistem proteksi *watermark DRM*.
  - Penyimpanan koleksi ke dalam Rak Buku Favorit (*Wishlist* / *Bookshelf*).
  - Memberikan ulasan dan rating bintang (1 - 5) untuk buku yang telah dibaca.

### 5. `tamu` (Pengunjung Umum & Peneliti Tamu Luar)
- **Tujuan**: Menjamin keterbukaan informasi akademik kampus bagi masyarakat luas sekaligus ramah mesin pencari (*SEO friendly*).
- **Akses Kunci**:
  - Menjelajahi seluruh katalog repositori publik tanpa batasan.
  - Memeriksa ketersediaan stok eksemplar fisik di perpustakaan kampus secara real-time.
  - Membaca pratinjau sampel bab pertama (*sample preview*) e-book secara legal.
  - Tidak memiliki izin melakukan peminjaman buku fisik atau unduhan dokumen berlisensi internal.

---

## 🔑 3. Matriks Izin Spatie (*Permissions Matrix*)

Platform mengimplementasikan arsitektur keamanan **Spatie Laravel Permission** dengan pembagian 24 hak akses modular:

| Modul | Nama Permission Spatie | Deskripsi Hak Akses | Admin | Pustakawan | Dosen | Mahasiswa | Tamu |
|:---|:---|:---|:---:|:---:|:---:|:---:|:---:|
| **Katalog Buku** | `books.view` | Melihat daftar katalog buku | ✅ | ✅ | ✅ | ✅ | ✅ |
| | `books.create` | Menambahkan koleksi buku baru | ✅ | ✅ | ❌ | ❌ | ❌ |
| | `books.edit` | Mengubah informasi buku & rak | ✅ | ✅ | ❌ | ❌ | ❌ |
| | `books.delete` | Menghapus koleksi buku dari sistem | ✅ | ✅ | ❌ | ❌ | ❌ |
| | `books.manage_copies`| Mengelola kode barcode eksemplar | ✅ | ✅ | ❌ | ❌ | ❌ |
| **E-Book & Repositori** | `ebooks.read_sample` | Membaca cuplikan bab sampel publik | ✅ | ✅ | ✅ | ✅ | ✅ |
| | `ebooks.read_full` | Membaca teks e-book lengkap di web | ✅ | ✅ | ✅ | ✅ | ❌ |
| | `ebooks.download` | Mengunduh berkas e-book akademik | ✅ | ✅ | ✅ | ❌ | ❌ |
| | `ebooks.upload` | Mengunggah modul/artikel ilmiah | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Sirkulasi** | `loans.view_own` | Melihat status pinjaman sendiri | ✅ | ✅ | ✅ | ✅ | ❌ |
| | `loans.borrow` | Mengajukan peminjaman buku | ✅ | ✅ | ✅ | ✅ | ❌ |
| | `loans.extend` | Memperpanjang masa pinjaman | ✅ | ✅ | ✅ | ✅ | ❌ |
| | `loans.return` | Mengembalikan buku yang dipinjam | ✅ | ✅ | ✅ | ✅ | ❌ |
| | `loans.manage` | Memproses sirkulasi seluruh anggota | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Ulasan & Rating** | `reviews.create` | Memberikan review & bintang | ✅ | ✅ | ✅ | ✅ | ❌ |
| | `reviews.moderate` | Menghapus atau menyaring ulasan | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Manajemen Pengguna** | `users.view` | Melihat daftar anggota perpustakaan | ✅ | ✅ | ❌ | ❌ | ❌ |
| | `users.create` | Mendaftarkan akun anggota baru | ✅ | ✅ | ❌ | ❌ | ❌ |
| | `users.edit` | Memperbarui profil dan kuota pinjam | ✅ | ✅ | ❌ | ❌ | ❌ |
| | `users.delete` | Menonaktifkan akun pengguna | ✅ | ❌ | ❌ | ❌ | ❌ |
| | `roles.manage` | Mengatur penetapan peran & izin | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Laporan & Sistem** | `reports.view` | Mengakses dasbor analitik sirkulasi | ✅ | ✅ | ✅ | ❌ | ❌ |
| | `reports.export` | Mengekspor rekapitulasi laporan | ✅ | ✅ | ❌ | ❌ | ❌ |
| | `settings.manage` | Mengatur parameter operasional | ✅ | ❌ | ❌ | ❌ | ❌ |

---

## 💻 4. Panduan Pengujian Autentikasi

### A. Melalui Tampilan Web (Frontend Simulator)
1. Buka aplikasi web di browser pada `http://localhost:5173` atau port server aktif.
2. Navigasikan ke menu **Simulasi Role** di navigasi utama atau kunjungi rute `/#/roles`.
3. Klik salah satu pill role (**Super Admin**, **Pustakawan**, **Dosen**, **Mahasiswa**, atau **Tamu**).
4. Klik tombol **Terapkan Role ke Sesi Ini**.
5. Sistem secara otomatis melakukan autentikasi API ke `/api/auth/login`, menerbitkan token Sanctum, dan memperbarui header akun dengan data pengguna terpilih beserta permissions yang dimilikinya.

### B. Melalui REST API (cURL / Postman / Fetch)

#### 1. Endpoint Login: `POST /api/auth/login`
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "pustakawan@digitech.ac.id",
    "password": "password"
  }'
```

**Contoh Response Sukses (200 OK):**
```json
{
  "message": "Login berhasil",
  "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
  "user": {
    "id": 3,
    "name": "Siti Rahmawati, S.Sos., M.I.Kom.",
    "email": "pustakawan@digitech.ac.id",
    "nim_nidn": "NIP: 19880415201201",
    "faculty": "UPT Perpustakaan Terpadu",
    "major": "Kepala Layanan Sirkulasi & Koleksi",
    "max_borrow_quota": 99,
    "current_borrowed": 0,
    "roles": ["pustakawan"],
    "permissions": [
      "books.view",
      "books.create",
      "books.edit",
      "books.delete",
      "books.manage_copies",
      "ebooks.read_sample",
      "ebooks.read_full",
      "ebooks.download",
      "ebooks.upload",
      "loans.view_own",
      "loans.borrow",
      "loans.extend",
      "loans.return",
      "loans.manage",
      "reviews.create",
      "reviews.moderate",
      "users.view",
      "users.create",
      "users.edit",
      "reports.view",
      "reports.export"
    ]
  }
}
```

#### 2. Endpoint Profil Pengguna: `GET /api/auth/me`
```bash
curl -X GET http://127.0.0.1:8000/api/auth/me \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <GANTI_DENGAN_TOKEN>"
```

#### 3. Endpoint Logout: `POST /api/auth/logout`
```bash
curl -X POST http://127.0.0.1:8000/api/auth/logout \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <GANTI_DENGAN_TOKEN>"
```

---

## 🔄 5. Perintah Sinkronisasi Ulang Database & Seeder

Bila ingin mereset dan memulihkan seluruh data akun ke kondisi default awal:
```bash
php artisan migrate:fresh --seed
```
Atau hanya memperbarui izin, role, dan akun tanpa menghapus tabel:
```bash
php artisan db:seed
```
