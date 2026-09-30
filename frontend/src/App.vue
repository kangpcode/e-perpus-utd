<template>
  <div class="min-h-screen flex flex-col bg-[#F4F6FB] relative">
    <!-- Top Sticky Navbar -->
    <Navbar />

    <!-- Main View Content -->
    <main class="flex-1">
      <router-view v-slot="{ Component }">
        <Transition
          enter-active-class="transition duration-200 ease-out"
          enter-from-class="opacity-0 translate-y-1"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition duration-150 ease-in"
          leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 translate-y-1"
          mode="out-in"
        >
          <component :is="Component" />
        </Transition>
      </router-view>
    </main>

    <!-- Modals -->
    <BookDetailModal />
    <EbookReaderModal />

    <!-- Mobile Bottom Navigation (<768px) -->
    <MobileBottomNav />

    <!-- Interactive Toast Notification Component -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="opacity-0 translate-y-6 scale-95"
        enter-to-class="opacity-100 translate-y-0 scale-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0 scale-100"
        leave-to-class="opacity-0 translate-y-6 scale-95"
      >
        <div
          v-if="libraryStore.toastMessage"
          class="fixed bottom-20 md:bottom-6 right-4 sm:right-8 z-50 clay-card px-5 py-3.5 rounded-2xl flex items-center gap-3 shadow-2xl border border-white max-w-md"
        >
          <div
            :class="[
              'w-8 h-8 rounded-xl flex items-center justify-center text-white shrink-0 shadow-sm font-bold text-xs',
              libraryStore.toastType === 'success' ? 'bg-emerald-500' :
              libraryStore.toastType === 'error' ? 'bg-[#D92B3E]' : 'bg-[#1E4FA3]'
            ]"
          >
            <CheckCircle2 v-if="libraryStore.toastType === 'success'" class="w-4 h-4" />
            <AlertCircle v-else-if="libraryStore.toastType === 'error'" class="w-4 h-4" />
            <Info v-else class="w-4 h-4" />
          </div>
          <div class="text-xs sm:text-sm font-semibold text-slate-800 leading-snug">
            {{ libraryStore.toastMessage }}
          </div>
          <button
            type="button"
            class="text-slate-400 hover:text-slate-700 ml-2"
            @click="libraryStore.toastMessage = null"
          >
            ✕
          </button>
        </div>
      </Transition>
    </Teleport>

    <!-- Desktop Footer (Hanya tampil di layar desktop / md ke atas) -->
    <footer class="hidden md:block mt-auto border-t border-slate-200/80 bg-white/60 backdrop-blur-md pt-10 pb-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
          <!-- Col 1: Brand -->
          <div class="md:col-span-2 space-y-3">
            <div class="flex items-center gap-2.5">
              <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-[#1E4FA3] to-[#D92B3E] flex items-center justify-center text-white font-bold text-xs">
                D
              </div>
              <span class="font-heading font-extrabold text-lg text-slate-800 tracking-tight">
                DIGIPUS DIGITECH
              </span>
            </div>
            <p class="text-xs text-slate-500 leading-relaxed max-w-md">
              Digital Library & Pustaka Terpadu Digitech University. Mengintegrasikan koleksi buku fisik, e-book akademik, skripsi, dan jurnal ilmiah dengan estetika visual Claymorphism.
            </p>
            <div class="text-[11px] text-slate-400">
              Gedung Perpustakaan Pusat, Lt. 1-3 • Kampus Utama Digitech University
            </div>
          </div>

          <!-- Col 2: Navigasi Cepat -->
          <div class="space-y-2">
            <h4 class="text-xs font-bold font-heading text-slate-800 uppercase tracking-wider">
              Layanan Kampus
            </h4>
            <ul class="text-xs text-slate-500 space-y-1.5">
              <li><router-link to="/katalog" class="hover:text-blue-700">Katalog Buku & E-Book</router-link></li>
              <li><router-link to="/rak-saya" class="hover:text-blue-700">Peminjaman & Perpanjangan</router-link></li>
              <li><router-link to="/roles" class="hover:text-blue-700">Portal Role & Hak Akses</router-link></li>
              <li><router-link to="/clay-playground" class="hover:text-blue-700">Claymorphism Design System</router-link></li>
            </ul>
          </div>
        </div>

        <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400">
          <div>
            © 2026 UPT Perpustakaan Digitech University. Hak Cipta Dilindungi.
          </div>
          <div class="flex items-center gap-4">
            <span class="text-emerald-600 font-semibold flex items-center gap-1">
              <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
              Server Status: Normal
            </span>
            <span>Versi 2.0.0-PROTOTYPE</span>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useLibraryStore } from './stores/library'
import Navbar from './components/layout/Navbar.vue'
import MobileBottomNav from './components/layout/MobileBottomNav.vue'
import BookDetailModal from './components/books/BookDetailModal.vue'
import EbookReaderModal from './components/books/EbookReaderModal.vue'
import {
  CheckCircle2,
  AlertCircle,
  Info
} from 'lucide-vue-next'

const libraryStore = useLibraryStore()

onMounted(() => {
  libraryStore.initFromBackend()
})
</script>
