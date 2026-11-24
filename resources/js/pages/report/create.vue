<script setup lang="ts">
  import { Head, Link } from '@inertiajs/vue3';
  import ReportForm from '@/components/reports/report-form.vue';
  import reportRoutes from '@/routes/report';
  import AppHeader from '@/components/app-header.vue';

  defineProps<{ categories: string[] }>();
</script>

<template>
  <Head title="Buat Laporan" />
  <div class="min-h-svh bg-[#f7f8fa] text-slate-900">
    <AppHeader />
    <main class="mx-auto max-w-5xl px-5 py-8 sm:px-8 sm:py-10">
      <Link
        class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-slate-900"
        :href="reportRoutes.index().url"
      >
        <svg
          class="h-4 w-4"
          fill="none"
          stroke="currentColor"
          stroke-width="1.8"
          viewBox="0 0 24 24"
          aria-hidden="true"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"
          />
        </svg>
        Kembali ke laporan
      </Link>

      <header class="mt-5 mb-6">
        <p class="text-sm font-medium text-orange-700">Laporan baru</p>
        <h1 class="mt-1 text-3xl font-semibold tracking-tight sm:text-4xl">
          Buat laporan barang
        </h1>
        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
          Isi informasi yang jelas agar laporan mudah dikenali dan
          ditindaklanjuti.
        </p>
      </header>

      <section
        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7"
      >
        <ReportForm
          :action-url="reportRoutes.store().url"
          :categories="categories"
          method="post"
          :report="null"
          submit-label="Simpan laporan"
        />
      </section>
    </main>
  </div>
</template>
