<script setup lang="ts">
  import { Head, Link, usePage } from '@inertiajs/vue3';
  import ReportCard from '@/components/reports/report-card.vue';
  import reportRoutes from '@/routes/report';
  import type { Pagination } from '@/types/report';
  import AppHeader from '@/components/app-header.vue';

  const props = defineProps<{ reports: Pagination }>();

  const page = usePage<{ flash: { success: string | null } }>();
</script>

<template>
  <Head title="Laporan Saya" />
  <div class="min-h-svh bg-[#f7f8fa] text-slate-900">
    <AppHeader />
    <main class="mx-auto max-w-7xl px-5 py-8 sm:px-8 sm:py-10">
      <Link
        class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-slate-900"
        :href="reportRoutes.index().url"
      >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        Semua laporan
      </Link>

      <header class="mt-5 mb-7 flex flex-wrap items-end justify-between gap-4">
        <div>
          <p class="text-sm font-medium text-orange-700">Area pengguna</p>
          <h1 class="mt-1 text-3xl font-semibold tracking-tight sm:text-4xl">
            Laporan saya
          </h1>
          <p class="mt-2 text-sm text-slate-600">
            Kelola laporan barang hilang atau ditemukan yang Anda buat.
          </p>
        </div>

        <Link
          class="inline-flex items-center rounded-xl bg-orange-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-orange-700"
          :href="reportRoutes.create().url"
        >
          <span class="mr-2 text-lg leading-none">+</span>
          Buat laporan
        </Link>
      </header>

      <div
        v-if="page.props.flash.success"
        class="mb-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
        role="status"
      >
        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        {{ page.props.flash.success }}
      </div>

      <div class="mb-4 flex items-center justify-between">
        <p class="text-sm text-slate-500">
          <span class="font-semibold text-slate-900">{{ props.reports.total }}</span>
          laporan dibuat
        </p>
      </div>

      <div
        v-if="props.reports.data.length"
        class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3"
      >
        <ReportCard
          v-for="report in props.reports.data"
          :key="report.id"
          :report="report"
          owner-actions
        />
      </div>

      <section
        v-else
        class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center"
      >
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-orange-50 text-orange-600">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
        </div>
        <h2 class="mt-4 font-semibold text-slate-900">Belum ada laporan</h2>
        <p class="mt-1 text-sm text-slate-500">
          Buat laporan pertama Anda untuk mulai mencatat barang hilang atau ditemukan.
        </p>
        <Link
          class="mt-5 inline-flex rounded-xl bg-orange-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-orange-700"
          :href="reportRoutes.create().url"
        >
          Buat laporan
        </Link>
      </section>

      <nav
        v-if="props.reports.last_page > 1"
        class="mt-8 flex items-center justify-between"
        aria-label="Navigasi halaman laporan"
      >
        <Link
          v-if="props.reports.prev_page_url"
          class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-400 hover:bg-slate-50"
          :href="props.reports.prev_page_url"
        >
          Sebelumnya
        </Link>
        <span v-else></span>

        <span class="text-sm text-slate-500">
          Halaman {{ props.reports.current_page }} dari
          {{ props.reports.last_page }}
        </span>

        <Link
          v-if="props.reports.next_page_url"
          class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-400 hover:bg-slate-50"
          :href="props.reports.next_page_url"
        >
          Berikutnya
        </Link>
      </nav>
    </main>
  </div>
</template>
