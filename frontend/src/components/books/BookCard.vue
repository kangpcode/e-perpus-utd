<template>
  <div
    class="clay-card clay-card-hover p-4 md:p-5 flex flex-col justify-between group transition-all duration-300 relative border border-white/60"
  >
    <!-- Top Bar: Badge & Bookmark -->
    <div class="flex items-center justify-between gap-2 mb-3">
      <ClayBadge
        :variant="formatBadgeVariant"
        size="xs"
      >
        <span class="flex items-center gap-1">
          <component :is="formatIcon" class="w-3 h-3" />
          {{ book.format }}
        </span>
      </ClayBadge>

      <button
        type="button"
        :class="[
          'w-8 h-8 rounded-full flex items-center justify-center transition-all active:scale-90',
          isWishlisted
            ? 'bg-rose-50 text-rose-600 shadow-inner'
            : 'bg-slate-100/70 text-slate-400 hover:text-rose-500 hover:bg-rose-50'
        ]"
        title="Simpan ke Rak Favorit"
        @click.stop="libraryStore.toggleWishlist(book.id)"
      >
        <Heart :class="['w-4 h-4', isWishlisted ? 'fill-rose-500 text-rose-500' : '']" />
      </button>
    </div>

    <!-- Book Cover & Spine 3D Representation -->
    <div
      class="cursor-pointer relative mb-4 flex justify-center py-2"
      @click="libraryStore.openDetail(book)"
    >
      <div
        :class="[
          'clay-book-spine w-32 h-44 sm:w-36 sm:h-48 md:w-40 md:h-52 rounded-r-xl rounded-l-sm bg-gradient-to-br p-3.5 flex flex-col justify-between text-white relative shadow-lg overflow-hidden',
          book.coverGradient
        ]"
      >
        <!-- Top Institutional Brand & Category -->
        <div class="z-10">
          <div class="text-[9px] font-bold uppercase tracking-wider text-white/80 flex items-center gap-1">
            <GraduationCap class="w-3 h-3" /> Digitech Press
          </div>
          <div class="text-[10px] font-semibold text-white/90 line-clamp-1 mt-0.5">
            {{ book.categoryName }}
          </div>
        </div>

        <!-- Book Title on Cover -->
        <div class="z-10 my-auto">
          <h4 class="text-xs sm:text-sm font-bold leading-snug line-clamp-3 drop-shadow-sm font-heading">
            {{ book.title }}
          </h4>
          <p class="text-[10px] text-white/80 mt-1 line-clamp-1 font-medium">
            {{ book.author }}
          </p>
        </div>

        <!-- Bottom Spine Bar & Year -->
        <div class="z-10 flex items-center justify-between pt-2 border-t border-white/20 text-[9px] text-white/80 font-mono">
          <span>{{ book.year }}</span>
          <span v-if="book.isDigital" class="font-semibold text-amber-300">E-READER</span>
          <span v-else class="font-semibold text-emerald-300">FISIK</span>
        </div>

        <!-- Decorative Light Sheen -->
        <div class="absolute inset-0 bg-gradient-to-tr from-black/20 via-transparent to-white/25 pointer-events-none"></div>
      </div>
    </div>

    <!-- Book Info Section -->
    <div class="flex-1 flex flex-col justify-between">
      <div>
        <div class="flex items-center gap-1 text-amber-500 text-xs font-semibold mb-1">
          <Star class="w-3.5 h-3.5 fill-amber-400 text-amber-400" />
          <span>{{ book.rating.toFixed(1) }}</span>
          <span class="text-slate-400 font-normal">({{ book.ratingCount }})</span>
          <span class="text-slate-300 mx-1">•</span>
          <span class="text-slate-500 text-[11px]">{{ book.borrowCount }}x dipinjam</span>
        </div>

        <h3
          class="font-heading font-bold text-slate-800 text-sm md:text-base leading-snug line-clamp-2 hover:text-[#1E4FA3] cursor-pointer transition-colors"
          @click="libraryStore.openDetail(book)"
        >
          {{ book.title }}
        </h3>

        <p class="text-xs text-slate-500 mt-1 line-clamp-1">
          {{ book.author }}
        </p>

        <!-- Availability Indicator -->
        <div class="mt-2.5 flex items-center justify-between text-xs">
          <span v-if="book.isDigital" class="text-emerald-700 font-medium flex items-center gap-1">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            Akses Digital Seketika
          </span>
          <span v-else-if="book.availableStock > 0" class="text-blue-700 font-medium flex items-center gap-1">
            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
            Tersedia ({{ book.availableStock }}/{{ book.stock }} buku)
          </span>
          <span v-else class="text-rose-600 font-medium flex items-center gap-1">
            <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
            Stok Habis (Reservasi)
          </span>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-2">
        <!-- If Digital, Provide Direct Reader Button -->
        <ClayButton
          v-if="book.isDigital"
          variant="red"
          size="sm"
          class="flex-1"
          @click="libraryStore.openReader(book)"
        >
          <template #leading>
            <BookOpen class="w-3.5 h-3.5" />
          </template>
          Baca Sekarang
        </ClayButton>

        <!-- If Physical or Dual, Borrow Button -->
        <ClayButton
          v-else
          variant="blue"
          size="sm"
          class="flex-1"
          :disabled="book.availableStock <= 0 || isBorrowed"
          @click="libraryStore.borrowBook(book)"
        >
          <template #leading>
            <CheckCircle2 v-if="isBorrowed" class="w-3.5 h-3.5" />
            <BookmarkPlus v-else class="w-3.5 h-3.5" />
          </template>
          {{ isBorrowed ? 'Dipinjam' : (book.availableStock > 0 ? 'Pinjam Fisik' : 'Antre Reservasi') }}
        </ClayButton>

        <ClayButton
          variant="white"
          size="sm"
          class="px-2.5"
          title="Detail Koleksi"
          @click="libraryStore.openDetail(book)"
        >
          <Info class="w-3.5 h-3.5 text-slate-600" />
        </ClayButton>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useLibraryStore } from '../../stores/library'
import ClayBadge from '../ui/ClayBadge.vue'
import ClayButton from '../ui/ClayButton.vue'
import {
  Heart,
  Star,
  BookOpen,
  BookmarkPlus,
  Info,
  CheckCircle2,
  GraduationCap,
  FileText,
  BookMarked
} from 'lucide-vue-next'

const props = defineProps({
  book: {
    type: Object,
    required: true,
  }
})

const libraryStore = useLibraryStore()

const isWishlisted = computed(() => libraryStore.isBookInWishlist(props.book.id))
const isBorrowed = computed(() => libraryStore.isBookBorrowed(props.book.id))

const formatBadgeVariant = computed(() => {
  if (props.book.formatType === 'pdf') return 'red'
  if (props.book.formatType === 'epub') return 'amber'
  if (props.book.formatType === 'hybrid') return 'blue'
  return 'emerald'
})

const formatIcon = computed(() => {
  if (props.book.formatType === 'pdf') return FileText
  if (props.book.formatType === 'epub') return BookOpen
  return BookMarked
})
</script>
