<script setup lang="ts">
  import { Link, useForm, usePage } from '@inertiajs/vue3';
  import adminReportRoutes from '@/routes/admin/report';
  import authRoutes from '@/routes/auth';
  import { home } from '@/routes';
  import loginRoutes from '@/routes/login';
  import registerRoutes from '@/routes/register';
  import reportRoutes from '@/routes/report';
  import type { Auth } from '@/types/auth';

  const page = usePage<{ auth: Auth }>();
  const logoutForm = useForm({});

  const logoUrl = '/images/logo-web.png';

  function logout(): void {
    logoutForm.post(authRoutes.logout().url);
  }
</script>

<template>
  <header class="border-b border-slate-200 bg-white/90">
    <nav
      class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-5 py-4 sm:px-8"
      aria-label="Navigasi utama"
    >
      <Link class="flex items-center gap-3" :href="home().url">
        <img
          class="h-16 w-16 bg-white object-contain p-0.5"
          :src="logoUrl"
          alt="Logo Fakultas Vokasi UNESA"
        />
        <span class="leading-tight">
          <span class="block text-sm font-bold text-slate-900">
            Lost &amp; Found
          </span>
          <span class="block text-xs text-slate-500">
            Fakultas Vokasi UNESA
          </span>
        </span>
      </Link>

      <div class="flex flex-wrap items-center gap-2 text-sm">
        <template v-if="page.props.auth.user">
          <Link
            class="rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100"
            :href="reportRoutes.index().url"
          >
            Laporan
          </Link>
          <Link
            class="rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100"
            :href="reportRoutes.mine().url"
          >
            Laporan saya
          </Link>
          <Link
            class="rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100"
            :href="reportRoutes.create().url"
          >
            Buat laporan
          </Link>
          <Link
            v-if="page.props.auth.user.role === 'admin'"
            class="rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100"
            :href="adminReportRoutes.index().url"
          >
            Panel admin
          </Link>
          <span class="hidden px-2 text-slate-500 sm:inline">
            {{ page.props.auth.user.name }}
          </span>
          <button
            class="rounded-lg bg-red-600 px-3 py-2 text-slate-100 hover:bg-red-700 disabled:opacity-50"
            :disabled="logoutForm.processing"
            type="button"
            @click="logout"
          >
            Keluar
          </button>
        </template>
        <template v-else>
          <Link
            class="rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100"
            :href="loginRoutes.create().url"
          >
            Masuk
          </Link>
          <Link
            class="rounded-lg bg-orange-600 px-3 py-2 font-medium text-white hover:bg-orange-700"
            :href="registerRoutes.create().url"
          >
            Daftar
          </Link>
        </template>
      </div>
    </nav>
  </header>
</template>
