<script setup lang="ts">
  import { router, useForm } from '@inertiajs/vue3';
  import { watch } from 'vue';
  import reportRoutes from '@/routes/report';
  import type { ReportFilters } from '@/types/report';

  const props = defineProps<{
    categories: string[];
    filters: ReportFilters;
  }>();

  const form = useForm<ReportFilters>({
    q: props.filters.q,
    type: props.filters.type,
    category: props.filters.category,
  });

  watch(
    () => props.filters,
    (filters: ReportFilters): void => {
      form.q = filters.q;
      form.type = filters.type;
      form.category = filters.category;
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

    if (form.category !== '') {
      query.category = form.category;
    }

    router.get(reportRoutes.index().url, query, {
      preserveScroll: true,
      preserveState: true,
      replace: true,
    });
  }

  function resetFilters(): void {
    form.q = '';
    form.type = '';
    form.category = '';

    router.get(
      reportRoutes.index().url,
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
    class="mb-8 grid gap-4 rounded-xl border bg-white p-5 sm:grid-cols-2 lg:grid-cols-4"
    role="search"
    @submit.prevent="submit"
  >
    <div class="sm:col-span-2">
      <label class="mb-1 block text-sm font-medium" for="report-search">
        Cari barang
      </label>
      <input
        id="report-search"
        v-model="form.q"
        class="w-full rounded-lg border px-3 py-2"
        maxlength="100"
        name="q"
        placeholder="Nama atau deskripsi barang"
        type="search"
      />
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium" for="report-type">
        Jenis laporan
      </label>
      <select
        id="report-type"
        v-model="form.type"
        class="w-full rounded-lg border px-3 py-2"
        name="type"
      >
        <option value="">Semua jenis</option>
        <option value="lost">Hilang</option>
        <option value="found">Ditemukan</option>
      </select>
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium" for="report-category">
        Kategori
      </label>
      <select
        id="report-category"
        v-model="form.category"
        class="w-full rounded-lg border px-3 py-2"
        name="category"
      >
        <option value="">Semua kategori</option>
        <option
          v-for="category in categories"
          :key="category"
          :value="category"
        >
          {{ category }}
        </option>
      </select>
    </div>

    <div class="flex flex-wrap gap-3 sm:col-span-2 lg:col-span-4">
      <button
        class="rounded-lg bg-orange-500 px-4 py-2 font-medium text-white disabled:opacity-50"
        :disabled="form.processing"
        type="submit"
      >
        Cari
      </button>
      <button
        class="rounded-lg border px-4 py-2 font-medium disabled:opacity-50"
        :disabled="form.processing"
        type="button"
        @click="resetFilters"
      >
        Reset filter
      </button>
    </div>
  </form>
</template>
