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
  <form
    class="mb-6 grid gap-4 rounded-xl border bg-white p-5 sm:grid-cols-2 lg:grid-cols-4"
    @submit.prevent="submit"
  >
    <div>
      <label class="mb-1 block text-sm font-medium" for="admin-report-search">
        Nama barang
      </label>
      <input
        id="admin-report-search"
        v-model="form.q"
        class="w-full rounded-lg border px-3 py-2"
        maxlength="100"
        placeholder="Cari nama barang"
        type="search"
      />
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium" for="admin-report-type">
        Jenis
      </label>
      <select
        id="admin-report-type"
        v-model="form.type"
        class="w-full rounded-lg border px-3 py-2"
      >
        <option value="">Semua jenis</option>
        <option value="lost">Hilang</option>
        <option value="found">Ditemukan</option>
      </select>
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium" for="admin-report-status">
        Status laporan
      </label>
      <select
        id="admin-report-status"
        v-model="form.status"
        class="w-full rounded-lg border px-3 py-2"
      >
        <option value="">Semua status</option>
        <option value="active">Aktif</option>
        <option value="resolved">Selesai</option>
      </select>
    </div>

    <div>
      <label
        class="mb-1 block text-sm font-medium"
        for="admin-report-visibility"
      >
        Moderasi
      </label>
      <select
        id="admin-report-visibility"
        v-model="form.visibility"
        class="w-full rounded-lg border px-3 py-2"
      >
        <option value="">Semua laporan</option>
        <option value="visible">Terlihat</option>
        <option value="hidden">Disembunyikan</option>
      </select>
    </div>

    <div class="flex gap-3 sm:col-span-2 lg:col-span-4">
      <button
        class="rounded-lg bg-blue-700 px-4 py-2 font-medium text-white"
        type="submit"
      >
        Terapkan filter
      </button>

      <button
        class="rounded-lg border px-4 py-2 font-medium"
        type="button"
        @click="resetFilters"
      >
        Reset
      </button>
    </div>
  </form>
</template>
