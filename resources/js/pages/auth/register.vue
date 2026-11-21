<script setup lang="ts">
  import { Head, Link, useForm } from '@inertiajs/vue3';
  import loginRoutes from '@/routes/login';
  import registerRoutes from '@/routes/register';

  defineProps<{ registrationAvailable: boolean }>();

  const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
  });

  const submit = (): void => form.post(registerRoutes.store().url);
</script>

<template>
  <Head title="Daftar" />
  <main class="mx-auto flex min-h-screen max-w-md flex-col justify-center px-6">
    <h1 class="text-2xl font-semibold">Buat akun</h1>
    <p v-if="!registrationAvailable" class="mt-3 text-sm text-red-700">
      Pendaftaran belum aktif. Periksa konfigurasi domain email.
    </p>
    <form class="mt-6 space-y-4" @submit.prevent="submit">
      <div>
        <label class="block text-sm font-medium" for="name">Nama</label>
        <input
          id="name"
          v-model="form.name"
          class="mt-1 w-full rounded border px-3 py-2"
          type="text"
          autocomplete="name"
          required
        />
        <p v-if="form.errors.name" class="mt-1 text-sm text-red-700">
          {{ form.errors.name }}
        </p>
      </div>
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
          autocomplete="new-password"
          required
        />
        <p v-if="form.errors.password" class="mt-1 text-sm text-red-700">
          {{ form.errors.password }}
        </p>
      </div>
      <div>
        <label class="block text-sm font-medium" for="password_confirmation">
          Konfirmasi password
        </label>
        <input
          id="password_confirmation"
          v-model="form.password_confirmation"
          class="mt-1 w-full rounded border px-3 py-2"
          type="password"
          autocomplete="new-password"
          required
        />
      </div>
      <button
        class="w-full rounded bg-orange-500 px-4 py-2 text-white disabled:opacity-50"
        :disabled="form.processing || !registrationAvailable"
      >
        Daftar
      </button>
    </form>
    <p class="mt-4 text-sm">
      Sudah punya akun?
      <Link class="underline" :href="loginRoutes.create().url">Masuk</Link>
    </p>
  </main>
</template>
