<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-8 pb-20 md:pb-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-bold mb-2">
          <Sparkles class="w-3.5 h-3.5" /> CLAYMORPHISM DESIGN SYSTEM LAB
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">
          Laboratorium Desain & Komponen Claymorphism
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl">
          Dokumentasi visual hidup untuk prinsip desain DIGIPUS: <span class="font-semibold text-slate-700">Soft surfaces, border radius 20–32px, dual soft shadow (outer + inner bevel), dan press-in micro-interaction</span>.
        </p>
      </div>

      <ClayButton
        variant="red"
        size="sm"
        @click="triggerConfettiToast"
      >
        <template #leading>
          <Sparkles class="w-4 h-4" />
        </template>
        Uji Efek Interaktif
      </ClayButton>
    </div>

    <!-- 1. COLOR TOKENS SWATCHES (MERAH - BIRU - PUTIH) -->
    <div class="clay-card p-6 sm:p-8 space-y-4 border border-white">
      <h2 class="text-base sm:text-lg font-bold font-heading text-slate-800 flex items-center gap-2">
        <Palette class="w-5 h-5 text-blue-600" /> Palet Warna Resmi DIGIPUS (CLAUDE.md Bagian 3.2)
      </h2>

      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <!-- Merah Digitech -->
        <div class="clay-card p-4 bg-white border border-white text-center space-y-2">
          <div class="w-full h-16 rounded-2xl bg-[#D92B3E] shadow-[0_8px_18px_rgba(217,43,62,0.35)] flex items-center justify-center text-white font-mono text-xs font-bold">
            #D92B3E
          </div>
          <div class="text-xs font-bold text-slate-800">Merah Digitech</div>
          <div class="text-[10px] text-slate-400">Primary Accent & CTA</div>
        </div>

        <!-- Biru Digitech -->
        <div class="clay-card p-4 bg-white border border-white text-center space-y-2">
          <div class="w-full h-16 rounded-2xl bg-[#1E4FA3] shadow-[0_8px_18px_rgba(30,79,163,0.35)] flex items-center justify-center text-white font-mono text-xs font-bold">
            #1E4FA3
          </div>
          <div class="text-xs font-bold text-slate-800">Biru Digitech</div>
          <div class="text-[10px] text-slate-400">Secondary & Branding</div>
        </div>

        <!-- Putih Card -->
        <div class="clay-card p-4 bg-white border border-white text-center space-y-2">
          <div class="w-full h-16 rounded-2xl bg-[#FFFFFF] border border-slate-200 shadow-sm flex items-center justify-center text-slate-700 font-mono text-xs font-bold">
            #FFFFFF
          </div>
          <div class="text-xs font-bold text-slate-800">Putih Bersih</div>
          <div class="text-[10px] text-slate-400">Permukaan Kartu 3D</div>
        </div>

        <!-- Netral Kanvas -->
        <div class="clay-card p-4 bg-white border border-white text-center space-y-2">
          <div class="w-full h-16 rounded-2xl bg-[#F4F6FB] border border-slate-200 shadow-inner flex items-center justify-center text-slate-700 font-mono text-xs font-bold">
            #F4F6FB
          </div>
          <div class="text-xs font-bold text-slate-800">Abu Lembut / BG</div>
          <div class="text-[10px] text-slate-400">Kanvas Kontras Clay</div>
        </div>
      </div>
    </div>

    <!-- 2. CLAY BUTTONS WITH PRESS-IN MICRO-INTERACTION -->
    <div class="clay-card p-6 sm:p-8 space-y-6 border border-white">
      <div>
        <h2 class="text-base sm:text-lg font-bold font-heading text-slate-800 flex items-center gap-2">
          <MousePointerClick class="w-5 h-5 text-red-500" /> Tombol Clay 3D (Press-In Micro-Interactions)
        </h2>
        <p class="text-xs text-slate-500 mt-1">
          Klik atau tap dan tahan tombol di bawah untuk melihat efek pegas membal (*press-in micro-animation*) serta perubahan shadow inset.
        </p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Red Variant -->
        <div class="clay-inset p-5 rounded-2xl text-center space-y-3">
          <div class="text-xs font-bold text-slate-700">Varian Merah (Primary)</div>
          <div>
            <ClayButton variant="red" size="md">
              <template #leading>
                <Flame class="w-4 h-4" />
              </template>
              Pinjam Sekarang
            </ClayButton>
          </div>
          <div class="text-[10px] text-slate-400 font-mono">class="clay-btn-red"</div>
        </div>

        <!-- Blue Variant -->
        <div class="clay-inset p-5 rounded-2xl text-center space-y-3">
          <div class="text-xs font-bold text-slate-700">Varian Biru (Secondary)</div>
          <div>
            <ClayButton variant="blue" size="md">
              <template #leading>
                <Bookmark class="w-4 h-4" />
              </template>
              Koleksi Akademik
            </ClayButton>
          </div>
          <div class="text-[10px] text-slate-400 font-mono">class="clay-btn-blue"</div>
        </div>

        <!-- White Variant -->
        <div class="clay-inset p-5 rounded-2xl text-center space-y-3">
          <div class="text-xs font-bold text-slate-700">Varian Putih (Clay Surface)</div>
          <div>
            <ClayButton variant="white" size="md">
              <template #leading>
                <Info class="w-4 h-4 text-slate-600" />
              </template>
              Detail Eksemplar
            </ClayButton>
          </div>
          <div class="text-[10px] text-slate-400 font-mono">class="clay-btn-white"</div>
        </div>
      </div>

      <!-- Button Sizes -->
      <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center gap-4">
        <span class="text-xs font-bold text-slate-600">Variasi Ukuran:</span>
        <ClayButton variant="blue" size="sm">Small (sm)</ClayButton>
        <ClayButton variant="blue" size="md">Medium (md)</ClayButton>
        <ClayButton variant="blue" size="lg">Large (lg)</ClayButton>
      </div>
    </div>

    <!-- 3. CLAY CARDS & INSET SURFACES -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Outer Clay Card -->
      <div class="clay-card clay-card-hover p-6 rounded-[28px] space-y-3 border border-white">
        <div class="text-xs font-bold uppercase tracking-wider text-blue-600">
          OUTER ELEVATION (CLAY-CARD)
        </div>
        <h3 class="text-base font-bold font-heading text-slate-800">
          Permukaan Clay Cembung
        </h3>
        <p class="text-xs text-slate-500 leading-relaxed">
          Menggunakan dual outer soft shadow yang lembut dikombinasikan dengan bevel highlight internal 1.5px. Arahkan kursor untuk melihat elevasi melayang (hover).
        </p>
        <div class="text-[10px] font-mono text-slate-400 p-2 bg-slate-50 rounded-xl">
          box-shadow: 12px 16px 28px rgba(30,79,163,0.07), -10px -10px 24px #fff, inset 2px 2px 4px rgba(255,255,255,0.95);
        </div>
      </div>

      <!-- Inset Clay Surface -->
      <div class="clay-card p-6 rounded-[28px] space-y-3 border border-white">
        <div class="text-xs font-bold uppercase tracking-wider text-[#D92B3E]">
          INNER CONCAVE (CLAY-INSET)
        </div>
        <h3 class="text-base font-bold font-heading text-slate-800">
          Permukaan Cekung ke Dalam
        </h3>
        <p class="text-xs text-slate-500 leading-relaxed">
          Sangat cocok untuk wadah sekunder, kolom input pencarian, atau area form agar elemen terlihat tertanam alami di atas kanvas tanah liat.
        </p>
        <div class="clay-inset p-3 rounded-2xl text-[10px] font-mono text-slate-600">
          box-shadow: inset 4px 4px 8px rgba(25,60,120,0.08), inset -4px -4px 8px #fff;
        </div>
      </div>
    </div>

    <!-- 4. CLAY BADGES & INPUTS -->
    <div class="clay-card p-6 sm:p-8 space-y-6 border border-white">
      <h2 class="text-base sm:text-lg font-bold font-heading text-slate-800 flex items-center gap-2">
        <CheckCircle2 class="w-5 h-5 text-emerald-600" /> Komponen Input Cekung & Badge Pill 3D
      </h2>

      <!-- Inputs -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">Input Text Sunken Clay:</label>
          <ClayInput
            v-model="sampleInput"
            placeholder="Ketik sesuatu untuk menguji input..."
          >
            <template #leading>
              <Search class="w-4 h-4" />
            </template>
          </ClayInput>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">Hasil Real-time:</label>
          <div class="clay-inset px-4 py-3 rounded-2xl text-xs sm:text-sm text-slate-700 font-medium">
            {{ sampleInput || 'Hasil teks akan muncul di sini secara dinamis...' }}
          </div>
        </div>
      </div>

      <!-- Badges -->
      <div class="pt-4 border-t border-slate-100 space-y-2">
        <label class="block text-xs font-bold text-slate-700">Variasi Badge Status Pill (Soft Clay):</label>
        <div class="flex flex-wrap gap-2">
          <ClayBadge variant="red" size="sm" :dot="true">Terlambat / Urgent</ClayBadge>
          <ClayBadge variant="blue" size="sm" :dot="true">Dipinjam (Aktif)</ClayBadge>
          <ClayBadge variant="emerald" size="sm" :dot="true">Tersedia di Rak</ClayBadge>
          <ClayBadge variant="amber" size="sm" :dot="true">Antrean Reservasi</ClayBadge>
          <ClayBadge variant="slate" size="sm">E-Book PDF</ClayBadge>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useLibraryStore } from '../stores/library'
import ClayButton from '../components/ui/ClayButton.vue'
import ClayInput from '../components/ui/ClayInput.vue'
import ClayBadge from '../components/ui/ClayBadge.vue'
import {
  Sparkles,
  Palette,
  MousePointerClick,
  Flame,
  Bookmark,
  Info,
  CheckCircle2,
  Search
} from 'lucide-vue-next'

const libraryStore = useLibraryStore()
const sampleInput = ref('')

function triggerConfettiToast() {
  libraryStore.showToast('Claymorphism 3D Engine Berjalan Sempurna! Micro-interaction Aktif.', 'success')
}
</script>
