<template>
  <nav class="md:hidden fixed bottom-3 left-3 right-3 z-40">
    <div class="clay-nav rounded-[24px] px-3 py-2 flex items-center justify-around border border-white/80 shadow-2xl">
      <router-link
        to="/"
        class="flex flex-col items-center py-1 px-2.5 rounded-xl transition-all"
        :class="[$route.path === '/' ? 'text-[#1E4FA3] font-bold scale-105' : 'text-slate-400 hover:text-slate-600']"
      >
        <Home class="w-5 h-5" />
        <span class="text-[10px] mt-0.5">Beranda</span>
      </router-link>

      <router-link
        to="/katalog"
        class="flex flex-col items-center py-1 px-2.5 rounded-xl transition-all"
        :class="[$route.path === '/katalog' ? 'text-[#1E4FA3] font-bold scale-105' : 'text-slate-400 hover:text-slate-600']"
      >
        <BookOpen class="w-5 h-5" />
        <span class="text-[10px] mt-0.5">Katalog</span>
      </router-link>

      <router-link
        to="/rak-saya"
        class="flex flex-col items-center py-1 px-2.5 rounded-xl transition-all relative"
        :class="[$route.path === '/rak-saya' ? 'text-[#1E4FA3] font-bold scale-105' : 'text-slate-400 hover:text-slate-600']"
      >
        <div class="relative">
          <Bookmark class="w-5 h-5" />
          <span
            v-if="libraryStore.loans.length > 0"
            class="absolute -top-1.5 -right-2 px-1 text-[9px] bg-[#D92B3E] text-white rounded-full font-bold"
          >
            {{ libraryStore.loans.length }}
          </span>
        </div>
        <span class="text-[10px] mt-0.5">Rak Saya</span>
      </router-link>

      <router-link
        to="/roles"
        class="flex flex-col items-center py-1 px-2.5 rounded-xl transition-all"
        :class="[$route.path === '/roles' ? 'text-[#1E4FA3] font-bold scale-105' : 'text-slate-400 hover:text-slate-600']"
      >
        <ShieldCheck class="w-5 h-5" />
        <span class="text-[10px] mt-0.5">Role</span>
      </router-link>


    </div>
  </nav>
</template>

<script setup>
import { useLibraryStore } from '../../stores/library'
import {
  Home,
  BookOpen,
  Bookmark,
  ShieldCheck
} from 'lucide-vue-next'

const libraryStore = useLibraryStore()
</script>
