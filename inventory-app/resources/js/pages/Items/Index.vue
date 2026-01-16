<script setup>
import { ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'

defineProps({
  items: Array,
})

const forms = ref([{ name: '', measurement_unit: '', quantity: '' }])

function addRow() {
  forms.value.push({ name: '', measurement_unit: '', quantity: '' })
}

function removeRow(index) {
  forms.value.splice(index, 1)
}

function submit() {
  router.post('/items', { items: forms.value }, {
    onSuccess: () => {
      forms.value = [{ name: '', measurement_unit: '', quantity: '' }]
    },
  })
}
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-purple-100 p-8">
    
    <div class="max-w-6xl mx-auto flex items-center gap-4 mb-8">
      <Link
        href="/"
        class="p-2 rounded-lg hover:bg-purple-100 transition-colors"
      >
        <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </Link>
      <div>
        <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-purple-700 to-purple-500 bg-clip-text text-transparent leading-tight">
          Inventory Overview
        </h1>
        <p class="text-purple-600/70 mt-1">Manage your stock and track changes</p>
      </div>
    </div>

    <div class="max-w-6xl mx-auto bg-white/80 backdrop-blur-sm shadow-xl shadow-purple-200/50 rounded-2xl p-8 mb-8 border border-purple-100">
      <h2 class="text-2xl font-semibold text-purple-800 mb-6 flex items-center gap-3">
        <div class="p-2.5 rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 shadow-lg shadow-purple-500/25">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
        </div>
        Add New Items
      </h2>

      <div class="space-y-4">
        <div 
          v-for="(form, index) in forms" 
          :key="index" 
          class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end bg-gradient-to-r from-purple-50 to-purple-100/50 p-5 rounded-xl border border-purple-200/50 transition-all duration-300 hover:shadow-lg hover:shadow-purple-200/30"
        >
          <div class="space-y-2">
            <label class="text-sm font-medium text-purple-700">Item Name</label>
            <input
              v-model="form.name"
              type="text"
              placeholder="Enter item name"
              class="w-full border-2 border-purple-200 rounded-xl px-4 py-3 text-gray-900 placeholder:text-gray-400 bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-200 transition-all outline-none"
              required
            />
          </div>

          <div class="space-y-2">
            <label class="text-sm font-medium text-purple-700">Unit</label>
            <select
              v-model="form.measurement_unit"
              class="w-full border-2 border-purple-200 rounded-xl px-4 py-3 text-gray-900 bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-200 transition-all outline-none cursor-pointer"
              required
            >
              <option disabled value="">Select unit</option>
              <option value="kg">Kilogram (kg)</option>
              <option value="g">Gram (g)</option>
              <option value="m">Meter (m)</option>
              <option value="cm">Centimeter (cm)</option>
              <option value="pcs">Pieces (pcs)</option>
            </select>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-medium text-purple-700">Quantity</label>
            <input
              v-model="form.quantity"
              type="number"
              step="0.01"
              placeholder="0.00"
              class="w-full border-2 border-purple-200 rounded-xl px-4 py-3 text-gray-900 placeholder:text-gray-400 bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-200 transition-all outline-none"
              required
            />
          </div>

          <button
            v-if="forms.length > 1"
            type="button"
            class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl px-4 py-3 font-medium shadow-lg shadow-red-200 transition-all duration-300 hover:scale-105 flex items-center justify-center gap-2"
            @click="removeRow(index)"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Remove
          </button>
        </div>
      </div>

      <div class="flex flex-wrap gap-4 mt-6 pt-6 border-t border-purple-100">
        <button
          type="button"
          class="bg-white border-2 border-dashed border-purple-300 hover:border-purple-500 text-purple-600 hover:text-purple-700 rounded-xl px-6 py-3 font-medium transition-all duration-300 hover:bg-purple-50 flex items-center gap-2"
          @click="addRow"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Add Another Item
        </button>

        <button
          type="button"
          class="ml-auto bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white rounded-xl px-8 py-3 font-semibold shadow-lg shadow-purple-300 transition-all duration-300 hover:scale-105 flex items-center gap-2"
          @click="submit"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          Submit All Items
        </button>
      </div>
    </div>

    <div class="max-w-6xl mx-auto bg-white/80 backdrop-blur-sm shadow-xl shadow-purple-200/50 rounded-2xl p-8 border border-purple-100">
      <h2 class="text-2xl font-semibold text-purple-800 mb-6 flex items-center gap-3">
        <div class="p-2.5 rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 shadow-lg shadow-purple-500/25">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
        </div>
        Item List
      </h2>

      <div class="overflow-hidden rounded-xl border border-purple-200">
        <table class="w-full">
          <thead class="bg-gradient-to-r from-purple-600 to-purple-700 text-white">
            <tr>
              <th class="px-6 py-4 text-left font-semibold">Name</th>
              <th class="px-6 py-4 text-left font-semibold">Unit</th>
              <th class="px-6 py-4 text-left font-semibold">Quantity</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-purple-100">
            <tr 
              v-for="item in items" 
              :key="item.id"
              class="hover:bg-purple-50 transition-colors"
            >
              <td class="px-6 py-4 text-gray-900 font-medium">{{ item.name }}</td>
              <td class="px-6 py-4">
                <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-medium">
                  {{ item.measurement_unit }}
                </span>
              </td>
              <td class="px-6 py-4 text-gray-900 font-mono">{{ item.quantity }}</td>
            </tr>
            <tr v-if="!items?.length">
              <td colspan="3" class="px-6 py-12 text-center text-purple-400">
                No items found. Add your first item above!
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Chrome, Safari, Edge, Opera - Remove arrows from number input */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox - Remove arrows from number input */
input[type=number] {
  -moz-appearance: textfield;
}
</style>