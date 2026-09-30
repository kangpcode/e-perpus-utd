<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="book"
        class="fixed inset-0 z-50 flex flex-col bg-slate-950/70 backdrop-blur-md"
      >
        <!-- Reader Container -->
        <div
          :class="[
            'w-full h-full flex flex-col transition-colors duration-300',
            currentThemeClasses.bg,
            currentThemeClasses.text
          ]"
        >
          <!-- Top Reader Navigation Bar -->
          <div
            :class="[
              'px-4 py-3 flex items-center justify-between border-b shadow-sm select-none shrink-0 transition-colors',
              currentThemeClasses.headerBorder,
              currentThemeClasses.headerBg
            ]"
          >
            <!-- Left: Title & University Branding -->
            <div class="flex items-center gap-3 min-w-0">
              <button
                type="button"
                class="p-2 rounded-xl hover:bg-black/10 active:scale-95 transition-all text-sm font-semibold flex items-center gap-1.5"
                @click="libraryStore.closeReader()"
              >
                <ArrowLeft class="w-4 h-4" />
                <span class="hidden sm:inline">Tutup Reader</span>
              </button>

              <div class="h-5 w-px bg-slate-300/40"></div>

              <div class="min-w-0">
                <h3 class="text-xs sm:text-sm font-bold truncate max-w-xs md:max-w-md font-heading">
                  {{ book.title }}
                </h3>
                <p class="text-[10px] opacity-75 truncate">
                  {{ book.author }} • Digitech Digital DRM Active
                </p>
              </div>
            </div>

            <!-- Right: Controls (Theme, Font Size, DRM Info) -->
            <div class="flex items-center gap-2">
              <!-- Font Size Controls -->
              <div class="flex items-center bg-black/5 rounded-xl p-0.5">
                <button
                  type="button"
                  class="px-2 py-1 text-xs font-bold rounded-lg hover:bg-black/10 active:scale-95"
                  title="Perkecil Font"
                  @click="fontSize = Math.max(13, fontSize - 1)"
                >
                  A-
                </button>
                <span class="text-[11px] px-1 font-mono font-semibold">{{ fontSize }}px</span>
                <button
                  type="button"
                  class="px-2 py-1 text-xs font-bold rounded-lg hover:bg-black/10 active:scale-95"
                  title="Perbesar Font"
                  @click="fontSize = Math.min(22, fontSize + 1)"
                >
                  A+
                </button>
              </div>

              <!-- Theme Selector -->
              <div class="flex items-center bg-black/5 rounded-xl p-0.5 text-xs">
                <button
                  v-for="t in themes"
                  :key="t.id"
                  type="button"
                  :class="[
                    'px-2.5 py-1 rounded-lg font-medium transition-all',
                    selectedTheme === t.id ? 'bg-white shadow-sm text-slate-900 font-bold' : 'opacity-70 hover:opacity-100'
                  ]"
                  @click="selectedTheme = t.id"
                >
                  {{ t.label }}
                </button>
              </div>

              <!-- Close Icon -->
              <button
                type="button"
                class="p-2 rounded-xl hover:bg-black/10 active:scale-95"
                @click="libraryStore.closeReader()"
              >
                <X class="w-5 h-5" />
              </button>
            </div>
          </div>

          <!-- Reader Reading Canvas (Scrollable) with Simulated DRM Watermark -->
          <div class="flex-1 overflow-y-auto p-4 md:p-8 flex justify-center relative">
            <!-- Simulated Security Watermark Layer -->
            <div class="absolute inset-0 pointer-events-none select-none overflow-hidden opacity-10 flex flex-wrap gap-24 p-12 justify-around items-center z-10">
              <div
                v-for="n in 12"
                :key="n"
                class="rotate-[-25deg] text-xs font-bold font-mono tracking-widest uppercase border border-current px-3 py-1 rounded"
              >
                DIGIPUS DIGITECH • {{ libraryStore.userProfile.nim }} • {{ libraryStore.userProfile.name }}
              </div>
            </div>

            <!-- Page Paper Container -->
            <div
              :class="[
                'w-full max-w-3xl rounded-2xl p-6 sm:p-10 md:p-14 shadow-lg transition-all relative z-20 flex flex-col justify-between min-h-[75vh]',
                currentThemeClasses.paperBg,
                currentThemeClasses.paperShadow
              ]"
            >
              <!-- Content -->
              <div class="space-y-6" :style="{ fontSize: `${fontSize}px`, lineHeight: 1.8 }">
                <!-- Chapter Heading -->
                <div class="border-b pb-4 mb-6" :class="currentThemeClasses.divider">
                  <div class="text-[11px] font-bold tracking-wider uppercase text-blue-600 mb-1">
                    DIGITECH UNIVERSITY DIGITAL REPOSITORY • HALAMAN {{ currentPage }} DARI {{ totalPages }}
                  </div>
                  <h2 class="text-xl sm:text-2xl font-bold font-heading">
                    {{ book.title }}
                  </h2>
                  <p class="text-xs opacity-75 mt-1 font-mono">
                    ISBN: {{ book.isbn }} | Penerbit: {{ book.publisher }} ({{ book.year }})
                  </p>
                </div>

                <!-- Paragraphs -->
                <div
                  v-for="(para, idx) in book.sampleContent"
                  :key="idx"
                  class="text-justify font-serif leading-relaxed"
                >
                  <p class="first-letter:text-3xl first-letter:font-bold first-letter:mr-1 first-letter:float-left first-letter:text-[#D92B3E]">
                    {{ para }}
                  </p>
                </div>

                <div class="clay-inset p-5 rounded-2xl my-6 text-sm italic font-sans opacity-90 border-l-4 border-blue-600">
                  "Koleksi digital ini dilindungi oleh Kebijakan Hak Cipta & Repositori Ilmiah Digitech University. Akses diberikan untuk sivitas akademika guna keperluan penelitian dan pembelajaran mandiri."
                </div>

                <p class="text-justify font-serif">
                  Model peminjaman digital (Digital Lending DRM-Lite) DIGIPUS memastikan bahwa mahasiswa dapat mengunduh salinan terenkripsi sementara untuk dibaca secara offline (PWA) tanpa risiko penyebaran ilegal di luar domain akademik universitas.
                </p>
              </div>

              <!-- Page Footer Navigation within Paper -->
              <div
                class="mt-12 pt-6 border-t flex flex-col sm:flex-row items-center justify-between gap-4 text-xs select-none"
                :class="currentThemeClasses.divider"
              >
                <div class="flex items-center gap-2">
                  <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                  <span class="font-medium">Mode DRM Aktif • Watermark Terverifikasi</span>
                </div>

                <div class="flex items-center gap-3">
                  <button
                    type="button"
                    :disabled="currentPage <= 1"
                    :class="[
                      'px-3.5 py-1.5 rounded-xl font-semibold transition-all flex items-center gap-1 active:scale-95',
                      currentPage <= 1 ? 'opacity-40 cursor-not-allowed' : 'bg-black/10 hover:bg-black/15'
                    ]"
                    @click="currentPage = Math.max(1, currentPage - 1)"
                  >
                    <ChevronLeft class="w-4 h-4" /> Hal Sebelumnya
                  </button>

                  <span class="font-mono font-bold">{{ currentPage }} / {{ totalPages }}</span>

                  <button
                    type="button"
                    :disabled="currentPage >= totalPages"
                    :class="[
                      'px-3.5 py-1.5 rounded-xl font-semibold transition-all flex items-center gap-1 active:scale-95',
                      currentPage >= totalPages ? 'opacity-40 cursor-not-allowed' : 'bg-black/10 hover:bg-black/15'
                    ]"
                    @click="currentPage = Math.min(totalPages, currentPage + 1)"
                  >
                    Hal Berikutnya <ChevronRight class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Bottom Progress Bar -->
          <div class="h-1.5 w-full bg-slate-200 shrink-0 overflow-hidden">
            <div
              class="h-full bg-gradient-to-r from-red-500 to-blue-600 transition-all duration-300"
              :style="{ width: `${(currentPage / totalPages) * 100}%` }"
            ></div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useLibraryStore } from '../../stores/library'
import {
  ArrowLeft,
  X,
  ChevronLeft,
  ChevronRight
} from 'lucide-vue-next'

const libraryStore = useLibraryStore()
const book = computed(() => libraryStore.activeReadingBook)

const fontSize = ref(16)
const currentPage = ref(1)
const totalPages = ref(14)
const selectedTheme = ref('day')

const themes = [
  { id: 'day', label: 'Day' },
  { id: 'sepia', label: 'Sepia' },
  { id: 'night', label: 'Night' },
]

const currentThemeClasses = computed(() => {
  if (selectedTheme.value === 'sepia') {
    return {
      bg: 'bg-[#F4ECD8]',
      text: 'text-[#5C4B37]',
      headerBg: 'bg-[#EDE2C8]',
      headerBorder: 'border-[#DFCFAF]',
      paperBg: 'bg-[#FAF4E6]',
      paperShadow: 'shadow-[0_10px_30px_rgba(92,75,55,0.15)]',
      divider: 'border-[#DFCFAF]'
    }
  } else if (selectedTheme.value === 'night') {
    return {
      bg: 'bg-[#0F172A]',
      text: 'text-[#E2E8F0]',
      headerBg: 'bg-[#1E293B]',
      headerBorder: 'border-slate-800',
      paperBg: 'bg-[#1E293B]',
      paperShadow: 'shadow-[0_10px_30px_rgba(0,0,0,0.5)]',
      divider: 'border-slate-700'
    }
  }
  // Day (default)
  return {
    bg: 'bg-[#F1F5F9]',
    text: 'text-slate-800',
    headerBg: 'bg-white',
    headerBorder: 'border-slate-200',
    paperBg: 'bg-white',
    paperShadow: 'shadow-[0_12px_36px_rgba(30,79,163,0.1)]',
    divider: 'border-slate-100'
  }
})
</script>
