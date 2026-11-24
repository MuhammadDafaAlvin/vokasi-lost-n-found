<script setup lang="ts">
  import { Link, useForm } from '@inertiajs/vue3';
  import type { Report } from '@/types/report';
  import reportRoutes from '@/routes/report';

  const props = withDefaults(
    defineProps<{ report: Report; ownerActions?: boolean }>(),
    { ownerActions: false },
  );

  const statusForm = useForm({});

  function reportTypeLabel(): string {
    return props.report.type === 'lost' ? 'Barang hilang' : 'Barang ditemukan';
  }

  function reportStatusLabel(): string {
    return props.report.status === 'active' ? 'Aktif' : 'Selesai';
  }

  function formattedEventDate(): string {
    return new Intl.DateTimeFormat('id-ID', {
      dateStyle: 'medium',
    }).format(new Date(props.report.event_date));
  }

  function resolveReport(): void {
    statusForm.patch(reportRoutes.status.update(props.report.id).url, {
      preserveScroll: true,
    });
  }
</script>

<template>
  <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md">
    <Link :href="reportRoutes.show(report.id).url" class="block">
      <div class="relative overflow-hidden">
      <img
        v-if="report.image_url"
        :src="report.image_url"
        :alt="`Foto ${report.title}`"
        class="h-52 w-full object-cover transition duration-300 group-hover:scale-[1.02]"
      />

      <div
        v-else
        class="flex h-52 items-center justify-center bg-slate-100 text-slate-400"
      >
        <svg class="h-10 w-10" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 19.5h16.5A1.75 1.75 0 0 0 22 17.75V6.25A1.75 1.75 0 0 0 20.25 4.5H3.75A1.75 1.75 0 0 0 2 6.25v11.5A1.75 1.75 0 0 0 3.75 19.5ZM15 8.25h.008v.008H15V8.25Z" />
        </svg>
      </div>

      <div class="absolute left-3 top-3 flex flex-wrap gap-2">
          <span
            class="rounded-full px-2.5 py-1 text-xs font-semibold shadow-sm"
            :class="report.type === 'lost' ? 'bg-orange-600 text-white' : 'bg-emerald-600 text-white'"
          >
            {{ reportTypeLabel() }}
          </span>

          <span
            class="rounded-full px-2.5 py-1 text-xs font-semibold shadow-sm"
            :class="report.status === 'active' ? 'bg-white text-slate-700' : 'bg-slate-800 text-white'"
          >
            {{ reportStatusLabel() }}
          </span>
      </div>
      </div>

      <div class="p-5">
        <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
          {{ report.category }}
        </p>
        <h2 class="line-clamp-1 text-lg font-semibold text-slate-900">
          {{ report.title }}
        </h2>

        <div class="mt-3 space-y-1.5 text-sm text-slate-500">
          <p class="flex items-center gap-2">
            <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
            </svg>
            <span class="line-clamp-1">{{ report.location }}</span>
          </p>
          <p class="flex items-center gap-2">
            <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M5.25 5.25h13.5A1.5 1.5 0 0 1 20.25 6.75v12A1.5 1.5 0 0 1 18.75 20.25H5.25a1.5 1.5 0 0 1-1.5-1.5v-12a1.5 1.5 0 0 1 1.5-1.5Z" />
            </svg>
            {{ formattedEventDate() }}
          </p>
        </div>
      </div>
    </Link>

    <div v-if="ownerActions" class="flex gap-2 border-t border-slate-100 p-4">
      <Link
        class="rounded-xl border border-slate-200 px-3.5 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
        :href="reportRoutes.edit(report.id).url"
      >
        Edit
      </Link>

      <button
        v-if="report.status === 'active'"
        class="rounded-xl bg-emerald-600 px-3.5 py-2 text-sm font-medium text-white transition hover:bg-emerald-700 disabled:opacity-50"
        :disabled="statusForm.processing"
        @click="resolveReport"
      >
        Tandai selesai
      </button>
    </div>
  </article>
</template>
