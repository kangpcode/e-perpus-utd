<template>
  <ClayModal
    :is-open="isOpen"
    custom-class="max-w-2xl"
    @close="$emit('close')"
  >
    <template #header>
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 rounded-xl bg-blue-100 text-[#1E4FA3] flex items-center justify-center font-bold">
          <BookPlus class="w-4 h-4" />
        </div>
        <div>
          <h3 class="text-lg font-bold font-heading text-slate-800">
            Tambah Koleksi Baru (Pustakawan)
          </h3>
          <p class="text-xs text-slate-500">
            Katalog buku fisik dan e-book repositori Digitech University
          </p>
        </div>
      </div>
    </template>

    <form @submit.prevent="submitBook" class="space-y-4 pt-2">
      <!-- Judul Buku -->
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Judul Buku *</label>
        <ClayInput
          v-model="form.title"
          placeholder="Masukkan judul buku lengkap..."
          required
        />
      </div>

      <!-- Grid 2 Col: ISBN & Pengarang -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">ISBN / Barcode *</label>
          <ClayInput
            v-model="form.isbn"
            placeholder="Contoh: 978-623-8812-99-9"
            required
          />
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Nama Penulis / Pengarang *</label>
          <ClayInput
            v-model="form.author_name"
            placeholder="Nama lengkap penulis..."
            required
          />
        </div>
      </div>

      <!-- Grid 3 Col: Kategori, Format, Tahun -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Keilmuan *</label>
          <select
            v-model="form.category_id"
            class="clay-inset w-full px-3 py-2.5 rounded-2xl text-xs font-medium text-slate-700 border-none focus:outline-none focus:ring-2 focus:ring-blue-400"
            required
          >
            <option value="1">Teknologi Informasi & AI</option>
            <option value="2">Bisnis Digital & FinTech</option>
            <option value="3">DKV & UI/UX</option>
            <option value="4">Sains Data & Analitika</option>
            <option value="5">Keamanan Siber & Jaringan</option>
            <option value="6">Karya Ilmiah & Jurnal</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Format Koleksi *</label>
          <select
            v-model="form.format_type"
            class="clay-inset w-full px-3 py-2.5 rounded-2xl text-xs font-medium text-slate-700 border-none focus:outline-none focus:ring-2 focus:ring-blue-400"
            required
          >
            <option value="physical">Buku Fisik</option>
            <option value="pdf">E-Book PDF</option>
            <option value="epub">E-Book EPUB</option>
            <option value="hybrid">Fisik + E-Book (Hybrid)</option>
          </select>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Terbit *</label>
          <ClayInput
            v-model="form.publication_year"
            type="number"
            placeholder="2025"
            required
          />
        </div>
      </div>

      <!-- Grid 2 Col: Halaman & Stok / Eksemplar -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Halaman *</label>
          <ClayInput
            v-model="form.pages"
            type="number"
            placeholder="Contoh: 350"
            required
          />
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Eksemplar / Salinan *</label>
          <ClayInput
            v-model="form.total_stock"
            type="number"
            placeholder="Contoh: 5"
            required
          />
        </div>
      </div>

      <!-- Lokasi Rak Fisik -->
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Lokasi Rak Perpustakaan</label>
        <ClayInput
          v-model="form.shelf_location"
          placeholder="Contoh: Lantai 2 - Rak B-06 (Sistem & Komputer)"
        />
      </div>

      <!-- Sinopsis -->
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Sinopsis & Ringkasan Buku *</label>
        <textarea
          v-model="form.synopsis"
          rows="3"
          placeholder="Tuliskan sinopsis atau abstrak singkat buku untuk memudahkan pencarian mahasiswa..."
          class="clay-inset w-full p-3 rounded-2xl text-xs font-medium text-slate-700 border-none focus:outline-none focus:ring-2 focus:ring-blue-400"
          required
        ></textarea>
      </div>

      <!-- Submit Footer -->
      <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
        <ClayButton
          variant="ghost"
          size="sm"
          type="button"
          @click="$emit('close')"
        >
          Batal
        </ClayButton>

        <ClayButton
          variant="blue"
          size="sm"
          type="submit"
          :disabled="isSubmitting"
        >
          <template #leading>
            <Check class="w-3.5 h-3.5" />
          </template>
          {{ isSubmitting ? 'Menyimpan...' : 'Simpan Koleksi Baru' }}
        </ClayButton>
      </div>
    </form>
  </ClayModal>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useLibraryStore } from '../../stores/library'
import ClayModal from '../ui/ClayModal.vue'
import ClayInput from '../ui/ClayInput.vue'
import ClayButton from '../ui/ClayButton.vue'
import { BookPlus, Check } from 'lucide-vue-next'

defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  }
})

const emit = defineEmits(['close', 'success'])
const libraryStore = useLibraryStore()
const isSubmitting = ref(false)

const form = reactive({
  title: '',
  isbn: '',
  author_name: '',
  category_id: '1',
  format_type: 'physical',
  publication_year: 2025,
  pages: 320,
  total_stock: 5,
  shelf_location: 'Lantai 2 - Rak A-08',
  synopsis: '',
})

async function submitBook() {
  isSubmitting.value = true
  try {
    const res = await fetch('/api/books', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${libraryStore.authToken}`
      },
      body: JSON.stringify(form)
    })

    const data = await res.json()
    if (res.ok) {
      libraryStore.showToast(data.message || 'Buku baru berhasil ditambahkan!', 'success')
      await libraryStore.initFromBackend()
      emit('success')
      emit('close')
    } else {
      libraryStore.showToast(data.message || 'Gagal menambahkan buku.', 'error')
    }
  } catch (err) {
    libraryStore.showToast('Gagal menghubungi API server.', 'error')
  } finally {
    isSubmitting.value = false
  }
}
</script>
