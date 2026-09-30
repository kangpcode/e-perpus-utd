<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-6 pb-20 md:pb-12">
    <!-- Header Title & Stats -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">
          Katalog Koleksi Pustaka
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
          Menampilkan <span class="font-bold text-slate-800">{{ libraryStore.filteredBooks.length }}</span> judul buku fisik dan literatur digital
        </p>
      </div>

      <!-- Quick Role Reminder Badge -->
      <div class="flex items-center gap-2">
        <ClayBadge variant="blue" size="sm">
          Akses Saat Ini: {{ libraryStore.currentRole.toUpperCase() }}
        </ClayBadge>
      </div>
    </div>

    <!-- Search & Filter Bar Container -->
    <div class="clay-card p-4 md:p-6 space-y-4 border border-white/80">
      <!-- Search Input -->
      <div class="flex flex-col sm:flex-row items-center gap-3">
        <div class="w-full flex-1">
          <ClayInput
            v-model="libraryStore.searchQuery"
            placeholder="Cari berdasarkan judul, penulis, ISBN, atau topik keilmuan..."
          >
            <template #leading>
              <Search class="w-5 h-5" />
            </template>
          </ClayInput>
        </div>

        <!-- Format Filter Dropdown / Selector -->
        <div class="w-full sm:w-auto flex items-center gap-2">
          <select
            v-model="libraryStore.selectedFormat"
            class="clay-inset px-4 py-3 rounded-2xl text-xs sm:text-sm font-semibold text-slate-700 bg-transparent border-none focus:outline-none focus:ring-2 focus:ring-blue-400"
          >
            <option value="all">Semua Format</option>
            <option value="physical">Buku Fisik Saja</option>
            <option value="pdf">E-Book PDF</option>
            <option value="epub">E-Book EPUB</option>
          </select>

          <!-- Availability Filter Toggle -->
          <button
            type="button"
            :class="[
              'px-4 py-3 rounded-2xl text-xs sm:text-sm font-semibold transition-all flex items-center gap-1.5 shrink-0 select-none active:scale-95',
              libraryStore.selectedAvailability === 'available'
                ? 'clay-btn-blue text-white'
                : 'clay-inset text-slate-600 hover:text-slate-900'
            ]"
            @click="toggleAvailabilityFilter"
          >
            <CheckCircle2 class="w-4 h-4" />
            <span class="hidden sm:inline">Hanya</span> Tersedia
          </button>
        </div>
      </div>

      <!-- Category Filter Pills -->
      <div class="flex items-center gap-2 overflow-x-auto pb-2 pt-1 no-scrollbar">
        <button
          v-for="cat in libraryStore.categories"
          :key="cat.id"
          type="button"
          :class="[
            'px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all select-none active:scale-95',
            libraryStore.selectedCategory === cat.id
              ? 'clay-btn-red text-white'
              : 'clay-inset text-slate-600 hover:text-slate-900'
          ]"
          @click="libraryStore.selectedCategory = cat.id"
        >
          {{ cat.name }}
        </button>
      </div>

      <!-- Active Filters Summary & Reset -->
      <div
        v-if="hasActiveFilters"
        class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs text-slate-500"
      >
        <div class="flex items-center gap-1.5">
          <Filter class="w-3.5 h-3.5 text-blue-600" />
          <span>Filter aktif diterapkan</span>
        </div>
        <button
          type="button"
          class="text-[#D92B3E] font-bold hover:underline"
          @click="resetFilters"
        >
          Reset Semua Filter
        </button>
      </div>
    </div>

    <!-- Books Display Grid -->
    <div
      v-if="libraryStore.filteredBooks.length > 0"
      class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
    >
      <BookCard
        v-for="book in libraryStore.filteredBooks"
        :key="book.id"
        :book="book"
      />
    </div>

    <!-- Empty State -->
    <div
      v-else
      class="clay-card p-12 text-center max-w-md mx-auto my-8 space-y-4"
    >
      <div class="w-16 h-16 rounded-3xl bg-red-50 text-[#D92B3E] flex items-center justify-center mx-auto shadow-inner">
        <SearchX class="w-8 h-8" />
      </div>
      <h3 class="text-lg font-bold font-heading text-slate-800">
        Koleksi Tidak Ditemukan
      </h3>
      <p class="text-xs text-slate-500 leading-relaxed">
        Tidak ada buku atau e-book yang cocok dengan kata kunci atau filter yang Anda terapkan. Coba ubah kata kunci atau reset filter pencarian.
      </p>
      <div>
        <ClayButton
          variant="white"
          size="sm"
          @click="resetFilters"
        >
          Reset Filter Pencarian
        </ClayButton>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useLibraryStore } from '../stores/library'
import ClayInput from '../components/ui/ClayInput.vue'
import ClayBadge from '../components/ui/ClayBadge.vue'
import ClayButton from '../components/ui/ClayButton.vue'
import BookCard from '../components/books/BookCard.vue'
import {
  Search,
  CheckCircle2,
  Filter,
  SearchX
} from 'lucide-vue-next'

const libraryStore = useLibraryStore()

const hasActiveFilters = computed(() => {
  return libraryStore.selectedCategory !== 'all' ||
    libraryStore.searchQuery !== '' ||
    libraryStore.selectedFormat !== 'all' ||
    libraryStore.selectedAvailability !== 'all'
})

function toggleAvailabilityFilter() {
  if (libraryStore.selectedAvailability === 'available') {
    libraryStore.selectedAvailability = 'all'
  } else {
    libraryStore.selectedAvailability = 'available'
  }
}

function resetFilters() {
  libraryStore.selectedCategory = 'all'
  libraryStore.searchQuery = ''
  libraryStore.selectedFormat = 'all'
  libraryStore.selectedAvailability = 'all'
}
</script>
