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
  <section
    v-if="reports.data.length"
    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
  >
    <div class="overflow-x-auto">
      <table class="w-full min-w-225 text-left text-sm">
        <thead class="border-b border-slate-200 bg-slate-50/80">
          <tr class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">
            <th class="px-5 py-3.5">Barang</th>
            <th class="px-4 py-3.5">Jenis / kategori</th>
            <th class="px-4 py-3.5">Pelapor</th>
            <th class="px-4 py-3.5">Tanggal</th>
            <th class="px-4 py-3.5">Status</th>
            <th class="px-4 py-3.5">Moderasi</th>
            <th class="px-5 py-3.5 text-right">Aksi</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-100">
          <tr
            v-for="report in reports.data"
            :key="report.id"
            class="text-slate-700 transition-colors hover:bg-slate-50/70"
          >
            <td class="px-5 py-4">
              <Link
                class="font-semibold text-slate-950 underline decoration-slate-300 underline-offset-4 transition hover:text-blue-700 hover:decoration-blue-300"
                :href="reportRoutes.show(report.id).url"
              >
                {{ report.title }}
              </Link>
            </td>

            <td class="px-4 py-4">
              <span
                class="mb-1 inline-flex rounded-md px-2 py-0.5 text-xs font-semibold"
                :class="
                  report.type === 'lost'
                    ? 'bg-orange-50 text-orange-700'
                    : 'bg-cyan-50 text-cyan-700'
                "
              >
                {{ reportTypeLabel(report.type) }}
              </span>
              <div class="text-xs text-slate-500">{{ report.category }}</div>
            </td>

            <td class="px-4 py-4 text-slate-700">{{ report.user.name }}</td>

            <td class="px-4 py-4 whitespace-nowrap text-slate-600">
              {{ formattedDate(report.event_date) }}
            </td>

            <td class="px-4 py-4">
              <span
                class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold"
                :class="
                  report.status === 'active'
                    ? 'bg-blue-50 text-blue-700'
                    : 'bg-emerald-50 text-emerald-700'
                "
              >
                {{ reportStatusLabel(report.status) }}
              </span>
            </td>

            <td class="px-4 py-4">
              <span
                class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold"
                :class="
                  report.is_hidden
                    ? 'bg-amber-50 text-amber-700'
                    : 'bg-slate-100 text-slate-600'
                "
              >
                <span
                  class="h-1.5 w-1.5 rounded-full"
                  :class="report.is_hidden ? 'bg-amber-500' : 'bg-emerald-500'"
                ></span>
                {{ report.is_hidden ? 'Disembunyikan' : 'Terlihat' }}
              </span>
            </td>

            <td class="px-5 py-4 text-right">
              <button
                class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 whitespace-nowrap shadow-sm transition hover:border-slate-300 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
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
  </section>

  <section v-else class="rounded-2xl border border-slate-200 bg-white p-10 text-center">
    <p class="text-sm font-semibold text-slate-700">Tidak ada laporan yang ditemukan</p>
    <p class="mt-1 text-xs text-slate-500">Coba ubah atau reset filter pencarian.</p>
  </section>

  <nav
    v-if="reports.last_page > 1"
    class="mt-5 flex items-center justify-between"
    aria-label="Navigasi halaman admin"
  >
    <Link
      v-if="reports.prev_page_url"
      class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50"
      :href="reports.prev_page_url"
    >
      Sebelumnya
    </Link>
    <span v-else></span>

    <span class="text-xs font-medium text-slate-500">
      Halaman {{ reports.current_page }} dari {{ reports.last_page }}
    </span>

    <Link
      v-if="reports.next_page_url"
      class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50"
      :href="reports.next_page_url"
    >
      Berikutnya
    </Link>
  </nav>
</template>
