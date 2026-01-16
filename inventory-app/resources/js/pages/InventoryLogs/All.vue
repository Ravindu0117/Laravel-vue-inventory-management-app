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
  <div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-purple-100/50 p-6 md:p-8">
    <!-- Header -->
      <div class="flex items-center gap-4 mb-8 animate-fade-in">
        <Link
          href="/"
          class="p-2 rounded-lg hover:bg-purple-100 transition-colors"
        >
          <svg class="w-5 h-5 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    

    <!-- Search Bar -->
    <div class="bg-white/80 backdrop-blur-sm shadow-lg shadow-purple-500/5 rounded-2xl p-4 mb-8 border border-purple-100">
      <div class="flex gap-3 items-center">
        <div class="relative flex-1">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input
            v-model="search"
            @keyup.enter="searchItem"
            type="text"
            placeholder="Search item by name..."
            class="w-full pl-10 pr-4 py-3 border border-purple-200 rounded-xl text-gray-700 placeholder:text-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all"
          />
        </div>
        <button
          @click="searchItem"
          class="bg-gradient-to-r from-purple-600 to-purple-500 hover:from-purple-700 hover:to-purple-600 text-white px-6 py-3 rounded-xl font-medium shadow-lg shadow-purple-500/25 transition-all hover:shadow-xl hover:shadow-purple-500/30"
        >
          Search
        </button>
        <button
          v-if="search"
          @click="clearSearch"
          class="bg-purple-100 hover:bg-purple-200 text-purple-700 px-4 py-3 rounded-xl font-medium transition-all"
        >
          Clear
        </button>
      </div>
    </div>

    <!-- Items -->
    <div class="space-y-6">
      <div
        v-for="item in items"
        :key="item.id"
        class="bg-white/80 backdrop-blur-sm shadow-lg shadow-purple-500/5 rounded-2xl p-6 border border-purple-100 hover:shadow-xl hover:shadow-purple-500/10 transition-all"
      >
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-4">
          <div>
            <h2 class="text-xl font-semibold text-gray-800">
              {{ item.name }}
              <span class="text-purple-500 font-normal">({{ item.measurement_unit }})</span>
            </h2>
            <p class="text-purple-600/70 mt-1">
              Current Stock:
              <span class="font-bold text-purple-700 text-lg">{{ item.quantity }}</span>
            </p>
          </div>
          <Link
            :href="`/inventory/${item.id}/edit`"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-purple-500 hover:from-purple-700 hover:to-purple-600 text-white px-5 py-2.5 rounded-xl font-medium shadow-lg shadow-purple-500/25 transition-all hover:shadow-xl"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Manage Inventory
          </Link>
        </div>

        <div class="overflow-hidden rounded-xl border border-purple-100">
          <table class="min-w-full">
            <thead class="bg-purple-50">
              <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold text-purple-700">Type</th>
                <th class="px-4 py-3 text-right text-sm font-semibold text-purple-700">Quantity</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-purple-700">Description</th>
                <th class="px-4 py-3 text-left text-sm font-semibold text-purple-700">Date</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-purple-100">
              <tr
                v-for="log in item.logs"
                :key="log.id"
                class="hover:bg-purple-50/50 transition-colors"
              >
                <td class="px-4 py-3">
                  <span
                    :class="log.change_type === 'addition'
                      ? 'bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-medium'
                      : 'bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium'"
                  >
                    {{ log.change_type === 'addition' ? '+ Addition' : '− Deduction' }}
                  </span>
                </td>
                <td class="px-4 py-3 text-right font-medium text-gray-700">
                  {{ log.quantity }}
                </td>
                <td class="px-4 py-3 text-gray-600">
                  {{ log.description ?? '—' }}
                </td>
                <td class="px-4 py-3 text-gray-500 text-sm">
                  {{ new Date(log.created_at).toLocaleString() }}
                </td>
              </tr>
              <tr v-if="item.logs.length === 0">
                <td colspan="4" class="text-center py-8 text-purple-400">
                  No history yet
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
