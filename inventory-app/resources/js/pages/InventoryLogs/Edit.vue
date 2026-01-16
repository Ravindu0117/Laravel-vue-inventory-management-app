<script setup>
import { ref, computed } from 'vue'
import { router, Link } from '@inertiajs/vue3'

/**
 * PROPS & DATA
 */
const props = defineProps({
  item: Object,
})

// Deduct Form State
const deductQuantity = ref('')
const deductDescription = ref('')
const isSubmitting = ref(false)

// History Log Configuration
const changeTypeConfig = {
  add: {
    label: 'Added',
    colorClass: 'text-green-600 bg-green-100',
    sign: '+',
  },
  deduct: {
    label: 'Deducted',
    colorClass: 'text-red-600 bg-red-100',
    sign: '-',
  },
  adjustment: {
    label: 'Adjusted',
    colorClass: 'text-amber-600 bg-amber-100',
    sign: '+',
  },
}

/**
 * COMPUTED
 */
const isOverLimit = computed(() => {
  const val = parseFloat(deductQuantity.value)
  return val > props.item.quantity
})

/**
 * METHODS
 */
function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

async function handleDeductSubmit() {
  const qty = parseFloat(deductQuantity.value)
  if (isNaN(qty) || qty <= 0 || qty > props.item.quantity) return
  
  isSubmitting.value = true
  
  router.post('/inventory/deduct', {
    item_id: props.item.id,
    quantity: qty,
    description: deductDescription.value,
  }, {
    onSuccess: () => {
      deductQuantity.value = ''
      deductDescription.value = ''
      isSubmitting.value = false
    },
    onError: () => {
      isSubmitting.value = false
    }
  })

  // Fallback reset for visual feedback if not using Inertia listeners
  setTimeout(() => {
    if (isSubmitting.value) isSubmitting.value = false
  }, 2000)
}
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-purple-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      
      <div class="flex items-center gap-4 mb-8 animate-fade-in">
        <Link
          href="/inventory"
          class="p-2 rounded-lg hover:bg-purple-100 transition-colors"
        >
          <svg class="w-5 h-5 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </Link>
        <div class="flex items-center gap-3">
          <div class="p-3 rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 shadow-lg shadow-purple-500/25">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
          <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ item.name }}</h1>
            <p class="text-sm text-purple-600">Manage inventory & track changes</p>
          </div>
        </div>
      </div>

      <div class="grid gap-6 md:grid-cols-2">
        <div class="relative overflow-hidden rounded-xl bg-white p-6 shadow-lg border border-purple-100 animate-fade-in">
          <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500/5 rounded-full -translate-y-1/2 translate-x-1/2" />
          <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
              <div class="p-2.5 rounded-lg bg-purple-100">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
              </div>
              <span class="text-sm font-medium text-purple-600 uppercase tracking-wide">Current Stock</span>
            </div>
            <div class="flex items-baseline gap-2">
              <span class="text-4xl font-bold text-gray-900 font-mono tracking-tight">
                {{ item.quantity.toLocaleString() }}
              </span>
              <span class="text-lg text-gray-500 font-medium">{{ item.measurement_unit }}</span>
            </div>
            <div class="flex items-center gap-1.5 mt-3">
              <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
              </svg>
              <span class="text-red-600 text-sm font-medium">205 kg</span>
              <span class="text-sm text-gray-500">vs last week</span>
            </div>
          </div>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-lg border border-purple-100 animate-fade-in animation-delay-100">
          <div class="flex items-center gap-3 mb-6">
            <div class="p-2.5 rounded-lg bg-red-100">
              <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
              </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">Deduct Stock</h2>
          </div>
          <form @submit.prevent="handleDeductSubmit" class="space-y-4">
            <div class="space-y-2">
              <label class="text-sm font-medium text-gray-700">Quantity to deduct</label>
              <div class="relative">
                <input
                  v-model="deductQuantity"
                  type="number"
                  min="0.01"
                  step="0.01"
                  placeholder="0.00"
                  required
                  class="w-full px-4 py-2.5 pr-16 rounded-lg border font-mono text-lg text-gray-900 placeholder:text-gray-400 transition-colors focus:outline-none focus:ring-2"
                  :class="isOverLimit 
                    ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20' 
                    : 'border-purple-200 focus:border-purple-500 focus:ring-purple-500/20'"
                />
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">
                  {{ item.measurement_unit }}
                </span>
              </div>
              <div v-if="isOverLimit" class="flex items-center gap-1.5 text-red-600 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Exceeds current stock ({{ item.quantity }} {{ item.measurement_unit }})</span>
              </div>
            </div>
            <div class="space-y-2">
              <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Reason (optional)
              </label>
              <input
                v-model="deductDescription"
                type="text"
                placeholder="e.g., Used for production batch #1"
                class="w-full px-4 py-2.5 rounded-lg border border-purple-200 text-gray-900 placeholder:text-gray-400 transition-colors focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20"
              />
            </div>
            <button
              type="submit"
              :disabled="isSubmitting || isOverLimit || !deductQuantity"
              class="w-full mt-2 px-6 py-2.5 rounded-lg bg-gradient-to-r from-red-500 to-red-600 text-white font-medium shadow-lg shadow-red-500/25 hover:from-red-600 hover:to-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2"
            >
              <div v-if="isSubmitting" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" />
              <template v-else>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
                Deduct Stock
              </template>
            </button>
          </form>
        </div>
      </div>

      <div class="mt-6 rounded-xl bg-white p-6 shadow-lg border border-purple-100 animate-fade-in animation-delay-200">
        <div class="flex items-center gap-3 mb-6">
          <div class="p-2.5 rounded-lg bg-purple-100">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h2 class="text-lg font-semibold text-gray-900">Inventory History</h2>
          <span class="ml-auto text-sm text-purple-600 bg-purple-50 px-2.5 py-1 rounded-full font-medium">
            {{ item.logs?.length || 0 }} entries
          </span>
        </div>

        <div v-if="!item.logs || item.logs.length === 0" class="text-center py-8">
          <svg class="w-12 h-12 text-purple-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-gray-500">No history entries yet</p>
        </div>

        <div v-else class="space-y-3">
          <div
            v-for="(log, index) in item.logs"
            :key="log.id"
            class="flex items-center gap-4 p-3 rounded-lg bg-purple-50/50 hover:bg-purple-50 transition-colors"
            :style="{ animationDelay: `${index * 50}ms` }"
          >
            <div :class="changeTypeConfig[log.change_type]?.colorClass || 'bg-gray-100'" class="p-2 rounded-lg">
              <svg v-if="log.change_type === 'add'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              <svg v-else-if="log.change_type === 'deduct'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
              </svg>
              <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
            </div>
            
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2">
                <span class="font-medium text-gray-900">
                  {{ changeTypeConfig[log.change_type]?.label || 'Adjustment' }}
                </span>
                <span class="font-mono text-sm font-semibold text-gray-700">
                  {{ changeTypeConfig[log.change_type]?.sign }}{{ log.quantity }} {{ item.measurement_unit }}
                </span>
              </div>
              <p v-if="log.description" class="text-sm text-gray-500 truncate mt-0.5">
                {{ log.description }}
              </p>
            </div>
            
            <time class="text-xs text-gray-400 whitespace-nowrap">
              {{ formatDate(log.created_at) }}
            </time>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<style scoped>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fade-in 0.4s ease-out forwards;
}

.animation-delay-100 { animation-delay: 100ms; }
.animation-delay-200 { animation-delay: 200ms; }

/* Chrome, Safari, Edge, Opera - Remove arrows from number input */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

</style>