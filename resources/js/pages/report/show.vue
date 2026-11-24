<script setup lang="ts">
  import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
  import CommentForm from '@/components/comments/comment-form.vue';
  import CommentList from '@/components/comments/comment-list.vue';
  import reportRoutes from '@/routes/report';
  import type { ReportComment } from '@/types/comment';
  import type { Report } from '@/types/report';
  import AppHeader from '@/components/app-header.vue';

  const props = defineProps<{
    canComment: boolean;
    canEdit: boolean;
    canResolve: boolean;
    comments: ReportComment[];
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
  <div class="min-h-svh bg-[#f7f8fa] text-slate-900">
    <AppHeader />
    <main class="mx-auto max-w-5xl px-5 py-8 sm:px-8 sm:py-10">
      <Link
        class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-slate-900"
        :href="reportRoutes.index().url"
      >
        <svg
          class="h-4 w-4"
          fill="none"
          stroke="currentColor"
          stroke-width="1.8"
          viewBox="0 0 24 24"
          aria-hidden="true"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"
          />
        </svg>
        Semua laporan
      </Link>

      <p
        v-if="page.props.flash.success"
        class="mt-5 flex items-center rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
        role="status"
      >
        {{ page.props.flash.success }}
      </p>

      <article
        class="mt-5 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
      >
        <div
          v-if="report.image_url"
          class="h-72 overflow-hidden bg-slate-100 sm:h-96"
        >
          <img
            :src="report.image_url"
            :alt="`Foto ${report.title}`"
            class="h-full w-full object-cover"
          />
        </div>

        <div
          v-else
          class="flex h-48 items-center justify-center bg-slate-100 text-slate-400"
        >
          <svg
            class="h-10 w-10"
            fill="none"
            stroke="currentColor"
            stroke-width="1.3"
            viewBox="0 0 24 24"
            aria-hidden="true"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 19.5h16.5A1.75 1.75 0 0 0 22 17.75V6.25A1.75 1.75 0 0 0 20.25 4.5H3.75A1.75 1.75 0 0 0 2 6.25v11.5A1.75 1.75 0 0 0 3.75 19.5ZM15 8.25h.008v.008H15V8.25Z"
            />
          </svg>
        </div>

        <div class="p-5 sm:p-7">
          <div class="flex flex-wrap items-center gap-2">
            <span
              class="rounded-full px-3 py-1 text-sm font-semibold"
              :class="
                report.type === 'lost'
                  ? 'bg-orange-100 text-orange-800'
                  : 'bg-emerald-100 text-emerald-800'
              "
            >
              {{
                report.type === 'lost' ? 'Barang hilang' : 'Barang ditemukan'
              }}
            </span>
            <span
              class="rounded-full px-3 py-1 text-sm font-semibold"
              :class="
                report.status === 'active'
                  ? 'bg-slate-100 text-slate-700'
                  : 'bg-slate-800 text-white'
              "
            >
              {{ report.status === 'active' ? 'Aktif' : 'Selesai' }}
            </span>
            <span
              v-if="report.is_hidden"
              class="rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-800"
            >
              Disembunyikan
            </span>
          </div>

          <h1 class="mt-5 text-3xl font-semibold tracking-tight sm:text-4xl">
            {{ report.title }}
          </h1>

          <dl class="mt-6 grid gap-3 sm:grid-cols-2">
            <div class="rounded-xl bg-slate-50 p-4">
              <dt
                class="text-xs font-semibold tracking-wide text-slate-400 uppercase"
              >
                Kategori
              </dt>
              <dd class="mt-1 font-medium text-slate-800">
                {{ report.category }}
              </dd>
            </div>
            <div class="rounded-xl bg-slate-50 p-4">
              <dt
                class="text-xs font-semibold tracking-wide text-slate-400 uppercase"
              >
                Warna
              </dt>
              <dd class="mt-1 font-medium text-slate-800">
                {{ report.color || 'Tidak disebutkan' }}
              </dd>
            </div>
            <div class="rounded-xl bg-slate-50 p-4">
              <dt
                class="text-xs font-semibold tracking-wide text-slate-400 uppercase"
              >
                Lokasi
              </dt>
              <dd class="mt-1 font-medium text-slate-800">
                {{ report.location }}
              </dd>
            </div>
            <div class="rounded-xl bg-slate-50 p-4">
              <dt
                class="text-xs font-semibold tracking-wide text-slate-400 uppercase"
              >
                Tanggal kejadian
              </dt>
              <dd class="mt-1 font-medium text-slate-800">
                {{ formattedEventDate() }}
              </dd>
            </div>
          </dl>

          <div class="mt-6 border-t border-slate-100 pt-6">
            <p
              class="text-xs font-semibold tracking-wide text-slate-400 uppercase"
            >
              Pelapor
            </p>
            <p class="mt-1 font-medium text-slate-800">
              {{ report.user?.name }}
            </p>
          </div>

          <div class="mt-6">
            <h2 class="font-semibold text-slate-900">Ciri-ciri barang</h2>
            <p class="mt-2 leading-7 whitespace-pre-line text-slate-600">
              {{ report.description }}
            </p>
          </div>

          <div
            v-if="canEdit"
            class="mt-6 flex flex-wrap gap-2 border-t border-slate-100 pt-5"
          >
            <Link
              class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
              :href="reportRoutes.edit(report.id).url"
            >
              Edit laporan
            </Link>
            <button
              v-if="canResolve"
              class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700 disabled:opacity-50"
              :disabled="statusForm.processing"
              type="button"
              @click="resolveReport"
            >
              Tandai selesai
            </button>
          </div>
        </div>
      </article>

      <section
        class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7"
      >
        <h2 class="text-xl font-semibold">Komentar</h2>
        <div class="mt-5">
          <CommentList :comments="comments" :report-id="report.id" />
        </div>
        <div class="mt-6 border-t border-slate-100 pt-6">
          <CommentForm v-if="canComment" :report-id="report.id" />
          <p
            v-else
            class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-600"
          >
            Komentar baru tidak tersedia untuk laporan tersembunyi atau selesai.
          </p>
        </div>
      </section>
    </main>
  </div>
</template>
