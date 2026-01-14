<script setup>
import { ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'

const props = defineProps({
  items: Array,
  filters: Object,
})

const search = ref(props.filters?.q ?? '')

function searchItem() {
  router.get('/inventory', { q: search.value }, {
    preserveState: true,
    replace: true,
  })
}

function clearSearch() {
  search.value = ''
  router.get('/inventory', {}, {
    preserveState: true,
    replace: true,
  })
}
</script>

<template>
  <div class="min-h-screen bg-purple-100 p-6 space-y-6">
    <h1 class="text-3xl font-bold text-purple-800">Inventory Overview</h1>

    <!-- 🔍 SEARCH BAR -->
    <div class="bg-white shadow rounded-lg p-4 flex gap-3 items-center">
      <input
        v-model="search"
        @keyup.enter="searchItem"
        type="text"
        placeholder="Search item by name..."
        class="flex-1 border rounded px-3 py-2 text-black focus:outline-none focus:ring-2 focus:ring-purple-400"
      />

      <button
        @click="searchItem"
        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded"
      >
        Search
      </button>

      <button
        v-if="search"
        @click="clearSearch"
        class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded"
      >
        Clear
      </button>
    </div>

    <!-- 📦 ITEMS -->
    <div
      v-for="item in items"
      :key="item.id"
      class="bg-white shadow rounded-lg p-4 space-y-3"
    >
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-xl font-semibold text-gray-700">
            {{ item.name }} ({{ item.measurement_unit }})
          </h2>
          <p class="text-gray-500">
            Current Stock:
            <span class="font-bold">{{ item.quantity }}</span>
          </p>
        </div>

        <!-- ✅ MANAGE INVENTORY BUTTON -->
        <Link
          :href="`/inventory/${item.id}/edit`"
          class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded"
        >
          Manage Inventory
        </Link>
      </div>

      <table class="min-w-full mt-2 border">
        <thead class="bg-gray-100">
          <tr>
            <th class="border px-4 py-2 text-left text-black">Type</th>
            <th class="border px-4 py-2 text-right text-black">Quantity</th>
            <th class="border px-4 py-2 text-left text-black">Description</th>
            <th class="border px-4 py-2 text-left text-black">Date</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="log in item.logs"
            :key="log.id"
            class="hover:bg-gray-50"
          >
            <td class="border px-4 py-2">
              <span
                :class="log.change_type === 'addition'
                  ? 'bg-green-100 text-green-700 px-2 py-1 rounded text-sm'
                  : 'bg-red-100 text-red-700 px-2 py-1 rounded text-sm'"
              >
                {{ log.change_type === 'addition' ? 'Addition' : 'Deduction' }}
              </span>
            </td>
            <td class="border px-4 py-2 text-right text-black">
              {{ log.quantity }}
            </td>
            <td class="border px-4 py-2 text-black">
              {{ log.description ?? '-' }}
            </td>
            <td class="border px-4 py-2 text-black">
              {{ new Date(log.created_at).toLocaleString() }}
            </td>
          </tr>

          <tr v-if="item.logs.length === 0">
            <td colspan="4" class="text-center py-2 text-gray-500">
              No history
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
