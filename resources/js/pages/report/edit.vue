<script setup lang="ts">
  import { Head, Link } from '@inertiajs/vue3';
  import ReportForm from '@/components/reports/report-form.vue';
  import reportRoutes from '@/routes/report';
  import type { Report } from '@/types/report';
  import AppHeader from '@/components/app-header.vue';

  const props = defineProps<{
    categories: string[];
    report: Report;
  }>();
</script>

<template>
  <Head :title="`Edit ${report.title}`" />
  <AppHeader />
  <main class="mx-auto max-w-3xl px-6 py-10">
    <Link
      class="text-sm text-blue-700 underline"
      :href="reportRoutes.show(report.id).url"
    >
      Kembali ke detail
    </Link>

    <h1 class="mt-4 mb-6 text-3xl font-semibold">Edit laporan</h1>

    <ReportForm
      :action-url="reportRoutes.update(props.report.id).url"
      :categories="categories"
      method="put"
      :report="report"
      submit-label="Simpan perubahan"
    />
  </main>
</template>
