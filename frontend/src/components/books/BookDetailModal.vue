<template>
  <ClayModal
    :is-open="!!book"
    custom-class="max-w-3xl"
    @close="libraryStore.closeDetail()"
  >
    <div v-if="book" class="space-y-6">
      <!-- Header Section: Cover + Meta Info -->
      <div class="flex flex-col sm:flex-row gap-6 items-center sm:items-start">
        <!-- 3D Clay Cover Display -->
        <div class="shrink-0 flex justify-center">
          <div
            :class="[
              'clay-book-spine w-36 h-52 sm:w-44 sm:h-64 rounded-r-xl rounded-l-sm bg-gradient-to-br p-4 flex flex-col justify-between text-white shadow-xl relative',
              book.coverGradient
            ]"
          >
            <div class="text-[10px] font-bold uppercase tracking-wider text-white/80 flex items-center gap-1">
              <GraduationCap class="w-3.5 h-3.5" /> Digitech Press
            </div>
            <div class="my-auto">
              <h3 class="text-sm sm:text-base font-bold leading-tight font-heading drop-shadow-sm">
                {{ book.title }}
              </h3>
              <p class="text-xs text-white/80 mt-1 line-clamp-2">
                {{ book.author }}
              </p>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-white/20 text-[10px] text-white/80 font-mono">
              <span>{{ book.year }}</span>
              <span class="font-bold text-amber-300 uppercase">{{ book.formatType }}</span>
            </div>
          </div>
        </div>

        <!-- Meta Details -->
        <div class="flex-1 space-y-3 text-center sm:text-left">
          <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
            <ClayBadge variant="blue" size="sm">
              {{ book.categoryName }}
            </ClayBadge>
            <ClayBadge :variant="book.isDigital ? 'emerald' : 'amber'" size="sm">
              {{ book.format }}
            </ClayBadge>
            <ClayBadge variant="slate" size="sm">
              Tahun {{ book.year }}
            </ClayBadge>
          </div>

          <h2 class="text-xl sm:text-2xl font-bold font-heading text-slate-800 leading-snug">
            {{ book.title }}
          </h2>

          <p class="text-sm font-medium text-slate-600">
            Penulis: <span class="text-blue-700 font-semibold">{{ book.author }}</span>
          </p>

          <p class="text-xs text-slate-500">
            Penerbit: {{ book.publisher }} • ISBN: <span class="font-mono">{{ book.isbn }}</span>
          </p>

          <!-- Rating & Borrow Metrics -->
          <div class="flex items-center justify-center sm:justify-start gap-4 py-2 border-y border-slate-100 text-sm">
            <div class="flex items-center gap-1.5 text-amber-500 font-semibold">
              <Star class="w-4 h-4 fill-amber-400 text-amber-400" />
              <span>{{ book.rating }} / 5.0</span>
              <span class="text-xs text-slate-400 font-normal">({{ book.ratingCount }} ulasan)</span>
            </div>
            <div class="text-slate-300">|</div>
            <div class="text-slate-600 text-xs">
              <span class="font-semibold text-slate-800">{{ book.borrowCount }}</span> kali dipinjam
            </div>
            <div class="text-slate-300">|</div>
            <div class="text-slate-600 text-xs">
              <span class="font-semibold text-slate-800">{{ book.pages }}</span> Halaman
            </div>
          </div>

          <!-- Stock & Shelf Location Box -->
          <div class="clay-inset p-3 text-xs space-y-1">
            <div class="flex items-center justify-between">
              <span class="text-slate-500">Lokasi / Penyimpanan:</span>
              <span class="font-semibold text-slate-700 flex items-center gap-1">
                <MapPin class="w-3.5 h-3.5 text-red-500" />
                {{ book.shelfLocation }}
              </span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-slate-500">Ketersediaan Eksemplar:</span>
              <span v-if="book.isDigital" class="font-semibold text-emerald-600">
                Akses Digital Tersedia (Tanpa Batas Fisik)
              </span>
              <span v-else-if="book.availableStock > 0" class="font-semibold text-blue-600">
                Tersedia {{ book.availableStock }} dari {{ book.stock }} buku fisik
              </span>
              <span v-else class="font-semibold text-rose-600">
                Semua eksemplar sedang dipinjam
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Synopsis -->
      <div>
        <h4 class="font-heading font-bold text-slate-800 text-sm mb-2 flex items-center gap-2">
          <BookText class="w-4 h-4 text-blue-600" /> Sinopsis & Cakupan Pembahasan
        </h4>
        <div class="clay-inset p-4 text-xs sm:text-sm text-slate-600 leading-relaxed max-h-36 overflow-y-auto">
          {{ book.synopsis }}
        </div>
      </div>

      <!-- Tags -->
      <div class="flex flex-wrap items-center gap-1.5">
        <span class="text-xs text-slate-400 mr-1 flex items-center gap-1">
          <Tag class="w-3 h-3" /> Topik:
        </span>
        <span
          v-for="tag in book.tags"
          :key="tag"
          class="px-2.5 py-1 text-xs rounded-full bg-slate-100 text-slate-600 font-medium"
        >
          #{{ tag }}
        </span>
      </div>
    </div>

    <!-- Modal Footer Actions -->
    <template #footer>
      <ClayButton
        variant="ghost"
        size="md"
        @click="libraryStore.closeDetail()"
      >
        Tutup
      </ClayButton>

      <ClayButton
        v-if="book.isDigital"
        variant="red"
        size="md"
        @click="startReading"
      >
        <template #leading>
          <BookOpen class="w-4 h-4" />
        </template>
        Buka di E-Reader
      </ClayButton>

      <ClayButton
        v-if="book.isPhysical"
        variant="blue"
        size="md"
        :disabled="book.availableStock <= 0 || isAlreadyBorrowed"
        @click="borrowPhysicalBook"
      >
        <template #leading>
          <CheckCircle2 v-if="isAlreadyBorrowed" class="w-4 h-4" />
          <BookmarkPlus v-else class="w-4 h-4" />
        </template>
        {{ isAlreadyBorrowed ? 'Sedang Dipinjam' : (book.availableStock > 0 ? 'Ajukan Pinjam Fisik (14 Hari)' : 'Reservasi Antrean') }}
      </ClayButton>
    </template>
  </ClayModal>
</template>

<script setup>
import { computed } from 'vue'
import { useLibraryStore } from '../../stores/library'
import ClayModal from '../ui/ClayModal.vue'
import ClayBadge from '../ui/ClayBadge.vue'
import ClayButton from '../ui/ClayButton.vue'
import {
  GraduationCap,
  Star,
  MapPin,
  BookText,
  Tag,
  BookOpen,
  BookmarkPlus,
  CheckCircle2
} from 'lucide-vue-next'

const libraryStore = useLibraryStore()
const book = computed(() => libraryStore.activeDetailBook)

const isAlreadyBorrowed = computed(() => {
  return book.value ? libraryStore.isBookBorrowed(book.value.id) : false
})

function startReading() {
  const current = book.value
  libraryStore.closeDetail()
  libraryStore.openReader(current)
}

function borrowPhysicalBook() {
  if (book.value) {
    const success = libraryStore.borrowBook(book.value)
    if (success) {
      libraryStore.closeDetail()
    }
  }
}
</script>
