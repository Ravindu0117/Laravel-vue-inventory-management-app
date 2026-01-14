<script setup>
defineProps({
  item: Object,
  logs: Array,
})
</script>

<template>
  <div class="p-6 space-y-6">

    <!-- Page Title -->
    <div>
      <h1 class="text-3xl font-bold text-black-800">
        Inventory History
      </h1>
      <p class="text-white-500 mt-1">
        Item: <span class="font-semibold">{{ item.name }}</span>
      </p>
    </div>

    <!-- Logs Table -->
    <div class="bg-white shadow rounded-lg p-6">
      <table class="min-w-full border">
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
            v-for="log in logs"
            :key="log.id"
            class="hover:bg-gray-50"
          >
            <td class="border px-4 py-2">
              <span
                v-if="log.change_type === 'addition'"
                class="px-2 py-1 text-sm rounded bg-green-100 text-green-700"
              >
                Addition
              </span>

              <span
                v-else
                class="px-2 py-1 text-sm rounded bg-red-100 text-red-700"
              >
                Deduction
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

          <tr v-if="logs.length === 0">
            <td colspan="4" class="text-center py-4 text-gray-500">
              No inventory history found
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>
