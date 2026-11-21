<script setup lang="ts">
  import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
  import type { Auth } from '@/types/auth';
  import { home } from '@/routes';
  import { logout as logoutRoute } from '@/routes/auth';

  const page = usePage<{ auth: Auth }>();
  const logoutForm = useForm({});
  const logout = (): void => logoutForm.post(logoutRoute().url);
</script>

<template>
  <Head title="Panel Admin" />
  <main class="mx-auto min-h-screen max-w-5xl px-6 py-10">
    <nav class="flex items-center justify-between border-b pb-4">
      <Link class="font-semibold" :href="home().url">
        Lost &amp; Found Vokasi
      </Link>
      <div class="flex items-center gap-4 text-sm">
        <span>{{ page.props.auth.user?.name }} (Admin)</span>
        <button
          class="underline"
          :disabled="logoutForm.processing"
          @click="logout"
        >
          Keluar
        </button>
      </div>
    </nav>
    <section class="py-12">
      <h1 class="text-2xl font-semibold">Panel Admin</h1>
      <p class="mt-3 text-gray-600">
        Akun admin berhasil masuk. Pengelolaan laporan dapat ditambahkan nanti.
      </p>
    </section>
  </main>
</template>
