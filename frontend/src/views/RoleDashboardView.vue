<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-6 pb-20 md:pb-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">
          Simulasi Arsitektur 5 Role (RBAC)
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
          Sesuai spesifikasi <span class="font-mono text-blue-700">CLAUDE.md Bagian 4</span>: Eksplorasi hak akses dan fitur dashboard untuk tiap tingkatan pengguna.
        </p>
      </div>

      <!-- Active Role Pill -->
      <ClayBadge variant="red" size="md">
        Role Terpilih: {{ selectedRole.toUpperCase() }}
      </ClayBadge>
    </div>

    <!-- 5 Role Selection Clay Pills -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
      <button
        v-for="role in roles"
        :key="role.id"
        type="button"
        :class="[
          'clay-card p-3.5 sm:p-4 rounded-2xl flex flex-col items-center justify-center text-center gap-2 transition-all select-none active:scale-95 border',
          selectedRole === role.id
            ? 'border-blue-600 bg-blue-50/50 shadow-md ring-2 ring-blue-500/30'
            : 'border-white hover:border-slate-200'
        ]"
        @click="switchRole(role.id)"
      >
        <div
          :class="[
            'w-10 h-10 rounded-xl flex items-center justify-center shadow-inner',
            selectedRole === role.id ? 'bg-[#1E4FA3] text-white' : 'bg-slate-100 text-slate-600'
          ]"
        >
          <component :is="role.icon" class="w-5 h-5" />
        </div>
        <div>
          <div class="text-xs font-bold text-slate-800 leading-tight">
            {{ role.title }}
          </div>
          <div class="text-[10px] text-slate-400 mt-0.5">
            {{ role.badge }}
          </div>
        </div>
      </button>
    </div>

    <!-- ROLE DETAILS & SIMULATED DASHBOARD VIEW -->
    <div class="clay-card p-6 sm:p-8 space-y-6 border border-white">
      <!-- Role Title & Scope -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
        <div class="space-y-1">
          <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">
              DOKUMENTASI RBAC SPATIE
            </span>
            <ClayBadge :variant="activeRoleData.badgeVariant" size="xs">
              {{ activeRoleData.permissionLevel }}
            </ClayBadge>
          </div>
          <h2 class="text-xl sm:text-2xl font-bold font-heading text-slate-800">
            {{ activeRoleData.title }} Dashboard
          </h2>
          <p class="text-xs text-slate-500">
            {{ activeRoleData.description }}
          </p>
        </div>

        <ClayButton
          variant="blue"
          size="sm"
          @click="applyCurrentRole"
        >
          Terapkan Role ke Sesi Ini
        </ClayButton>
      </div>

      <!-- Feature Matrix Cards for this Role -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div
          v-for="(feature, idx) in activeRoleData.features"
          :key="idx"
          class="clay-inset p-4 rounded-2xl space-y-2"
        >
          <div class="flex items-center gap-2 text-slate-800 font-bold text-xs sm:text-sm">
            <CheckCircle2 class="w-4 h-4 text-emerald-600 shrink-0" />
            <span>{{ feature.title }}</span>
          </div>
          <p class="text-[11px] text-slate-500 leading-relaxed pl-6">
            {{ feature.desc }}
          </p>
        </div>
      </div>

      <!-- Simulated Dashboard Widget based on Role -->
      <div class="mt-6 pt-6 border-t border-slate-100">
        <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">
          Pratinjau Widget Khusus: {{ activeRoleData.title }}
        </div>

        <!-- Super Admin Widget -->
        <div v-if="selectedRole === 'admin'" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="clay-card p-4 rounded-2xl bg-slate-900 text-white">
              <div class="text-[11px] text-slate-400">Total Pengguna Terdaftar</div>
              <div class="text-2xl font-extrabold font-heading text-blue-400 mt-1">4,892 User</div>
              <div class="text-[10px] text-emerald-400 mt-1">↑ +142 mahasiswa baru pekan ini</div>
            </div>
            <div class="clay-card p-4 rounded-2xl bg-slate-900 text-white">
              <div class="text-[11px] text-slate-400">Status Server Repositori</div>
              <div class="text-2xl font-extrabold font-heading text-emerald-400 mt-1">Normal (99.9%)</div>
              <div class="text-[10px] text-slate-400 mt-1">Redis Cache: 2.1 GB / 8 GB</div>
            </div>
            <div class="clay-card p-4 rounded-2xl bg-slate-900 text-white">
              <div class="text-[11px] text-slate-400">Indeks SEO Meilisearch</div>
              <div class="text-2xl font-extrabold font-heading text-amber-400 mt-1">24,680 Dokumen</div>
              <div class="text-[10px] text-slate-400 mt-1">Sitemap ter-update otomatis</div>
            </div>
          </div>

          <div class="flex items-center gap-3 pt-2">
            <ClayButton variant="red" size="sm" @click="showAddBookModal = true">
              <template #leading><BookPlus class="w-4 h-4" /></template>
              + Tambah Koleksi Baru
            </ClayButton>
            <ClayButton variant="white" size="sm" @click="flushCache">
              <template #leading><RefreshCw class="w-4 h-4 text-blue-600" /></template>
              Bersihkan Cache Redis
            </ClayButton>
          </div>
        </div>

        <!-- Pustakawan Widget -->
        <div v-else-if="selectedRole === 'pustakawan'" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="clay-inset p-4 rounded-2xl">
              <div class="text-xs text-slate-500 font-medium">Sirkulasi Menunggu Pengembalian</div>
              <div class="text-2xl font-bold text-red-600 mt-1">18 Eksemplar</div>
              <div class="text-[11px] text-slate-400 mt-1">3 melewati batas jatuh tempo</div>
            </div>
            <div class="clay-inset p-4 rounded-2xl">
              <div class="text-xs text-slate-500 font-medium">Usulan Pengadaan Buku Baru</div>
              <div class="text-2xl font-bold text-blue-600 mt-1">7 Usulan Dosen</div>
              <div class="text-[11px] text-slate-400 mt-1">Menunggu approval pustakawan</div>
            </div>
            <div class="clay-inset p-4 rounded-2xl">
              <div class="text-xs text-slate-500 font-medium">Scan Barcode Cepat (Kiosk)</div>
              <div class="text-sm font-bold text-slate-800 mt-2">Kamera Scanner Siap</div>
              <div class="text-[11px] text-emerald-600 mt-1">Integrasi RFID / Barcode OK</div>
            </div>
          </div>

          <div class="flex items-center gap-3 pt-2">
            <ClayButton variant="blue" size="sm" @click="showAddBookModal = true">
              <template #leading><BookPlus class="w-4 h-4" /></template>
              + Input Koleksi Baru
            </ClayButton>
            <ClayButton variant="white" size="sm" @click="showScannerModal = true">
              <template #leading><ScanLine class="w-4 h-4 text-red-600" /></template>
              Buka Scanner Barcode
            </ClayButton>
          </div>
        </div>

        <!-- Dosen Widget -->
        <div v-else-if="selectedRole === 'dosen'" class="space-y-4">
          <div class="clay-inset p-5 rounded-2xl space-y-3">
            <div class="flex items-center justify-between">
              <h4 class="font-bold text-sm text-slate-800">Reading List Mata Kuliah: Arsitektur Perangkat Lunak (IF-402)</h4>
              <ClayBadge variant="blue" size="xs">4 Buku Rujukan Ditetapkan</ClayBadge>
            </div>
            <p class="text-xs text-slate-500">
              Mahasiswa kelas Anda secara otomatis menerima notifikasi prioritas peminjaman dan akses instan e-book yang ada di reading list ini.
            </p>
            <div class="pt-2">
              <ClayButton variant="blue" size="sm" @click="requestNewBookProposal">
                <template #leading><BookOpen class="w-4 h-4" /></template>
                + Usulkan Pengadaan Bahan Ajar Baru
              </ClayButton>
            </div>
          </div>
        </div>

        <!-- Mahasiswa Widget -->
        <div v-else-if="selectedRole === 'mahasiswa'" class="space-y-4">
          <div class="clay-inset p-5 rounded-2xl space-y-3">
            <div class="flex items-center justify-between">
              <h4 class="font-bold text-sm text-slate-800">Status Kartu Anggota Digital Mahasiswa</h4>
              <ClayBadge variant="emerald" size="xs">Aktif Semester Ganjil 2026</ClayBadge>
            </div>
            <p class="text-xs text-slate-500">
              NIM: 22010884 • Kuota Pinjam Tersedia: 3 Buku Fisik & E-Book Tidak Terbatas.
            </p>
          </div>
        </div>

        <!-- Tamu / Publik Widget -->
        <div v-else class="space-y-4">
          <div class="clay-card p-6 bg-gradient-to-r from-red-50 to-blue-50 border border-red-100 rounded-2xl text-center space-y-3">
            <h4 class="font-bold text-base text-slate-800">Akses Publik & Pengunjung Umum</h4>
            <p class="text-xs text-slate-600 max-w-lg mx-auto">
              Anda dapat menjelajahi seluruh katalog repositori akademik Digitech University untuk keperluan sitasi. Masuk menggunakan akun SSO kampus untuk menikmati peminjaman penuh dan e-reader.
            </p>
            <div>
              <ClayButton variant="red" size="sm" @click="switchRole('mahasiswa')">
                Login Sebagai Mahasiswa
              </ClayButton>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals for Role Actions -->
    <BookAddModal
      :is-open="showAddBookModal"
      @close="showAddBookModal = false"
    />

    <BarcodeScannerModal
      :is-open="showScannerModal"
      @close="showScannerModal = false"
    />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useLibraryStore } from '../stores/library'
import ClayBadge from '../components/ui/ClayBadge.vue'
import ClayButton from '../components/ui/ClayButton.vue'
import BookAddModal from '../components/books/BookAddModal.vue'
import BarcodeScannerModal from '../components/books/BarcodeScannerModal.vue'
import {
  ShieldAlert,
  BookMarked,
  GraduationCap,
  User,
  Globe,
  CheckCircle2,
  BookPlus,
  ScanLine,
  RefreshCw,
  BookOpen
} from 'lucide-vue-next'

const libraryStore = useLibraryStore()
const selectedRole = ref(libraryStore.currentRole)
const showAddBookModal = ref(false)
const showScannerModal = ref(false)

function flushCache() {
  libraryStore.showToast('Cache Redis & Indeks Meilisearch berhasil dibersihkan!', 'success')
}

function requestNewBookProposal() {
  libraryStore.showToast('Form Usulan Bahan Ajar baru telah dikirimkan ke UPT Perpustakaan!', 'success')
}

const roles = [
  { id: 'admin', title: 'Super Admin', badge: 'Full Access', icon: ShieldAlert },
  { id: 'pustakawan', title: 'Pustakawan', badge: 'Sirkulasi & Stok', icon: BookMarked },
  { id: 'dosen', title: 'Dosen', badge: 'Reading List', icon: GraduationCap },
  { id: 'mahasiswa', title: 'Mahasiswa', badge: 'Sirkulasi Mandiri', icon: User },
  { id: 'tamu', title: 'Tamu / Publik', badge: 'Katalog SEO', icon: Globe },
]

const roleDataMap = {
  admin: {
    title: 'Super Admin',
    badgeVariant: 'red',
    permissionLevel: 'Super Administrator',
    description: 'Mengelola seluruh master data, pengaturan sistem, konfigurasi denda & kuota, analitik global, dan sitemap SEO.',
    features: [
      { title: 'Manajemen Seluruh User & Role', desc: 'Atur izin Spatie Permission, reset akun civitas, dan kelola staf perpustakaan.' },
      { title: 'Pengaturan Kebijakan Sistem', desc: 'Konfigurasi durasi pinjam (default 14 hari), batas kuota, dan nominal denda keterlambatan.' },
      { title: 'Analitik Global & Audit Log', desc: 'Pantau buku paling sering dibaca, waktu kunjungan puncak, dan catatan aktivitas sistem.' },
    ]
  },
  pustakawan: {
    title: 'Pustakawan / Staff Perpustakaan',
    badgeVariant: 'blue',
    permissionLevel: 'Staff Sirkulasi',
    description: 'Menangani sirkulasi harian, manajemen barcode/RFID per eksemplar, verifikasi pengembalian, dan approval usulan koleksi.',
    features: [
      { title: 'Manajemen Koleksi & ISBN', desc: 'Tambah, edit, dan arsipkan buku fisik maupun e-book serta kelola lokasi rak perpustakaan.' },
      { title: 'Layanan Sirkulasi Cepat', desc: 'Proses peminjaman di loket, pengembalian mandiri, dan perpanjangan izin pinjam.' },
      { title: 'Manajemen Denda & Approval', desc: 'Catat pelunasan denda dan setujui permintaan pengadaan buku referensi dari dosen.' },
    ]
  },
  dosen: {
    title: 'Dosen Digitech University',
    badgeVariant: 'amber',
    permissionLevel: 'Pendidik & Peneliti',
    description: 'Akses tanpa kuota ketat (unmetered digital access), buat reading list mata kuliah, dan unggah materi ajar ke repositori.',
    features: [
      { title: 'Kelola Reading List Mata Kuliah', desc: 'Susun daftar buku wajib dan anjuran per semester agar mahasiswa dapat langsung meminjam.' },
      { title: 'Akses E-Book Tanpa Kuota', desc: 'Membaca dan menelaah literatur ilmiah serta prosiding konferensi tanpa batasan kuota sempit.' },
      { title: 'Unggah Referensi Riset', desc: 'Kirimkan modul perkuliahan atau artikel ilmiah untuk dipublikasikan di repositori kampus.' },
    ]
  },
  mahasiswa: {
    title: 'Mahasiswa Digitech University',
    badgeVariant: 'emerald',
    permissionLevel: 'Sivitas Akademika',
    description: 'Peminjaman mandiri hingga 5 buku fisik, akses e-reader online, unduhan offline PWA, rak favorit, dan ulasan buku.',
    features: [
      { title: 'Peminjaman Mandiri (Self-Checkout)', desc: 'Pesan buku secara online atau scan langsung di loket perpustakaan kampus.' },
      { title: 'E-Reader dengan DRM Kampus', desc: 'Baca ratusan e-book akademik langsung di browser dengan proteksi identitas watermark.' },
      { title: 'Rak Virtual & Notifikasi Jatuh Tempo', desc: 'Pantau sisa hari pinjaman dan perpanjang masa pinjam secara online sebelum terlambat.' },
    ]
  },
  tamu: {
    title: 'Tamu / Publik',
    badgeVariant: 'slate',
    permissionLevel: 'Akses Publik Terbuka',
    description: 'Pencarian katalog publik yang terindeks Google (SEO-friendly) untuk mempromosikan riset Digitech ke masyarakat luas.',
    features: [
      { title: 'Jelajah Repositori Terbuka', desc: 'Mencari judul skripsi, karya ilmiah, dan ringkasan buku tanpa perlu login.' },
      { title: 'Informasi Ketersediaan Koleksi', desc: 'Melihat status eksemplar buku di perpustakaan fisik Digitech University.' },
      { title: 'Ajakan Pendaftaran (CTA)', desc: 'Tombol registrasi/login bagi calon sivitas untuk mendapatkan akses sirkulasi lengkap.' },
    ]
  }
}

const activeRoleData = computed(() => {
  return roleDataMap[selectedRole.value] || roleDataMap.mahasiswa
})

function switchRole(roleId) {
  selectedRole.value = roleId
}

function applyCurrentRole() {
  libraryStore.setRole(selectedRole.value)
}
</script>
