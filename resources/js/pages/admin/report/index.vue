<script setup lang="ts">
  import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
  import AdminReportFilters from '@/components/admin/admin-report-filters.vue';
  import AdminReportTable from '@/components/admin/admin-report-table.vue';
  import type { Auth } from '@/types/auth';
  import type {
    AdminReportFilters as AdminReportFiltersData,
    AdminReportPagination,
  } from '@/types/report';
  import { home } from '@/routes';
  import { logout as logoutRoute } from '@/routes/auth';

  defineProps<{
    filters: AdminReportFiltersData;
    reports: AdminReportPagination;
  }>();

  const page = usePage<{
    auth: Auth;
    flash: { success: string | null };
  }>();
  const logoutForm = useForm({});

  function logout(): void {
    logoutForm.post(logoutRoute().url);
  }
</script>

<template>
  <Head title="Moderasi Laporan" />

  <main class="mx-auto min-h-screen max-w-7xl px-6 py-10">
    <nav class="mb-8 flex items-center justify-between border-b pb-4">
      <Link class="font-semibold" :href="home().url">
        Lost &amp; Found Vokasi
      </Link>
      <div class="flex items-center gap-4 text-sm">
        <span>{{ page.props.auth.user?.name }} (Admin)</span>
        <button
          class="underline disabled:opacity-50"
          :disabled="logoutForm.processing"
          type="button"
          @click="logout"
        >
          Keluar
        </button>
      </div>
    </nav>

    <header class="mb-6">
      <h1 class="text-3xl font-semibold">Moderasi laporan</h1>
      <p class="mt-2 text-gray-600">
        Tinjau laporan, lalu sembunyikan atau tampilkan sesuai kebutuhan.
      </p>
    </header>

    <p
      v-if="page.props.flash.success"
      class="mb-5 rounded-lg bg-green-50 p-3 text-sm text-green-800"
      role="status"
    >
      {{ page.props.flash.success }}
    </p>

    <AdminReportFilters :filters="filters" />
    <AdminReportTable :reports="reports" />
  </main>
</template>
