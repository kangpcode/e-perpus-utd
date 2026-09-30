<template>
  <header class="sticky top-0 z-40 px-3 sm:px-6 py-3 transition-all">
    <div class="max-w-7xl mx-auto clay-nav rounded-[24px] px-4 sm:px-6 py-3 flex items-center justify-between gap-4 border border-white/80 shadow-md">
      <!-- Brand Logo -->
      <router-link to="/" class="flex items-center gap-3 shrink-0 group">
        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[#1E4FA3] to-[#D92B3E] p-0.5 shadow-[0_6px_14px_rgba(30,79,163,0.35)] flex items-center justify-center transition-transform group-hover:scale-105 active:scale-95">
          <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center">
            <BookMarked class="w-5 h-5 text-[#1E4FA3] group-hover:text-[#D92B3E] transition-colors" />
          </div>
        </div>
        <div>
          <div class="flex items-center gap-1.5">
            <span class="font-heading font-extrabold text-lg tracking-tight bg-gradient-to-r from-[#1E4FA3] via-blue-800 to-[#D92B3E] bg-clip-text text-transparent">
              DIGIPUS
            </span>
            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-red-100 text-[#D92B3E]">
              v2.0
            </span>
          </div>
          <p class="text-[10px] text-slate-500 font-medium leading-none hidden sm:block">
            Digitech University Library
          </p>
        </div>
      </router-link>

      <!-- Desktop Nav Links -->
      <nav class="hidden md:flex items-center gap-1.5 text-sm font-semibold">
        <router-link
          to="/"
          class="px-3.5 py-2 rounded-xl transition-all"
          :class="[$route.path === '/' ? 'bg-blue-50 text-[#1E4FA3] shadow-inner font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/60']"
        >
          Beranda
        </router-link>

        <router-link
          to="/katalog"
          class="px-3.5 py-2 rounded-xl transition-all"
          :class="[$route.path === '/katalog' ? 'bg-blue-50 text-[#1E4FA3] shadow-inner font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/60']"
        >
          Katalog Koleksi
        </router-link>

        <router-link
          to="/rak-saya"
          class="px-3.5 py-2 rounded-xl transition-all relative flex items-center gap-1.5"
          :class="[$route.path === '/rak-saya' ? 'bg-blue-50 text-[#1E4FA3] shadow-inner font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/60']"
        >
          <span>Rak Saya</span>
          <span
            v-if="libraryStore.loans.length > 0"
            class="px-1.5 py-0.2 text-[10px] rounded-full bg-[#D92B3E] text-white font-bold"
          >
            {{ libraryStore.loans.length }}
          </span>
        </router-link>

        <router-link
          to="/roles"
          class="px-3.5 py-2 rounded-xl transition-all"
          :class="[$route.path === '/roles' ? 'bg-blue-50 text-[#1E4FA3] shadow-inner font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/60']"
        >
          Multi-Role
        </router-link>

      </nav>

      <!-- Right: Role Switcher & User Profile Capsule -->
      <div class="flex items-center gap-2 sm:gap-3">
        <!-- Role Selector Dropdown -->
        <div class="relative">
          <button
            type="button"
            class="clay-inset px-2.5 sm:px-3 py-1.5 text-xs font-semibold rounded-xl flex items-center gap-1.5 hover:bg-slate-200/50 transition-all text-slate-700"
            @click="showRoleDropdown = !showRoleDropdown"
          >
            <ShieldCheck class="w-3.5 h-3.5 text-[#1E4FA3]" />
            <span class="capitalize hidden xs:inline">{{ libraryStore.currentRole }}</span>
            <ChevronDown class="w-3 h-3 text-slate-400" />
          </button>

          <!-- Dropdown Menu -->
          <div
            v-if="showRoleDropdown"
            class="absolute right-0 mt-2 w-48 clay-card p-2 bg-white rounded-2xl shadow-xl z-50 border border-slate-100"
          >
            <div class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
              Ganti Role Simulasi
            </div>
            <button
              v-for="role in rolesList"
              :key="role.id"
              type="button"
              class="w-full text-left px-3 py-2 text-xs rounded-xl flex items-center justify-between transition-colors"
              :class="libraryStore.currentRole === role.id ? 'bg-blue-50 text-[#1E4FA3] font-bold' : 'hover:bg-slate-50 text-slate-700'"
              @click="selectRole(role.id)"
            >
              <span>{{ role.label }}</span>
              <Check v-if="libraryStore.currentRole === role.id" class="w-3.5 h-3.5 text-[#1E4FA3]" />
            </button>
          </div>
        </div>

        <!-- User Profile Avatar Pill -->
        <div class="clay-card py-1 px-2.5 sm:px-3 rounded-2xl flex items-center gap-2 border border-white/70">
          <div class="w-7 h-7 rounded-xl bg-gradient-to-tr from-[#1E4FA3] to-[#2563EB] text-white flex items-center justify-center text-xs font-bold shadow-sm">
            {{ libraryStore.userProfile.name.charAt(0) }}
          </div>
          <div class="hidden lg:block text-left">
            <div class="text-xs font-bold text-slate-800 leading-tight">
              {{ libraryStore.userProfile.name }}
            </div>
            <div class="text-[10px] text-slate-400 font-mono">
              {{ libraryStore.userProfile.nim }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref } from 'vue'
import { useLibraryStore } from '../../stores/library'
import {
  BookMarked,
  ShieldCheck,
  ChevronDown,
  Check
} from 'lucide-vue-next'

const libraryStore = useLibraryStore()
const showRoleDropdown = ref(false)

const rolesList = [
  { id: 'mahasiswa', label: 'Mahasiswa (Default)' },
  { id: 'dosen', label: 'Dosen' },
  { id: 'pustakawan', label: 'Pustakawan / Staff' },
  { id: 'admin', label: 'Super Admin' },
  { id: 'tamu', label: 'Tamu / Publik' },
]

function selectRole(roleId) {
  libraryStore.setRole(roleId)
  showRoleDropdown.value = false
}
</script>
