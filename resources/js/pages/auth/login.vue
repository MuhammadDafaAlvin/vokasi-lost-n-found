<script setup lang="ts">
  import { Head, Link, useForm } from '@inertiajs/vue3';
  import loginRoutes from '@/routes/login';
  import registerRoutes from '@/routes/register';

  const form = useForm({
    email: '',
    password: '',
    remember: false,
  });

  function submit(): void {
    form.post(loginRoutes.store().url);
  }
</script>

<template>
  <Head title="Masuk" />

  <main class="mx-auto flex min-h-screen max-w-md flex-col justify-center px-6">
    <h1 class="text-2xl font-semibold">Masuk ke Lost &amp; Found</h1>

    <form class="mt-6 space-y-4" @submit.prevent="submit">
      <div>
        <label class="block text-sm font-medium" for="email">Email</label>
        <input
          id="email"
          v-model="form.email"
          class="mt-1 w-full rounded border px-3 py-2"
          type="email"
          autocomplete="email"
          required
        />
        <p v-if="form.errors.email" class="mt-1 text-sm text-red-700">
          {{ form.errors.email }}
        </p>
      </div>

      <div>
        <label class="block text-sm font-medium" for="password">
          Password
        </label>
        <input
          id="password"
          v-model="form.password"
          class="mt-1 w-full rounded border px-3 py-2"
          type="password"
          autocomplete="current-password"
          required
        />
        <p v-if="form.errors.password" class="mt-1 text-sm text-red-700">
          {{ form.errors.password }}
        </p>
      </div>

      <label class="flex items-center gap-2 text-sm">
        <input v-model="form.remember" type="checkbox" />
        Ingat saya
      </label>

      <button
        class="w-full rounded bg-blue-700 px-4 py-2 text-white disabled:opacity-50"
        :disabled="form.processing"
      >
        Masuk
      </button>
    </form>

    <p class="mt-4 text-sm">
      Belum punya akun?
      <Link class="underline" :href="registerRoutes.create().url">
        Daftar
      </Link>
    </p>
  </main>
</template>
