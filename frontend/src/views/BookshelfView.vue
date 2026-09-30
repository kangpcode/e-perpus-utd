<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-6 pb-20 md:pb-12">
    <!-- Top Quota & Profile Overview Card -->
    <div class="clay-card p-6 sm:p-8 bg-gradient-to-br from-white via-blue-50/20 to-white border border-white">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div class="space-y-1">
          <div class="flex items-center gap-2">
            <ClayBadge variant="blue" size="sm">
              {{ libraryStore.userProfile.faculty }}
            </ClayBadge>
            <ClayBadge variant="emerald" size="sm" :dot="true">
              Akun Aktif
            </ClayBadge>
          </div>
          <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900 mt-1">
            Rak Virtual & Sirkulasi Saya
          </h1>
          <p class="text-xs sm:text-sm text-slate-500">
            {{ libraryStore.userProfile.name }} • <span class="font-mono">{{ libraryStore.userProfile.nim }}</span>
          </p>
        </div>

        <!-- Quota Pill Card -->
        <div class="clay-inset p-4 rounded-2xl sm:w-72 space-y-2">
          <div class="flex items-center justify-between text-xs">
            <span class="font-medium text-slate-500">Kuota Peminjaman:</span>
            <span class="font-bold text-slate-800">
              {{ libraryStore.loans.length }} / {{ libraryStore.userProfile.maxBorrowQuota }} Buku
            </span>
          </div>
          <div class="w-full h-2.5 rounded-full bg-slate-200 overflow-hidden">
            <div
              class="h-full rounded-full bg-gradient-to-r from-blue-600 to-[#D92B3E] transition-all duration-300"
              :style="{ width: `${(libraryStore.loans.length / libraryStore.userProfile.maxBorrowQuota) * 100}%` }"
            ></div>
          </div>
          <div class="text-[11px] text-slate-400 text-right">
            Sisa kuota: {{ libraryStore.userProfile.maxBorrowQuota - libraryStore.loans.length }} judul
          </div>
        </div>
      </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex items-center gap-3 border-b border-slate-200/80 pb-3">
      <button
        type="button"
        :class="[
          'px-4 py-2 rounded-2xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 select-none active:scale-95',
          activeTab === 'loans'
            ? 'clay-btn-blue text-white'
            : 'clay-card text-slate-600 hover:text-slate-900'
        ]"
        @click="activeTab = 'loans'"
      >
        <BookMarked class="w-4 h-4" />
        Pinjaman Aktif ({{ libraryStore.loans.length }})
      </button>

      <button
        type="button"
        :class="[
          'px-4 py-2 rounded-2xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 select-none active:scale-95',
          activeTab === 'wishlist'
            ? 'clay-btn-blue text-white'
            : 'clay-card text-slate-600 hover:text-slate-900'
        ]"
        @click="activeTab = 'wishlist'"
      >
        <Heart class="w-4 h-4 text-rose-500" />
        Rak Favorit ({{ libraryStore.wishlist.length }})
      </button>

      <button
        type="button"
        :class="[
          'px-4 py-2 rounded-2xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 select-none active:scale-95',
          activeTab === 'denda'
            ? 'clay-btn-blue text-white'
            : 'clay-card text-slate-600 hover:text-slate-900'
        ]"
        @click="activeTab = 'denda'"
      >
        <Receipt class="w-4 h-4 text-amber-500" />
        Status Denda
      </button>
    </div>

    <!-- TAB 1: PINJAMAN AKTIF -->
    <div v-if="activeTab === 'loans'" class="space-y-4">
      <div v-if="libraryStore.loans.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div
          v-for="loan in libraryStore.loans"
          :key="loan.id"
          class="clay-card p-5 sm:p-6 flex flex-col justify-between border border-white"
        >
          <div>
            <div class="flex items-center justify-between gap-2 mb-3">
              <span class="text-xs font-mono font-bold text-slate-400">
                #{{ loan.id }}
              </span>
              <ClayBadge
                :variant="loan.daysRemaining <= 3 ? 'red' : 'emerald'"
                size="sm"
                :dot="true"
              >
                {{ loan.daysRemaining }} Hari Tersisa
              </ClayBadge>
            </div>

            <div class="flex gap-4 items-start">
              <!-- Book Mini Spine -->
              <div
                :class="[
                  'clay-book-spine w-16 h-24 rounded-r-lg rounded-l-xs bg-gradient-to-br p-2 text-white shrink-0 flex flex-col justify-between text-[8px]',
                  loan.coverGradient
                ]"
              >
                <div class="font-bold uppercase tracking-wider">DIGIPUS</div>
                <div class="font-mono text-[7px]">{{ loan.format.split(' ')[0] }}</div>
              </div>

              <!-- Loan Details -->
              <div class="space-y-1 flex-1 min-w-0">
                <h3 class="text-sm sm:text-base font-bold font-heading text-slate-800 leading-snug line-clamp-2">
                  {{ loan.title }}
                </h3>
                <div class="text-xs text-slate-500 space-y-0.5 pt-1">
                  <div>Kode Salinan: <span class="font-mono font-semibold text-slate-700">{{ loan.copyCode }}</span></div>
                  <div>Tanggal Pinjam: <span class="font-medium text-slate-700">{{ loan.borrowDate }}</span></div>
                  <div>Jatuh Tempo: <strong class="text-red-600 font-semibold">{{ loan.dueDate }}</strong></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Bottom Action Buttons -->
          <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
            <span class="text-xs text-slate-400">
              Perpanjangan: <strong>{{ loan.extendCount }}/{{ loan.maxExtend }}x</strong>
            </span>

            <div class="flex items-center gap-2">
              <ClayButton
                variant="white"
                size="sm"
                :disabled="loan.extendCount >= loan.maxExtend"
                @click="libraryStore.extendLoan(loan.id)"
              >
                <template #leading>
                  <RefreshCw class="w-3.5 h-3.5 text-blue-600" />
                </template>
                Perpanjang (+7h)
              </ClayButton>

              <ClayButton
                variant="red"
                size="sm"
                @click="libraryStore.returnLoan(loan.id)"
              >
                <template #leading>
                  <CheckCircle2 class="w-3.5 h-3.5" />
                </template>
                Kembalikan
              </ClayButton>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="clay-card p-12 text-center max-w-md mx-auto my-8 space-y-4">
        <div class="w-16 h-16 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto shadow-inner">
          <BookOpenCheck class="w-8 h-8" />
        </div>
        <h3 class="text-lg font-bold font-heading text-slate-800">
          Tidak Ada Pinjaman Aktif
        </h3>
        <p class="text-xs text-slate-500 leading-relaxed">
          Anda sedang tidak meminjam buku fisik ataupun e-book. Silakan jelajahi katalog untuk mulai meminjam referensi kuliah Anda.
        </p>
        <router-link to="/katalog">
          <ClayButton variant="red" size="sm">
            Jelajah Katalog Sekarang
          </ClayButton>
        </router-link>
      </div>
    </div>

    <!-- TAB 2: RAK FAVORIT / WISHLIST -->
    <div v-else-if="activeTab === 'wishlist'" class="space-y-4">
      <div v-if="wishlistBooks.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <BookCard
          v-for="book in wishlistBooks"
          :key="book.id"
          :book="book"
        />
      </div>

      <div v-else class="clay-card p-12 text-center max-w-md mx-auto my-8 space-y-4">
        <div class="w-16 h-16 rounded-3xl bg-rose-50 text-rose-500 flex items-center justify-center mx-auto shadow-inner">
          <Heart class="w-8 h-8" />
        </div>
        <h3 class="text-lg font-bold font-heading text-slate-800">
          Rak Favorit Masih Kosong
        </h3>
        <p class="text-xs text-slate-500 leading-relaxed">
          Tandai buku yang menarik perhatian Anda dengan menekan ikon hati agar mudah ditemukan kembali di masa mendatang.
        </p>
      </div>
    </div>

    <!-- TAB 3: STATUS DENDA & PEMBAYARAN -->
    <div v-else-if="activeTab === 'denda'" class="space-y-4">
      <div class="clay-card p-6 sm:p-8 max-w-xl mx-auto space-y-6 text-center border border-white">
        <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-inner">
          <CheckCircle2 class="w-8 h-8" />
        </div>

        <div class="space-y-1">
          <h3 class="text-xl font-bold font-heading text-slate-800">
            Bebas Tunggakan Denda
          </h3>
          <p class="text-xs text-slate-500">
            Anda tidak memiliki denda keterlambatan pengembalian buku. Terima kasih atas kepatuhan Anda!
          </p>
        </div>

        <div class="clay-inset p-4 rounded-2xl text-left text-xs space-y-2">
          <div class="font-bold text-slate-700 flex items-center gap-1.5">
            <Info class="w-4 h-4 text-blue-600" /> Ketentuan Denda Kampus:
          </div>
          <ul class="list-disc list-inside text-slate-500 space-y-1">
            <li>Keterlambatan buku fisik: Rp 1.000 / hari per eksemplar.</li>
            <li>E-Book digital kedaluwarsa secara otomatis (tanpa denda finansial).</li>
            <li>Pembayaran denda dapat dilakukan secara online melalui integrasi QRIS/Virtual Account saat sirkulasi.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useLibraryStore } from '../stores/library'
import ClayBadge from '../components/ui/ClayBadge.vue'
import ClayButton from '../components/ui/ClayButton.vue'
import BookCard from '../components/books/BookCard.vue'
import {
  BookMarked,
  Heart,
  Receipt,
  RefreshCw,
  CheckCircle2,
  BookOpenCheck,
  Info
} from 'lucide-vue-next'

const libraryStore = useLibraryStore()
const activeTab = ref('loans')

const wishlistBooks = computed(() => {
  return libraryStore.books.filter(b => libraryStore.wishlist.includes(b.id))
})
</script>
