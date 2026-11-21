<script setup lang="ts">
  import { Link, useForm } from '@inertiajs/vue3';
  import adminReportRoutes from '@/routes/admin/report';
  import reportRoutes from '@/routes/report';
  import type { AdminReportPagination } from '@/types/report';

  defineProps<{ reports: AdminReportPagination }>();

  const visibilityForm = useForm<{ is_hidden: boolean }>({
    is_hidden: false,
  });

  function reportTypeLabel(type: 'lost' | 'found'): string {
    return type === 'lost' ? 'Hilang' : 'Ditemukan';
  }

  function reportStatusLabel(status: 'active' | 'resolved'): string {
    return status === 'active' ? 'Aktif' : 'Selesai';
  }

  function formattedDate(date: string): string {
    return new Intl.DateTimeFormat('id-ID', {
      dateStyle: 'medium',
    }).format(new Date(date));
  }

  function toggleVisibility(reportId: number, isHidden: boolean): void {
    const nextVisibility = !isHidden;
    const action = nextVisibility ? 'sembunyikan' : 'tampilkan kembali';

    if (!window.confirm(`Yakin ingin ${action} laporan ini?`)) {
      return;
    }

    visibilityForm.is_hidden = nextVisibility;

    visibilityForm.patch(adminReportRoutes.visibility(reportId).url, {
      preserveScroll: true,
    });
  }
</script>

<template>
  <div
    v-if="reports.data.length"
    class="overflow-x-auto rounded-xl border bg-white"
  >
    <table class="w-full min-w-[900px] divide-y text-left text-sm">
      <thead class="bg-gray-50 text-xs text-gray-600 uppercase">
        <tr>
          <th class="px-4 py-3">Barang</th>
          <th class="px-4 py-3">Jenis / kategori</th>
          <th class="px-4 py-3">Pelapor</th>
          <th class="px-4 py-3">Tanggal</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3">Moderasi</th>
          <th class="px-4 py-3">Aksi</th>
        </tr>
      </thead>

      <tbody class="divide-y">
        <tr v-for="report in reports.data" :key="report.id">
          <td class="px-4 py-4">
            <Link
              class="font-medium text-blue-800 underline"
              :href="reportRoutes.show(report.id).url"
            >
              {{ report.title }}
            </Link>
          </td>

          <td class="px-4 py-4 text-gray-700">
            <div>{{ reportTypeLabel(report.type) }}</div>
            <div class="mt-1 text-gray-500">{{ report.category }}</div>
          </td>

          <td class="px-4 py-4">{{ report.user.name }}</td>

          <td class="px-4 py-4">
            {{ formattedDate(report.event_date) }}
          </td>

          <td class="px-4 py-4">
            {{ reportStatusLabel(report.status) }}
          </td>

          <td class="px-4 py-4">
            <span
              class="rounded-full px-2.5 py-1 text-xs font-medium"
              :class="
                report.is_hidden
                  ? 'bg-amber-100 text-amber-800'
                  : 'bg-green-100 text-green-800'
              "
            >
              {{ report.is_hidden ? 'Disembunyikan' : 'Terlihat' }}
            </span>
          </td>

          <td class="px-4 py-4">
            <button
              class="rounded-lg border px-3 py-2 text-xs font-medium whitespace-nowrap disabled:opacity-50"
              :disabled="visibilityForm.processing"
              type="button"
              @click="toggleVisibility(report.id, report.is_hidden)"
            >
              {{
                visibilityForm.processing
                  ? 'Memproses...'
                  : report.is_hidden
                    ? 'Tampilkan'
                    : 'Sembunyikan'
              }}
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <p v-else class="rounded-xl border bg-white p-8 text-center text-gray-600">
    Tidak ada laporan yang sesuai dengan filter.
  </p>

  <nav
    v-if="reports.last_page > 1"
    class="mt-6 flex items-center justify-between"
    aria-label="Navigasi halaman admin"
  >
    <Link
      v-if="reports.prev_page_url"
      class="rounded-lg border px-4 py-2"
      :href="reports.prev_page_url"
    >
      Sebelumnya
    </Link>

    <span v-else></span>

    <span class="text-sm text-gray-600">
      Halaman {{ reports.current_page }} dari {{ reports.last_page }}
    </span>

    <Link
      v-if="reports.next_page_url"
      class="rounded-lg border px-4 py-2"
      :href="reports.next_page_url"
    >
      Berikutnya
    </Link>
  </nav>
</template>
