<script setup lang="ts">
  import { Head, Link } from '@inertiajs/vue3';
  import ReportFilters from '@/components/reports/report-filters.vue';
  import ReportCard from '@/components/reports/report-card.vue';
  import reportRoutes from '@/routes/report';
  import type {
    Pagination,
    ReportFilters as ReportFiltersData,
  } from '@/types/report';
  import AppHeader from '@/components/app-header.vue';

  defineProps<{
    categories: string[];
    filters: ReportFiltersData;
    reports: Pagination;
  }>();
</script>

<template>
  <Head title="Daftar Laporan" />
  <div class="min-h-svh bg-[#f7f8fa] text-slate-900">
    <AppHeader />
    <main class="mx-auto max-w-7xl px-5 py-8 sm:px-8 sm:py-10">
      <header class="mb-7 flex flex-wrap items-end justify-between gap-4">
        <div>
          <p class="text-sm font-medium text-orange-700">Lost &amp; Found</p>
          <h1 class="mt-1 text-3xl font-semibold tracking-tight sm:text-4xl">
            Laporan barang
          </h1>
          <p
            class="mt-2 max-w-xl text-sm leading-6 text-slate-600 sm:text-base"
          >
            Temukan barang hilang atau lihat barang yang baru ditemukan di
            lingkungan Vokasi.
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

      <ReportFilters :categories="categories" :filters="filters" />

      <div class="mb-4 flex items-center justify-between">
        <p class="text-sm text-slate-500">
          <span class="font-semibold text-slate-900">{{ reports.total }}</span>
          laporan ditemukan
        </p>
      </div>

      <div
        v-if="reports.data.length"
        class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3"
      >
        <ReportCard
          v-for="report in reports.data"
          :key="report.id"
          :report="report"
        />
      </div>

      <section
        v-else
        class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center"
      >
        <div
          class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400"
        >
          <svg
            class="h-6 w-6"
            fill="none"
            stroke="currentColor"
            stroke-width="1.5"
            viewBox="0 0 24 24"
            aria-hidden="true"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="m21 21-4.35-4.35m1.35-5.15a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z"
            />
          </svg>
        </div>
        <h2 class="mt-4 font-semibold text-slate-900">
          {{
            filters.q || filters.type || filters.category
              ? 'Tidak ada laporan yang cocok'
              : 'Belum ada laporan barang'
          }}
        </h2>
        <p class="mt-1 text-sm text-slate-500">
          Coba ubah kata kunci atau reset filter untuk melihat laporan lain.
        </p>
      </section>

      <nav
        v-if="reports.last_page > 1"
        class="mt-8 flex items-center justify-between"
        aria-label="Navigasi halaman laporan"
      >
        <Link
          v-if="reports.prev_page_url"
          class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-400 hover:bg-slate-50"
          :href="reports.prev_page_url"
        >
          Sebelumnya
        </Link>
        <span v-else></span>
        <span class="text-sm text-slate-500">
          Halaman {{ reports.current_page }} dari {{ reports.last_page }}
        </span>
        <Link
          v-if="reports.next_page_url"
          class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-400 hover:bg-slate-50"
          :href="reports.next_page_url"
        >
          Berikutnya
        </Link>
      </nav>
    </main>
  </div>
</template>
