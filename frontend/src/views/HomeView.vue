<template>
  <div class="space-y-12 sm:space-y-16 pb-20 md:pb-12">
    <!-- HERO SECTION -->
    <section class="relative overflow-hidden pt-4 sm:pt-8">
      <!-- Background Decorative Blur Glows -->
      <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-400/15 rounded-full blur-3xl pointer-events-none -z-10"></div>
      <div class="absolute top-12 right-1/4 w-96 h-96 bg-red-400/15 rounded-full blur-3xl pointer-events-none -z-10"></div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
          <!-- Left: Hero Text & Search Box -->
          <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white clay-card text-xs font-semibold text-[#1E4FA3] shadow-sm">
              <span class="w-2 h-2 rounded-full bg-[#D92B3E] animate-pulse"></span>
              DIGIPUS • Pustaka Digitech University
            </div>

            <!-- Headline -->
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold font-heading text-slate-900 leading-tight tracking-tight">
              Eksplorasi Ilmu Tanpa Batas dengan
              <span class="bg-gradient-to-r from-[#1E4FA3] via-blue-700 to-[#D92B3E] bg-clip-text text-transparent">
                Perpustakaan Digital
              </span>
              Modern
            </h1>

            <p class="text-sm sm:text-base text-slate-600 max-w-xl mx-auto lg:mx-0 leading-relaxed font-normal">
              Kelola peminjaman buku fisik, baca e-book akademik, dan temukan ribuan referensi karya ilmiah Digitech University dalam satu platform terintegrasi berdesain <span class="font-semibold text-slate-800">Claymorphism</span> yang intuitif.
            </p>

            <!-- Clay Search Capsule Box -->
            <div class="clay-card p-2 sm:p-3 max-w-xl mx-auto lg:mx-0 flex flex-col sm:flex-row items-center gap-2 border border-white/80">
              <div class="relative w-full flex-1">
                <Search class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" />
                <input
                  v-model="heroSearch"
                  type="text"
                  placeholder="Cari judul buku, penulis, ISBN, atau topik skripsi..."
                  class="w-full pl-11 pr-4 py-3 bg-transparent text-sm sm:text-base text-slate-800 placeholder-slate-400 focus:outline-none"
                  @keyup.enter="handleHeroSearch"
                />
              </div>
              <ClayButton
                variant="red"
                size="md"
                class="w-full sm:w-auto shrink-0"
                @click="handleHeroSearch"
              >
                <template #leading>
                  <Search class="w-4 h-4" />
                </template>
                Cari Buku
              </ClayButton>
            </div>

            <!-- Quick Category Tags -->
            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 text-xs">
              <span class="text-slate-400 font-medium">Topik Populer:</span>
              <button
                v-for="topic in quickTopics"
                :key="topic"
                type="button"
                class="px-3 py-1 rounded-full bg-white text-slate-700 hover:text-[#1E4FA3] hover:shadow-sm transition-all border border-slate-200/60 font-medium active:scale-95"
                @click="searchByTag(topic)"
              >
                {{ topic }}
              </button>
            </div>
          </div>

          <!-- Right: Hero 3D Clay Feature Showcase Card -->
          <div class="lg:col-span-5 flex justify-center">
            <div class="w-full max-w-md relative">
              <!-- Background floating clay backdrop -->
              <div class="clay-card p-6 sm:p-8 bg-gradient-to-br from-white via-[#FAFBFD] to-[#EFF3FA] relative border border-white">
                <!-- Top Ribbon -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                  <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                  </div>
                  <span class="text-[11px] font-mono font-bold text-slate-400 uppercase tracking-widest">
                    LAYANAN TERPADU
                  </span>
                </div>

                <!-- Showcase Highlights -->
                <div class="mt-5 space-y-4">
                  <!-- Highlight 1: Dual Mode -->
                  <div class="clay-inset p-3.5 flex items-start gap-3 rounded-2xl">
                    <div class="p-2.5 rounded-xl bg-blue-100 text-[#1E4FA3] shrink-0 shadow-sm">
                      <BookOpen class="w-5 h-5" />
                    </div>
                    <div>
                      <h4 class="text-xs sm:text-sm font-bold text-slate-800">Sirkulasi Ganda (Fisik + Digital)</h4>
                      <p class="text-[11px] text-slate-500 mt-0.5">Booking buku fisik ke loket kampus atau baca e-book PDF/EPUB instan di browser.</p>
                    </div>
                  </div>

                  <!-- Highlight 2: PWA Offline -->
                  <div class="clay-inset p-3.5 flex items-start gap-3 rounded-2xl">
                    <div class="p-2.5 rounded-xl bg-red-100 text-[#D92B3E] shrink-0 shadow-sm">
                      <Smartphone class="w-5 h-5" />
                    </div>
                    <div>
                      <h4 class="text-xs sm:text-sm font-bold text-slate-800">PWA & Terasa Native App</h4>
                      <p class="text-[11px] text-slate-500 mt-0.5">Install di HP atau Laptop, akses buku yang diunduh secara offline tanpa kuota.</p>
                    </div>
                  </div>

                  <!-- Highlight 3: DRM-Lite Protection -->
                  <div class="clay-inset p-3.5 flex items-start gap-3 rounded-2xl">
                    <div class="p-2.5 rounded-xl bg-emerald-100 text-emerald-700 shrink-0 shadow-sm">
                      <ShieldCheck class="w-5 h-5" />
                    </div>
                    <div>
                      <h4 class="text-xs sm:text-sm font-bold text-slate-800">DRM-Lite & Watermark Kampus</h4>
                      <p class="text-[11px] text-slate-500 mt-0.5">Perlindungan hak cipta karya civitas dengan watermark identitas digital otomatis.</p>
                    </div>
                  </div>
                </div>

                <!-- Bottom Button inside Card -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                  <span class="text-xs text-slate-500">Kuota Pinjam: <strong class="text-slate-800">5 Buku / Mahasiswa</strong></span>
                  <router-link to="/katalog">
                    <ClayButton variant="blue" size="sm">
                      Jelajah Sekarang
                    </ClayButton>
                  </router-link>
                </div>
              </div>

              <!-- Decorative Floating Micro-Cards -->
              <div class="absolute -bottom-4 -left-4 clay-card py-2.5 px-4 rounded-2xl bg-white shadow-xl flex items-center gap-2.5 border border-white">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs">
                  ✓
                </div>
                <div>
                  <div class="text-[10px] text-slate-400 font-bold uppercase">SISTEM AKTIF</div>
                  <div class="text-xs font-bold text-slate-800">Kampus Terhubung</div>
                </div>
              </div>

              <div class="absolute -top-4 -right-4 clay-card py-2 px-3.5 rounded-2xl bg-white shadow-xl flex items-center gap-2 border border-white">
                <Star class="w-4 h-4 fill-amber-400 text-amber-400" />
                <span class="text-xs font-bold text-slate-800">4.9 / 5.0 Rating</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- METRICS RIBBON (CLAY STYLE) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
        <div class="clay-card p-5 text-center flex flex-col items-center justify-center">
          <div class="w-10 h-10 rounded-2xl bg-blue-50 text-[#1E4FA3] flex items-center justify-center mb-2 shadow-inner">
            <BookMarked class="w-5 h-5" />
          </div>
          <div class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">
            {{ stats.totalBooks }}
          </div>
          <div class="text-xs font-medium text-slate-500 mt-1">
            Total Judul Koleksi
          </div>
        </div>

        <div class="clay-card p-5 text-center flex flex-col items-center justify-center">
          <div class="w-10 h-10 rounded-2xl bg-red-50 text-[#D92B3E] flex items-center justify-center mb-2 shadow-inner">
            <FileText class="w-5 h-5" />
          </div>
          <div class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">
            {{ stats.totalEbooks }}
          </div>
          <div class="text-xs font-medium text-slate-500 mt-1">
            E-Book & Jurnal Digital
          </div>
        </div>

        <div class="clay-card p-5 text-center flex flex-col items-center justify-center">
          <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-2 shadow-inner">
            <Users class="w-5 h-5" />
          </div>
          <div class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">
            {{ stats.activeMembers }}
          </div>
          <div class="text-xs font-medium text-slate-500 mt-1">
            Sivitas Akademika Aktif
          </div>
        </div>

        <div class="clay-card p-5 text-center flex flex-col items-center justify-center">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2 shadow-inner">
            <Sparkles class="w-5 h-5" />
          </div>
          <div class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">
            {{ stats.satisfactionRate }}
          </div>
          <div class="text-xs font-medium text-slate-500 mt-1">
            Indeks Kepuasan Pengguna
          </div>
        </div>
      </div>
    </section>

    <!-- FEATURED BOOKS SECTION -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
        <div>
          <div class="text-xs font-bold uppercase tracking-wider text-[#D92B3E] flex items-center gap-1.5 mb-1">
            <Flame class="w-4 h-4" /> KOLEKSI UNGGULAN & TERPOPULER
          </div>
          <h2 class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">
            Rekomendasi Terbaik Minggu Ini
          </h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Buku dan materi referensi ilmiah yang paling banyak dipinjam dan dipelajari mahasiswa
          </p>
        </div>

        <router-link to="/katalog">
          <ClayButton variant="white" size="sm">
            Lihat Semua Koleksi ({{ libraryStore.books.length }})
            <template #trailing>
              <ArrowRight class="w-4 h-4 ml-1" />
            </template>
          </ClayButton>
        </router-link>
      </div>

      <!-- Book Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <BookCard
          v-for="book in featuredBooks"
          :key="book.id"
          :book="book"
        />
      </div>
    </section>

    <!-- CATEGORIES HUB -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6">
      <div class="clay-card p-6 sm:p-10 bg-gradient-to-r from-blue-900 via-indigo-900 to-[#1E4FA3] text-white rounded-[32px] shadow-xl relative overflow-hidden">
        <!-- Background graphics -->
        <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>

        <div class="relative z-10">
          <div class="max-w-2xl mb-8">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-300">
              JELAJAH BIDANG ILMU
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold font-heading text-white mt-1">
              Kategori Keilmuan Digitech University
            </h2>
            <p class="text-xs sm:text-sm text-blue-100/90 mt-2">
              Pilih bidang kajian yang ingin Anda pelajari untuk menemukan literatur, handbook, dan artikel jurnal yang relevan.
            </p>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
            <div
              v-for="cat in libraryStore.categories.filter(c => c.id !== 'all')"
              :key="cat.id"
              class="clay-card p-4 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 cursor-pointer transition-all hover:-translate-y-1 active:scale-95 text-center flex flex-col items-center justify-center gap-2 group"
              @click="filterByCategory(cat.id)"
            >
              <div class="w-10 h-10 rounded-xl bg-white/20 group-hover:bg-[#D92B3E] transition-colors flex items-center justify-center text-white">
                <FolderGit2 class="w-5 h-5" />
              </div>
              <h3 class="text-xs font-bold text-white leading-tight">
                {{ cat.name }}
              </h3>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- INTERACTIVE READER PROMO BANNER -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
        <!-- Text Info -->
        <div class="space-y-5">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 text-[#D92B3E] text-xs font-bold">
            <BookOpenCheck class="w-4 h-4" /> IN-BROWSER DIGITAL READER
          </div>
          <h2 class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900 leading-snug">
            Membaca Nyaman di Laptop Maupun Smartphone, Kapan Saja
          </h2>
          <p class="text-sm text-slate-600 leading-relaxed">
            Tidak perlu menginstal software pembaca pihak ketiga yang rumit. E-Reader DIGIPUS terintegrasi langsung dengan format PDF dan EPUB. Atur ukuran teks, ubah tema baca (Sepia, Malam, Terang), dan bookmark posisi halaman Anda secara otomatis.
          </p>

          <div class="grid grid-cols-2 gap-4 text-xs font-semibold text-slate-700">
            <div class="flex items-center gap-2">
              <CheckCircle2 class="w-4 h-4 text-emerald-500" /> Mode Baca Sepia & Malam
            </div>
            <div class="flex items-center gap-2">
              <CheckCircle2 class="w-4 h-4 text-emerald-500" /> Watermark Digital Kampus
            </div>
            <div class="flex items-center gap-2">
              <CheckCircle2 class="w-4 h-4 text-emerald-500" /> Cache Offline (PWA)
            </div>
            <div class="flex items-center gap-2">
              <CheckCircle2 class="w-4 h-4 text-emerald-500" /> Ukuran Font Adaptif
            </div>
          </div>

          <div class="pt-2">
            <ClayButton
              variant="red"
              size="md"
              @click="libraryStore.openReader(libraryStore.books[0])"
            >
              <template #leading>
                <BookOpen class="w-4 h-4" />
              </template>
              Coba E-Reader Interaktif Sekarang
            </ClayButton>
          </div>
        </div>

        <!-- Visual Mockup Card -->
        <div class="clay-card p-6 bg-white rounded-3xl border border-white/80 shadow-xl">
          <div class="clay-inset p-4 rounded-2xl bg-slate-900 text-slate-200 space-y-3 font-mono text-xs">
            <div class="flex items-center justify-between text-[11px] text-slate-400 border-b border-slate-800 pb-2">
              <span>READER-DRM-ENGINE: ONLINE</span>
              <span class="text-emerald-400">READY</span>
            </div>
            <p class="text-slate-300 font-sans text-sm font-semibold">
              "Arsitektur Kecerdasan Buatan Modern: Deep Learning & Large Language Models"
            </p>
            <div class="p-3 bg-slate-800/80 rounded-xl text-slate-400 font-sans text-xs italic">
              Watermark Lisensi: DIGIPUS • NIM: 22010884 • Digitech University
            </div>
            <div class="flex items-center justify-between pt-2 text-[11px]">
              <span class="text-amber-400">Halaman 1 dari 486</span>
              <span class="text-blue-400">Format: E-Book PDF</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useLibraryStore } from '../stores/library'
import { MOCK_STATS } from '../data/mockBooks'
import ClayButton from '../components/ui/ClayButton.vue'
import BookCard from '../components/books/BookCard.vue'
import {
  Search,
  BookOpen,
  BookMarked,
  FileText,
  Users,
  Sparkles,
  Smartphone,
  ShieldCheck,
  Star,
  Flame,
  ArrowRight,
  FolderGit2,
  BookOpenCheck,
  CheckCircle2
} from 'lucide-vue-next'

const router = useRouter()
const libraryStore = useLibraryStore()
const heroSearch = ref('')
const stats = ref(MOCK_STATS)

const quickTopics = [
  'Deep Learning',
  'UI/UX Claymorphism',
  'Laravel & Vue',
  'Bisnis Startup',
  'Cyber Security',
  'Data Science'
]

const featuredBooks = computed(() => {
  return libraryStore.books.slice(0, 4)
})

function handleHeroSearch() {
  if (heroSearch.value.trim()) {
    libraryStore.searchQuery = heroSearch.value.trim()
  }
  router.push('/katalog')
}

function searchByTag(topic) {
  libraryStore.searchQuery = topic
  router.push('/katalog')
}

function filterByCategory(catId) {
  libraryStore.selectedCategory = catId
  router.push('/katalog')
}
</script>
