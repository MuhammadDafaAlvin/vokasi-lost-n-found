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

  <main
    class="mx-auto flex min-h-screen max-w-md flex-col justify-center px-6 py-10"
  >
    <h1 class="text-2xl font-semibold text-slate-900">
      Masuk ke Lost &amp; Found
    </h1>
    <p class="mt-2 text-sm text-slate-600">
      Gunakan akun Fakultas Vokasi Anda untuk melanjutkan.
    </p>

    <form class="mt-6 space-y-4" @submit.prevent="submit">
      <div>
        <label class="block text-sm font-medium text-slate-700" for="email">
          Email
        </label>
        <input
          id="email"
          v-model="form.email"
          class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"
          type="email"
          placeholder="john@mhs.unesa.ac.id"
          autocomplete="email"
          required
        />
        <p v-if="form.errors.email" class="mt-1 text-sm text-red-700">
          {{ form.errors.email }}
        </p>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700" for="password">
          Password
        </label>
        <input
          id="password"
          v-model="form.password"
          class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"
          type="password"
          placeholder="********"
          autocomplete="current-password"
          required
        />
        <p v-if="form.errors.password" class="mt-1 text-sm text-red-700">
          {{ form.errors.password }}
        </p>
      </div>

      <label class="flex items-center gap-2 text-sm text-slate-600">
        <input v-model="form.remember" type="checkbox" />
        Ingat saya
      </label>

      <div class="flex gap-3">
        <Link
          class="flex-1 rounded-lg border border-slate-300 px-4 py-2 text-center font-semibold text-slate-700 hover:bg-slate-50"
          href="/"
        >
          Kembali
        </Link>

        <button
          class="flex-1 rounded-lg bg-orange-600 px-4 py-2 font-semibold text-white hover:bg-orange-700 disabled:opacity-50"
          :disabled="form.processing"
          type="submit"
        >
          {{ form.processing ? 'Memproses...' : 'Masuk' }}
        </button>
      </div>
    </form>

    <p class="mt-5 text-sm text-slate-600">
      Belum punya akun?
      <Link
        class="font-medium text-orange-700 underline"
        :href="registerRoutes.create().url"
      >
        Daftar
      </Link>
    </p>
  </main>
</template>
