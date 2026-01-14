<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

defineProps({
  items: Array,
})

// Start with one item form
const forms = ref([
  { name: '', measurement_unit: '', quantity: '' }
])

// Add a new empty form
function addRow() {
  forms.value.push({ name: '', measurement_unit: '', quantity: '' })
}

// Remove a specific form
function removeRow(index) {
  forms.value.splice(index, 1)
}

// Submit all forms at once
function submit() {
  router.post('/items', { items: forms.value }, {
    onSuccess: () => {
      // Reset all forms
      forms.value = [{ name: '', measurement_unit: '', quantity: '' }]
    },
  })
}
</script>

<template>
  <div class="min-h-screen bg-purple-100 p-6 space-y-6">

    <!-- Page Title -->
    <h1 class="text-3xl font-bold text-purple-800">
      Inventory Items
    </h1>

    <!-- Add Item Forms -->
    <div class="bg-white shadow rounded-lg p-6 space-y-4">
      <h2 class="text-xl font-semibold mb-4 text-black">Add New Items</h2>

      <div v-for="(form, index) in forms" :key="index" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end bg-purple-50 p-4 rounded">
        <input
          v-model="form.name"
          type="text"
          placeholder="Item name"
          class="border rounded px-3 py-2 text-black"
          required
        />

        <select
          v-model="form.measurement_unit"
          class="border rounded px-3 py-2 text-black bg-white"
          required
        >
          <option disabled value="">Select unit</option>
          <option value="kg">kg</option>
          <option value="g">g</option>
          <option value="m">m</option>
          <option value="cm">cm</option>
          <option value="pcs">pcs</option>
        </select>

        <input
          v-model="form.quantity"
          type="number"
          step="0.01"
          placeholder="Quantity"
          class="border rounded px-3 py-2 text-black"
          required
        />

        <div class="flex flex-col gap-2">
          <button
            type="button"
            class="bg-indigo-600 hover:bg-indigo-700 text-white rounded px-4 py-2"
            @click="addRow"
          >
            + Add Another Item
          </button>

          <button
            v-if="forms.length > 1"
            type="button"
            class="bg-red-600 hover:bg-red-700 text-white rounded px-4 py-2"
            @click="removeRow(index)"
          >
            Remove
          </button>
        </div>
      </div>

      <button
        type="button"
        class="bg-green-600 hover:bg-green-700 text-white rounded px-6 py-2 mt-4"
        @click="submit"
      >
        Submit 
      </button>
    </div>

    <!-- Items Table -->
    <div class="bg-white shadow rounded-lg p-6">
      <h2 class="text-xl font-semibold mb-4 text-black">Item List</h2>

      <table class="min-w-full border">
        <thead class="bg-gray-100">
          <tr>
            <th class="border px-4 py-2 text-left text-black">Name</th>
            <th class="border px-4 py-2 text-left text-black">Unit</th>
            <th class="border px-4 py-2 text-right text-black">Quantity</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="item in items" :key="item.id" class="hover:bg-gray-50">
            <td class="border px-4 py-2 text-black">{{ item.name }}</td>
            <td class="border px-4 py-2 text-black">{{ item.measurement_unit }}</td>
            <td class="border px-4 py-2 text-right text-black">{{ item.quantity }}</td>
          </tr>

          <tr v-if="items.length === 0">
            <td colspan="3" class="text-center py-4 text-gray-500">
              No items found
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>
