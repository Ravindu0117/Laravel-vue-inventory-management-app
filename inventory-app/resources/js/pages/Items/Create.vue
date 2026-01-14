<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  measurement_unit: '',
  quantity: ''
})

function submit() {
  form.post('/items')
}
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Add Item</h1>

    <form @submit.prevent="submit" class="space-y-4">
      <input v-model="form.name" placeholder="Item name" class="border p-2 w-full" />
      <input v-model="form.measurement_unit" placeholder="Unit (kg, pcs)" class="border p-2 w-full" />
      <input v-model="form.quantity" type="number" placeholder="Quantity" class="border p-2 w-full" />

      <button class="bg-blue-600 text-white px-4 py-2">
        Add Item
      </button>
    </form>
  </div>
</template>
