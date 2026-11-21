<script setup lang="ts">
  import { Head, Link, usePage } from '@inertiajs/vue3';
  import ReportCard from '@/components/reports/report-card.vue';
  import reportRoutes from '@/routes/report';
  import type { Pagination } from '@/types/report';

  const props = defineProps<{ reports: Pagination }>();

  const page = usePage<{ flash: { success: string | null } }>();
</script>

<template>
  <Head title="Laporan Saya" />

  <main class="mx-auto min-h-screen max-w-6xl px-6 py-10">
    <header class="mb-8 flex flex-wrap items-center justify-between gap-4">
      <div>
        <Link
          class="text-sm text-blue-700 underline"
          :href="reportRoutes.index().url"
        >
          Kembali ke semua laporan
        </Link>

        <h1 class="mt-3 text-3xl font-semibold">Laporan saya</h1>
      </div>

      <Link
        class="rounded-lg bg-blue-700 px-4 py-2 text-white"
        :href="reportRoutes.create().url"
      >
        Buat laporan
      </Link>
    </header>

    <p
      v-if="page.props.flash.success"
      class="mb-5 text-green-700"
      role="status"
    >
      {{ page.props.flash.success }}
    </p>

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

    <p v-else class="rounded-xl border bg-white p-8 text-center text-gray-600">
      Anda belum membuat laporan.
    </p>

    <nav
      v-if="props.reports.last_page > 1"
      class="mt-8 flex items-center justify-between"
      aria-label="Navigasi halaman laporan"
    >
      <Link
        v-if="props.reports.prev_page_url"
        class="rounded border px-4 py-2"
        :href="props.reports.prev_page_url"
      >
        Sebelumnya
      </Link>

      <span v-else></span>

      <span class="text-sm text-gray-600">
        Halaman {{ props.reports.current_page }} dari
        {{ props.reports.last_page }}
      </span>

      <Link
        v-if="props.reports.next_page_url"
        class="rounded border px-4 py-2"
        :href="props.reports.next_page_url"
      >
        Berikutnya
      </Link>
    </nav>
  </main>
</template>
