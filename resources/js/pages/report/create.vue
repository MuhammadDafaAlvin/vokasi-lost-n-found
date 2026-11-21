<script setup lang="ts">
  import { Head, Link } from '@inertiajs/vue3';
  import ReportForm from '@/components/reports/report-form.vue';
  import reportRoutes from '@/routes/report';

  defineProps<{ categories: string[] }>();
</script>

<template>
  <Head title="Buat Laporan" />

  <main class="mx-auto max-w-3xl px-6 py-10">
    <Link
      class="text-sm text-blue-700 underline"
      :href="reportRoutes.index().url"
    >
      Kembali ke laporan
    </Link>

    <h1 class="mt-4 mb-6 text-3xl font-semibold">Buat laporan barang</h1>

    <ReportForm
      :action-url="reportRoutes.store().url"
      :categories="categories"
      method="post"
      :report="null"
      submit-label="Simpan laporan"
    />
  </main>
</template>
