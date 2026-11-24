<script setup lang="ts">
  import { Head, usePage } from '@inertiajs/vue3';
  import AdminReportFilters from '@/components/admin/admin-report-filters.vue';
  import AdminReportTable from '@/components/admin/admin-report-table.vue';
  import type { Auth } from '@/types/auth';
  import type {
    AdminReportFilters as AdminReportFiltersData,
    AdminReportPagination,
  } from '@/types/report';
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
</script>

<template>
  <Head title="Moderasi Laporan" />
  <AppHeader />
  <main class="min-h-screen bg-slate-50">
    <div class="mx-auto max-w-7xl px-5 py-8 sm:px-8 sm:py-10">
      <header
        class="mb-7 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between"
      >
        <div>
          <p
            class="mb-2 text-xs font-semibold tracking-[0.18em] text-blue-700 uppercase"
          >
            Panel admin
          </p>
          <h1
            class="text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl"
          >
            Moderasi laporan
          </h1>
          <p class="mt-2 text-sm text-slate-500 sm:text-base">
            Tinjau laporan dan kelola visibilitas dengan cepat.
          </p>
        </div>
      </header>

      <p
        v-if="page.props.flash.success"
        class="mb-5 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800"
        role="status"
      >
        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
        {{ page.props.flash.success }}
      </p>

      <section
        class="mb-6 grid grid-cols-2 gap-3 lg:grid-cols-4"
        aria-label="Ringkasan laporan"
      >
        <article
          class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5"
        >
          <p class="text-xs font-medium text-slate-500 sm:text-sm">
            Semua laporan
          </p>
          <p class="mt-2 text-2xl font-bold text-slate-950 sm:text-3xl">
            {{ stats.total }}
          </p>
        </article>

        <article
          class="rounded-2xl border border-blue-100 bg-blue-50/60 p-4 shadow-sm sm:p-5"
        >
          <p class="text-xs font-medium text-blue-700 sm:text-sm">
            Laporan aktif
          </p>
          <p class="mt-2 text-2xl font-bold text-blue-800 sm:text-3xl">
            {{ stats.active }}
          </p>
        </article>

        <article
          class="rounded-2xl border border-emerald-100 bg-emerald-50/60 p-4 shadow-sm sm:p-5"
        >
          <p class="text-xs font-medium text-emerald-700 sm:text-sm">
            Laporan selesai
          </p>
          <p class="mt-2 text-2xl font-bold text-emerald-800 sm:text-3xl">
            {{ stats.resolved }}
          </p>
        </article>

        <article
          class="rounded-2xl border border-amber-100 bg-amber-50/70 p-4 shadow-sm sm:p-5"
        >
          <p class="text-xs font-medium text-amber-700 sm:text-sm">
            Disembunyikan
          </p>
          <p class="mt-2 text-2xl font-bold text-amber-800 sm:text-3xl">
            {{ stats.hidden }}
          </p>
        </article>
      </section>

      <AdminReportFilters :filters="filters" />
      <AdminReportTable :reports="reports" />
    </div>
  </main>
</template>
