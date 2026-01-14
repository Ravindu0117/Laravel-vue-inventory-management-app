<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  item: Object,
})

const form = ref({
  item_id: props.item.id,
  quantity: '',
  description: '',
})

function deduct() {
  router.post('/inventory/deduct', form.value, {
    onSuccess: () => {
      form.value.quantity = ''
      form.value.description = ''
    },
  })
}
</script>

<template>
  <div class="min-h-screen bg-purple-100 p-6 space-y-6">

    <h1 class="text-3xl font-bold text-purple-800">
      Manage Inventory — {{ item.name }}
    </h1>

    <div class="bg-white p-6 rounded shadow">
      <p class="text-gray-700">
        Current Stock:
        <span class="font-bold">{{ item.quantity }}</span>
        {{ item.measurement_unit }}
      </p>
    </div>

    <!-- DEDUCT FORM -->
    <div class="bg-white p-6 rounded shadow space-y-4">
      <h2 class="text-xl font-semibold text-black">
        Deduct Stock
      </h2>

      <form @submit.prevent="deduct" class="space-y-4">
        <input
          v-model="form.quantity"
          type="number"
          min="1"
          step="0.01"
          placeholder="Quantity to deduct"
          class="w-full border rounded px-3 py-2 text-black"
          required
        />

        <input
          v-model="form.description"
          type="text"
          placeholder="Reason (optional)"
          class="w-full border rounded px-3 py-2 text-black"
        />

        <button
          type="submit"
          class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded"
        >
          Deduct Stock
        </button>
      </form>
    </div>

    <!-- HISTORY -->
    <div class="bg-white p-6 rounded shadow">
      <h2 class="text-xl font-semibold mb-4">Inventory History</h2>

      <ul class="space-y-2">
        <li
          v-for="log in item.logs"
          :key="log.id"
          class="flex justify-between text-sm"
        >
          <span>
            {{ log.change_type }} - {{ log.quantity }}
          </span>
          <span class="text-gray-500">
            {{ new Date(log.created_at).toLocaleString() }}
          </span>
        </li>
      </ul>
    </div>

  </div>
</template>
