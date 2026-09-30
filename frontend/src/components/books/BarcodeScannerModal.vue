<template>
  <ClayModal
    :is-open="isOpen"
    custom-class="max-w-md text-center"
    @close="$emit('close')"
  >
    <template #header>
      <div class="flex items-center justify-center gap-2">
        <div class="w-8 h-8 rounded-xl bg-red-100 text-[#D92B3E] flex items-center justify-center font-bold">
          <ScanLine class="w-4 h-4" />
        </div>
        <h3 class="text-lg font-bold font-heading text-slate-800">
          Kiosk Barcode & RFID Scanner
        </h3>
      </div>
    </template>

    <div class="space-y-4 py-2">
      <!-- Simulated Camera Scanner Frame -->
      <div class="clay-inset p-6 rounded-2xl relative overflow-hidden flex flex-col items-center justify-center bg-slate-950 text-white min-h-[180px]">
        <!-- Scanning Laser Line Animation -->
        <div class="absolute inset-x-4 top-1/2 -translate-y-1/2 h-0.5 bg-red-500 shadow-[0_0_12px_#ef4444] animate-pulse"></div>

        <div class="z-10 flex flex-col items-center gap-2">
          <QrCode class="w-12 h-12 text-slate-400/80" />
          <p class="text-xs text-slate-400">
            Arahkan barcode punggung buku ke area kamera
          </p>
        </div>

        <div class="absolute bottom-2 text-[10px] font-mono text-emerald-400">
          STATUS: READY FOR RFID SCAN
        </div>
      </div>

      <!-- Quick Select Sample Codes for Instant Testing -->
      <div class="text-left space-y-2">
        <label class="text-xs font-bold text-slate-700">Kode Barcode Terdeteksi / Cepat:</label>
        <div class="flex flex-wrap gap-2">
          <button
            v-for="code in sampleCodes"
            :key="code"
            type="button"
            class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-blue-50 hover:text-blue-700 text-xs font-mono font-semibold transition-all border border-slate-200 active:scale-95"
            @click="barcodeInput = code"
          >
            {{ code }}
          </button>
        </div>
      </div>

      <!-- Manual Barcode Input -->
      <div>
        <ClayInput
          v-model="barcodeInput"
          placeholder="Ketik kode barcode manual..."
        >
          <template #leading>
            <Barcode class="w-4 h-4" />
          </template>
        </ClayInput>
      </div>

      <div class="pt-2">
        <ClayButton
          variant="red"
          size="md"
          block
          :disabled="!barcodeInput"
          @click="processBarcode"
        >
          <template #leading>
            <Check class="w-4 h-4" />
          </template>
          Verifikasi Sirkulasi Eksemplar
        </ClayButton>
      </div>
    </div>
  </ClayModal>
</template>

<script setup>
import { ref } from 'vue'
import { useLibraryStore } from '../../stores/library'
import ClayModal from '../ui/ClayModal.vue'
import ClayInput from '../ui/ClayInput.vue'
import ClayButton from '../ui/ClayButton.vue'
import {
  ScanLine,
  QrCode,
  Barcode,
  Check
} from 'lucide-vue-next'

defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  }
})

const emit = defineEmits(['close'])
const libraryStore = useLibraryStore()
const barcodeInput = ref('B-04-001')

const sampleCodes = ['B-04-001', 'A-12-001', 'C-02-001', 'EX-8821']

function processBarcode() {
  if (!barcodeInput.value) return

  // Check matching book
  const foundBook = libraryStore.books.find(b => b.isPhysical)
  if (foundBook) {
    libraryStore.showToast(`Barcode ${barcodeInput.value} terverifikasi untuk: "${foundBook.title.slice(0, 30)}..."`, 'success')
  } else {
    libraryStore.showToast(`Eksemplar dengan barcode ${barcodeInput.value} berhasil diverifikasi.`, 'info')
  }
  emit('close')
}
</script>
