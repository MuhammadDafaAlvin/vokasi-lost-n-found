<script setup lang="ts">
  import { Head, Link } from '@inertiajs/vue3';
  import ReportCard from '@/components/reports/report-card.vue';
  import reportRoutes from '@/routes/report';
  import type { Pagination } from '@/types/report';

  defineProps<{ reports: Pagination }>();
</script>

<template>
  <Head title="Daftar Laporan" />

  <main class="mx-auto min-h-screen max-w-6xl px-6 py-10">
    <header class="mb-8 flex flex-wrap items-center justify-between gap-4">
      <div>
        <h1 class="text-3xl font-semibold">Laporan barang</h1>

        <p class="mt-2 text-gray-600">
          Lihat barang hilang dan ditemukan di Fakultas Vokasi.
        </p>
      </div>

      <div class="flex gap-3">
        <Link
          class="rounded-lg border px-4 py-2"
          :href="reportRoutes.mine().url"
        >
          Laporan saya
        </Link>

        <Link
          class="rounded-lg bg-blue-700 px-4 py-2 text-white"
          :href="reportRoutes.create().url"
        >
          Buat laporan
        </Link>
      </div>
    </header>

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

    <p v-else class="rounded-xl border bg-white p-8 text-center text-gray-600">
      Belum ada laporan barang.
    </p>

    <nav
      v-if="reports.last_page > 1"
      class="mt-8 flex items-center justify-between"
      aria-label="Navigasi halaman laporan"
    >
      <Link
        v-if="reports.prev_page_url"
        class="rounded border px-4 py-2"
        :href="reports.prev_page_url"
      >
        Sebelumnya
      </Link>

      <span v-else></span>

      <span class="text-sm text-gray-600">
        Halaman {{ reports.current_page }} dari {{ reports.last_page }}
      </span>

      <Link
        v-if="reports.next_page_url"
        class="rounded border px-4 py-2"
        :href="reports.next_page_url"
      >
        Berikutnya
      </Link>
    </nav>
  </main>
</template>
