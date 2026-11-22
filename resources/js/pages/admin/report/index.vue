<script setup lang="ts">
  import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
  import AdminReportFilters from '@/components/admin/admin-report-filters.vue';
  import AdminReportTable from '@/components/admin/admin-report-table.vue';
  import type { Auth } from '@/types/auth';
  import type {
    AdminReportFilters as AdminReportFiltersData,
    AdminReportPagination,
  } from '@/types/report';
  import { logout as logoutRoute } from '@/routes/auth';
  import AppHeader from '@/components/app-header.vue';

  defineProps<{
    filters: AdminReportFiltersData;
    reports: AdminReportPagination;
    stats: {
      total: number;
      active: number;
      resolved: number;
      hidden: number;
    };
  }>();

  const page = usePage<{
    auth: Auth;
    flash: { success: string | null };
  }>();
  const logoutForm = useForm({});

  function logout(): void {
    logoutForm.post(logoutRoute().url);
  }
</script>

<template>
  <Head title="Moderasi Laporan" />
  <AppHeader />
  <main class="mx-auto min-h-screen max-w-7xl px-6 py-10">
    <header class="mb-6">
      <h1 class="text-3xl font-semibold">Moderasi laporan</h1>
      <p class="mt-2 text-gray-600">
        Tinjau laporan, lalu sembunyikan atau tampilkan sesuai kebutuhan.
      </p>
    </header>

    <p
      v-if="page.props.flash.success"
      class="mb-5 rounded-lg bg-green-50 p-3 text-sm text-green-800"
      role="status"
    >
      {{ page.props.flash.success }}
    </p>
    <section
      class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4"
      aria-label="Ringkasan laporan"
    >
      <article class="rounded-xl border border-slate-200 bg-white p-5">
        <p class="text-sm text-slate-500">Semua laporan</p>
        <p class="mt-2 text-3xl font-semibold text-slate-900">
          {{ stats.total }}
        </p>
      </article>

      <article class="rounded-xl border border-slate-200 bg-white p-5">
        <p class="text-sm text-slate-500">Laporan aktif</p>
        <p class="mt-2 text-3xl font-semibold text-blue-700">
          {{ stats.active }}
        </p>
      </article>

      <article class="rounded-xl border border-slate-200 bg-white p-5">
        <p class="text-sm text-slate-500">Laporan selesai</p>
        <p class="mt-2 text-3xl font-semibold text-green-700">
          {{ stats.resolved }}
        </p>
      </article>

      <article class="rounded-xl border border-slate-200 bg-white p-5">
        <p class="text-sm text-slate-500">Disembunyikan</p>
        <p class="mt-2 text-3xl font-semibold text-amber-700">
          {{ stats.hidden }}
        </p>
      </article>
    </section>
    <AdminReportFilters :filters="filters" />
    <AdminReportTable :reports="reports" />
  </main>
</template>
