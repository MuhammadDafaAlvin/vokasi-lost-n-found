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
  <article class="overflow-hidden rounded-xl border bg-white shadow-sm">
    <Link :href="reportRoutes.show(report.id).url" class="block">
      <img
        v-if="report.image_url"
        :src="report.image_url"
        :alt="`Foto ${report.title}`"
        class="h-48 w-full object-cover"
      />

      <div
        v-else
        class="flex h-48 items-center justify-center bg-gray-100 text-sm text-gray-500"
      >
        Foto tidak tersedia
      </div>

      <div class="space-y-2 p-4">
        <div class="flex flex-wrap gap-2 text-xs">
          <span class="rounded-full bg-blue-100 px-2 py-1 text-blue-800">
            {{ reportTypeLabel() }}
          </span>

          <span class="rounded-full bg-gray-100 px-2 py-1 text-gray-700">
            {{ reportStatusLabel() }}
          </span>
        </div>

        <h2 class="text-lg font-semibold text-gray-900">
          {{ report.title }}
        </h2>

        <p class="text-sm text-gray-600">{{ report.location }}</p>

        <p class="text-sm text-gray-500">
          {{ formattedEventDate() }}
        </p>
      </div>
    </Link>

    <div v-if="ownerActions" class="flex gap-3 border-t p-4">
      <Link
        class="rounded-lg border px-3 py-2 text-sm"
        :href="reportRoutes.edit(report.id).url"
      >
        Edit
      </Link>

      <button
        v-if="report.status === 'active'"
        class="rounded-lg bg-green-700 px-3 py-2 text-sm text-white disabled:opacity-50"
        :disabled="statusForm.processing"
        @click="resolveReport"
      >
        Tandai selesai
      </button>
    </div>
  </article>
</template>
