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
  import AppHeader from '@/components/app-header.vue';

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
  <AppHeader />
  <main class="mx-auto min-h-screen max-w-7xl px-6 py-10">
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
