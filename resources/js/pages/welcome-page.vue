<script setup lang="ts">
  import { Head, Link, usePage } from '@inertiajs/vue3';
  import AppHeader from '@/components/app-header.vue';
  import loginRoutes from '@/routes/login';
  import registerRoutes from '@/routes/register';
  import reportRoutes from '@/routes/report';
  import type { Auth } from '@/types/auth';

  const page = usePage<{ auth: Auth }>();
</script>

<template>
  <Head title="Lost & Found Fakultas Vokasi UNESA" />

  <div class="min-h-svh bg-[#f7f8fa] text-slate-900">
    <AppHeader />

    <main
      class="mx-auto grid min-h-[calc(100svh-81px)] max-w-7xl items-center gap-10 px-5 py-10 sm:px-8 lg:grid-cols-[1.1fr_0.9fr]"
    >
      <section class="max-w-2xl">
        <p
          class="inline-flex items-center gap-2 rounded-full border border-orange-200 bg-white px-4 py-2 text-sm font-medium text-orange-800 shadow-sm"
        >
          <span class="h-2 w-2 rounded-full bg-orange-500"></span>
          Layanan Fakultas Vokasi UNESA
        </p>

        <h1
          class="mt-6 text-4xl leading-tight font-semibold tracking-tight sm:text-6xl"
        >
          Temukan kembali barang yang berarti.
        </h1>
        <p class="mt-5 max-w-xl text-base leading-7 text-slate-600 sm:text-lg">
          Laporkan barang hilang atau ditemukan di lingkungan Fakultas Vokasi.
          Informasi yang jelas membantu pemilik dan penemu saling terhubung.
        </p>

        <div class="mt-8 flex flex-wrap gap-3">
          <Link
            v-if="page.props.auth.user"
            class="rounded-xl bg-orange-600 px-5 py-3 font-semibold text-white shadow-sm hover:bg-orange-700"
            :href="reportRoutes.index().url"
          >
            Lihat laporan
          </Link>
          <Link
            v-if="page.props.auth.user"
            class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-semibold text-slate-800 hover:bg-slate-50"
            :href="reportRoutes.create().url"
          >
            Buat laporan
          </Link>
          <template v-else>
            <Link
              class="rounded-xl bg-orange-600 px-5 py-3 font-semibold text-white shadow-sm hover:bg-orange-700"
              :href="loginRoutes.create().url"
            >
              Masuk untuk mulai
            </Link>
            <Link
              class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-semibold text-slate-800 hover:bg-slate-50"
              :href="registerRoutes.create().url"
            >
              Buat akun
            </Link>
          </template>
        </div>

        <p v-if="page.props.auth.user" class="mt-4 text-sm text-slate-500">
          Anda masuk sebagai {{ page.props.auth.user.role }}.
        </p>
      </section>

      <aside class="hidden lg:block">
        <div
          class="rounded-3xl border border-slate-200 bg-white p-7 shadow-xl shadow-slate-200/60"
        >
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-orange-700">
                Lost &amp; Found
              </p>
              <h2 class="mt-1 text-2xl font-semibold">Alur sederhana</h2>
            </div>
            <img
              class="h-14 w-14 object-contain"
              src="https://statik.unesa.ac.id/profileunesa_konten_statik/uploads/vokasi/gallery/d5a04d59-54ae-4aae-a3dd-f624420c96d4.png"
              alt=""
            />
          </div>

          <ol class="mt-8 space-y-5">
            <li class="flex gap-4">
              <span
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-orange-100 font-semibold text-orange-800"
                >1</span
              >
              <div>
                <h3 class="font-semibold">Buat laporan</h3>
                <p class="mt-1 text-sm leading-6 text-slate-600">
                  Tambahkan nama barang, lokasi, tanggal, dan foto bila ada.
                </p>
              </div>
            </li>
            <li class="flex gap-4">
              <span
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-orange-100 font-semibold text-orange-800"
                >2</span
              >
              <div>
                <h3 class="font-semibold">Cari dan hubungi</h3>
                <p class="mt-1 text-sm leading-6 text-slate-600">
                  Jelajahi laporan dan gunakan komentar pada detail laporan.
                </p>
              </div>
            </li>
          </ol>
          <p class="mt-8 border-t border-slate-100 pt-5 text-xs text-slate-500">
            Gunakan informasi secukupnya dan jangan membagikan data pribadi di
            komentar publik.
          </p>
        </div>
      </aside>
    </main>
  </div>
</template>
