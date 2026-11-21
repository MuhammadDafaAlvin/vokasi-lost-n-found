<script setup lang="ts">
  import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
  import reportRoutes from '@/routes/report';
  import type { Report } from '@/types/report';

  const props = defineProps<{
    canEdit: boolean;
    canResolve: boolean;
    report: Report;
  }>();

  const page = usePage<{ flash: { success: string | null } }>();

  const statusForm = useForm({});

  function resolveReport(): void {
    statusForm.patch(reportRoutes.status.update(props.report.id).url, {
      preserveScroll: true,
    });
  }

  function formattedEventDate(): string {
    return new Intl.DateTimeFormat('id-ID', {
      dateStyle: 'long',
    }).format(new Date(props.report.event_date));
  }
</script>

<template>
  <Head :title="report.title" />

  <main class="mx-auto max-w-4xl px-6 py-10">
    <Link
      class="text-sm text-blue-700 underline"
      :href="reportRoutes.index().url"
    >
      Kembali ke daftar laporan
    </Link>

    <p
      v-if="page.props.flash.success"
      class="mt-5 text-green-700"
      role="status"
    >
      {{ page.props.flash.success }}
    </p>

    <article class="mt-5 overflow-hidden rounded-xl border bg-white">
      <img
        v-if="report.image_url"
        :src="report.image_url"
        :alt="`Foto ${report.title}`"
        class="max-h-[28rem] w-full object-cover"
      />

      <div class="space-y-5 p-6">
        <div class="flex flex-wrap items-center gap-2">
          <span
            class="rounded-full bg-blue-100 px-3 py-1 text-sm text-blue-800"
          >
            {{ report.type === 'lost' ? 'Barang hilang' : 'Barang ditemukan' }}
          </span>

          <span
            class="rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-700"
          >
            {{ report.status === 'active' ? 'Aktif' : 'Selesai' }}
          </span>

          <span
            v-if="report.is_hidden"
            class="rounded-full bg-amber-100 px-3 py-1 text-sm text-amber-800"
          >
            Disembunyikan
          </span>
        </div>

        <h1 class="text-3xl font-semibold">{{ report.title }}</h1>

        <dl class="grid gap-4 sm:grid-cols-2">
          <div>
            <dt class="text-sm text-gray-500">Kategori</dt>
            <dd class="font-medium">{{ report.category }}</dd>
          </div>

          <div>
            <dt class="text-sm text-gray-500">Warna</dt>
            <dd class="font-medium">
              {{ report.color || 'Tidak disebutkan' }}
            </dd>
          </div>

          <div>
            <dt class="text-sm text-gray-500">Lokasi</dt>
            <dd class="font-medium">{{ report.location }}</dd>
          </div>

          <div>
            <dt class="text-sm text-gray-500">Tanggal kejadian</dt>
            <dd class="font-medium">{{ formattedEventDate() }}</dd>
          </div>

          <div>
            <dt class="text-sm text-gray-500">Pelapor</dt>
            <dd class="font-medium">{{ report.user?.name }}</dd>
          </div>
        </dl>

        <div>
          <h2 class="font-semibold">Ciri-ciri barang</h2>

          <p class="mt-2 whitespace-pre-line text-gray-700">
            {{ report.description }}
          </p>
        </div>

        <div v-if="canEdit" class="flex flex-wrap gap-3 border-t pt-5">
          <Link
            class="rounded-lg border px-4 py-2"
            :href="reportRoutes.edit(report.id).url"
          >
            Edit laporan
          </Link>

          <button
            v-if="canResolve"
            class="rounded-lg bg-green-700 px-4 py-2 text-white disabled:opacity-50"
            :disabled="statusForm.processing"
            @click="resolveReport"
          >
            Tandai selesai
          </button>
        </div>
      </div>
    </article>
  </main>
</template>
