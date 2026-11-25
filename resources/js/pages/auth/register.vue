<script setup lang="ts">
  import { Head, Link, useForm } from '@inertiajs/vue3';
  import loginRoutes from '@/routes/login';
  import registerRoutes from '@/routes/register';

  defineProps<{
    registrationAvailable: boolean;
  }>();

  const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
  });

  function submit(): void {
    form.post(registerRoutes.store().url);
  }
</script>

<template>
  <Head title="Daftar" />
  <main
    class="mx-auto flex min-h-screen max-w-md flex-col justify-center px-6 py-10"
  >
    <h1 class="text-2xl font-semibold text-slate-900">
      Buat akun Lost &amp; Found
    </h1>
    <p class="mt-2 text-sm text-slate-600">
      Daftar menggunakan email yang diizinkan oleh kampus.
    </p>

    <p
      v-if="!registrationAvailable"
      class="mt-4 rounded-lg bg-amber-50 p-3 text-sm text-amber-800"
      role="status"
    >
      Pendaftaran belum tersedia. Periksa konfigurasi domain email.
    </p>

    <form class="mt-6 space-y-4" @submit.prevent="submit">
      <div>
        <label class="block text-sm font-medium text-slate-700" for="name">
          Nama
        </label>
        <input
          id="name"
          v-model="form.name"
          class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"
          type="text"
          autocomplete="name"
          placeholder="John Maximillian"
          required
        />
        <p v-if="form.errors.name" class="mt-1 text-sm text-red-700">
          {{ form.errors.name }}
        </p>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700" for="email">
          Email
        </label>
        <input
          id="email"
          v-model="form.email"
          class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"
          type="email"
          autocomplete="email"
          placeholder="john@mhs.unesa.ac.id"
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
          autocomplete="new-password"
          required
        />
        <p v-if="form.errors.password" class="mt-1 text-sm text-red-700">
          {{ form.errors.password }}
        </p>
      </div>

      <div>
        <label
          class="block text-sm font-medium text-slate-700"
          for="password_confirmation"
        >
          Konfirmasi password
        </label>
        <input
          id="password_confirmation"
          v-model="form.password_confirmation"
          class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"
          type="password"
          placeholder="********"
          autocomplete="new-password"
          required
        />
      </div>

      <div class="flex gap-3">
        <Link
          class="flex-1 rounded-lg border border-slate-300 px-4 py-2 text-center font-semibold text-slate-700 hover:bg-slate-50"
          href="/"
        >
          Kembali
        </Link>
        <button
          class="flex-1 rounded-lg bg-orange-600 px-4 py-2 font-semibold text-white hover:bg-orange-700 disabled:opacity-50"
          :disabled="form.processing || !registrationAvailable"
          type="submit"
        >
          {{ form.processing ? 'Memproses...' : 'Daftar' }}
        </button>
      </div>
    </form>

    <p class="mt-5 text-sm text-slate-600">
      Sudah punya akun?
      <Link
        class="font-medium text-orange-700 underline"
        :href="loginRoutes.create().url"
      >
        Masuk
      </Link>
    </p>
  </main>
</template>
