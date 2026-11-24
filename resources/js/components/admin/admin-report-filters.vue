<script setup lang="ts">
  import { router, useForm } from '@inertiajs/vue3';
  import { watch } from 'vue';
  import adminReportRoutes from '@/routes/admin/report';
  import type { AdminReportFilters } from '@/types/report';

  const props = defineProps<{ filters: AdminReportFilters }>();

  const form = useForm<AdminReportFilters>({ ...props.filters });

  watch(
    () => props.filters,
    (filters: AdminReportFilters): void => {
      form.q = filters.q;
      form.type = filters.type;
      form.status = filters.status;
      form.visibility = filters.visibility;
    },
  );

  function submit(): void {
    const query: Record<string, string> = {};
    const search = form.q.trim();

    if (search !== '') {
      query.q = search;
    }

    if (form.type !== '') {
      query.type = form.type;
    }

    if (form.status !== '') {
      query.status = form.status;
    }

    if (form.visibility !== '') {
      query.visibility = form.visibility;
    }

    router.get(adminReportRoutes.index().url, query, {
      preserveScroll: true,
      preserveState: true,
      replace: true,
    });
  }

  function resetFilters(): void {
    form.q = '';
    form.type = '';
    form.status = '';
    form.visibility = '';

    router.get(
      adminReportRoutes.index().url,
      {},
      {
        preserveScroll: true,
        preserveState: true,
        replace: true,
      },
    );
  }
</script>

<template>
  <section class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
    <div class="mb-4 flex items-center justify-between">
      <h2 class="text-sm font-semibold text-slate-900">Filter laporan</h2>
      <button
        class="text-sm font-medium text-slate-500 transition hover:text-slate-900"
        type="button"
        @click="resetFilters"
      >
        Reset
      </button>
    </div>

    <form
      class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[minmax(220px,1.6fr)_repeat(3,minmax(150px,1fr))]"
      @submit.prevent="submit"
    >
      <div>
        <label class="mb-1.5 block text-xs font-medium text-slate-600" for="admin-report-search">
          Nama barang
        </label>
        <input
          id="admin-report-search"
          v-model="form.q"
          class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-3 focus:ring-blue-100"
          maxlength="100"
          placeholder="Cari nama barang"
          type="search"
        />
      </div>

      <div>
        <label class="mb-1.5 block text-xs font-medium text-slate-600" for="admin-report-type">
          Jenis
        </label>
        <select
          id="admin-report-type"
          v-model="form.type"
          class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-3 focus:ring-blue-100"
        >
          <option value="">Semua jenis</option>
          <option value="lost">Hilang</option>
          <option value="found">Ditemukan</option>
        </select>
      </div>

      <div>
        <label class="mb-1.5 block text-xs font-medium text-slate-600" for="admin-report-status">
          Status laporan
        </label>
        <select
          id="admin-report-status"
          v-model="form.status"
          class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-3 focus:ring-blue-100"
        >
          <option value="">Semua status</option>
          <option value="active">Aktif</option>
          <option value="resolved">Selesai</option>
        </select>
      </div>

      <div>
        <label
          class="mb-1.5 block text-xs font-medium text-slate-600"
          for="admin-report-visibility"
        >
          Moderasi
        </label>
        <select
          id="admin-report-visibility"
          v-model="form.visibility"
          class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-3 focus:ring-blue-100"
        >
          <option value="">Semua laporan</option>
          <option value="visible">Terlihat</option>
          <option value="hidden">Disembunyikan</option>
        </select>
      </div>

      <div class="sm:col-span-2 lg:col-span-4 lg:flex lg:justify-end">
        <button
          class="h-10 w-full rounded-lg bg-slate-900 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:ring-3 focus:ring-slate-200 lg:w-auto"
          type="submit"
        >
          Terapkan filter
        </button>
      </div>
    </form>
  </section>
</template>
