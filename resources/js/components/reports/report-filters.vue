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
    class="mb-5 grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-[minmax(220px,1.5fr)_minmax(140px,.7fr)_minmax(140px,.7fr)_auto]"
    role="search"
    @submit.prevent="submit"
  >
    <div>
      <label
        class="mb-1.5 block text-xs font-semibold tracking-wide text-slate-500 uppercase"
        for="report-search"
      >
        Cari barang
      </label>
      <div class="relative">
        <svg
          class="pointer-events-none absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400"
          fill="none"
          stroke="currentColor"
          stroke-width="1.7"
          viewBox="0 0 24 24"
          aria-hidden="true"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="m21 21-4.35-4.35m1.35-5.15a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z"
          />
        </svg>
        <input
          id="report-search"
          v-model="form.q"
          class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 pr-3 pl-9 text-sm transition outline-none placeholder:text-slate-400 focus:border-orange-400 focus:bg-white focus:ring-3 focus:ring-orange-100"
          maxlength="100"
          name="q"
          placeholder="Nama atau deskripsi barang"
          type="search"
        />
      </div>
    </div>

    <div>
      <label
        class="mb-1.5 block text-xs font-semibold tracking-wide text-slate-500 uppercase"
        for="report-type"
      >
        Jenis laporan
      </label>
      <select
        id="report-type"
        v-model="form.type"
        class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm transition outline-none focus:border-orange-400 focus:bg-white focus:ring-3 focus:ring-orange-100"
        name="type"
      >
        <option value="">Semua jenis</option>
        <option value="lost">Hilang</option>
        <option value="found">Ditemukan</option>
      </select>
    </div>

    <div>
      <label
        class="mb-1.5 block text-xs font-semibold tracking-wide text-slate-500 uppercase"
        for="report-category"
      >
        Kategori
      </label>
      <select
        id="report-category"
        v-model="form.category"
        class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm transition outline-none focus:border-orange-400 focus:bg-white focus:ring-3 focus:ring-orange-100"
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

    <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-1">
      <button
        class="h-11 flex-1 rounded-xl bg-orange-600 px-4 text-sm font-semibold text-white transition hover:bg-orange-700 disabled:opacity-50"
        :disabled="form.processing"
        type="submit"
      >
        Cari
      </button>
      <button
        class="h-11 rounded-xl border border-slate-200 px-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50 disabled:opacity-50"
        :disabled="form.processing"
        type="button"
        @click="resetFilters"
      >
        Reset filter
      </button>
    </div>
  </form>
</template>
