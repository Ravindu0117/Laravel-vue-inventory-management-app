<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
  <Head title="Welcome" />

  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 flex flex-col">
    <!-- NAVBAR -->
    <nav class="w-full px-6 py-4 flex items-center justify-between max-w-7xl mx-auto">
      <h1 class="bg-purple-100 text-purple-500 px-2 py-1 rounded text-sm">
        Inventory System
      </h1>

      <div class="flex items-center gap-3">
        <Link
          v-if="$page.props.auth.user"
          :href="dashboard()"
          class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition"
        >
          Dashboard
        </Link>

        <template v-else>
          <Link
            :href="login()"
            class="px-4 py-2 rounded-lg text-sm font-medium text-slate-700 hover:text-indigo-600 transition"
          >
            Login
          </Link>

          <Link
            v-if="canRegister"
            :href="register()"
            class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition"
          >
            Register
          </Link>
        </template>
      </div>
    </nav>

    <!-- HERO SECTION -->
    <main class="flex-1 flex items-center justify-center px-6">
      <div class="max-w-3xl text-center">
        <h2 class="text-4xl md:text-5xl font-extrabold text-slate-800 mb-4">
          Manage Your Inventory <br />
          <span class="text-indigo-600">Smarter & Faster</span>
        </h2>

        <p class="text-slate-600 text-lg mb-8">
          Track stock, add or deduct inventory, and maintain logs effortlessly.
          Built with Laravel, Inertia, and Vue.
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
          <Link
            v-if="$page.props.auth.user"
            href="/items"
            class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-slate-700 transition"
          >
            Add a Item
          </Link>
          <Link
            v-if="$page.props.auth.user"
            href="/inventory"
            class="px-6 py-3 rounded-xl bg-indigo-600 text-white text-sm font-medium hover:bg-slate-800"
            >
            Inventory
          </Link>

          <template v-else>
            <Link
              :href="login()"
              class="px-6 py-3 rounded-xl border border-slate-300 bg-white text-slate-700 font-semibold hover:border-indigo-600 hover:text-indigo-600 transition"
            >
              Login
            </Link>

            <Link
              v-if="canRegister"
              :href="register()"
              class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition"
            >
              Create Account
            </Link>
          </template>
        </div>
      </div>
    </main>

    <!-- FOOTER -->
    <footer class="py-4 text-center text-sm text-slate-500">
      © {{ new Date().getFullYear() }} Inventory System. Built with Laravel + Vue.
    </footer>
  </div>
</template>

