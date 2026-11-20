<script setup lang="ts">
  import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
  import type { Auth } from '@/types/auth';
  import { home } from '@/routes';
  import authRoutes from '@/routes/auth';
  import loginRoutes from '@/routes/login';
  import registerRoutes from '@/routes/register';
  import report from '@/routes/admin/report';

  const page = usePage<{ auth: Auth }>();
  const logoutForm = useForm({});

  function submitLogout(): void {
    logoutForm.post(authRoutes.logout().url);
  }
</script>

<template>
  <Head title="Lost & Found Vokasi" />

  <main class="mx-auto min-h-screen max-w-5xl px-6 py-10">
    <nav class="flex items-center justify-between border-b pb-4">
      <Link class="font-semibold" :href="home().url">
        Lost &amp; Found Vokasi
      </Link>

      <div v-if="page.props.auth.user" class="flex items-center gap-4 text-sm">
        <Link
          v-if="page.props.auth.user.role === 'admin'"
          :href="report.index().url"
        >
          Panel admin
        </Link>

        <span>{{ page.props.auth.user.name }}</span>

        <button
          class="underline"
          :disabled="logoutForm.processing"
          @click="submitLogout"
        >
          Keluar
        </button>
      </div>

      <div v-else class="flex items-center gap-4 text-sm">
        <Link class="underline" :href="loginRoutes.create().url"> Masuk </Link>
        <Link class="underline" :href="registerRoutes.create().url">
          Daftar
        </Link>
      </div>
    </nav>

    <section class="py-16">
      <h1 class="text-3xl font-semibold">
        Lost &amp; Found Fakultas Vokasi UNESA
      </h1>

      <p class="mt-3 max-w-2xl text-gray-600">
        Platform untuk melaporkan dan mencari barang hilang atau ditemukan.
      </p>

      <p v-if="page.props.auth.user" class="mt-4 text-sm text-gray-700">
        Anda masuk sebagai {{ page.props.auth.user.role }}.
      </p>

      <div v-else class="mt-6 flex gap-3">
        <Link
          class="rounded bg-blue-700 px-4 py-2 text-white"
          :href="loginRoutes.create().url"
        >
          Masuk
        </Link>
        <Link
          class="rounded border px-4 py-2"
          :href="registerRoutes.create().url"
        >
          Buat akun
        </Link>
      </div>
    </section>
  </main>
</template>
