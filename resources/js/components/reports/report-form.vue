<script setup lang="ts">
  import { useForm } from '@inertiajs/vue3';
  import type { Report, ReportType } from '@/types/report';

  type ReportFormData = {
    _method: 'put' | null;
    type: ReportType;
    title: string;
    category: string;
    description: string;
    color: string;
    location: string;
    event_date: string;
    image: File | null;
  };

  const props = defineProps<{
    actionUrl: string;
    categories: string[];
    method: 'post' | 'put';
    report: Report | null;
    submitLabel: string;
  }>();

  const form = useForm<ReportFormData>({
    _method: props.method === 'put' ? 'put' : null,
    type: props.report?.type ?? 'lost',
    title: props.report?.title ?? '',
    category: props.report?.category ?? '',
    description: props.report?.description ?? '',
    color: props.report?.color ?? '',
    location: props.report?.location ?? '',
    event_date: props.report?.event_date.slice(0, 10) ?? '',
    image: null,
  });

  function selectImage(event: Event): void {
    const input = event.currentTarget;

    if (input instanceof HTMLInputElement) {
      form.image = input.files?.item(0) ?? null;
    }
  }

  function submit(): void {
    form.post(props.actionUrl);
  }
</script>

<template>
  <form class="space-y-6" @submit.prevent="submit">
    <div class="grid gap-5 sm:grid-cols-2">
      <div>
        <label
          class="mb-1.5 block text-sm font-semibold text-slate-700"
          for="type"
        >
          Jenis laporan
        </label>

        <select
          id="type"
          v-model="form.type"
          class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm transition outline-none focus:border-orange-400 focus:bg-white focus:ring-3 focus:ring-orange-100"
          required
        >
          <option value="lost">Barang hilang</option>
          <option value="found">Barang ditemukan</option>
        </select>

        <p v-if="form.errors.type" class="mt-1 text-sm text-red-700">
          {{ form.errors.type }}
        </p>
      </div>

      <div>
        <label
          class="mb-1.5 block text-sm font-semibold text-slate-700"
          for="title"
        >
          Nama barang
        </label>

        <input
          id="title"
          v-model="form.title"
          class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm transition outline-none placeholder:text-slate-400 focus:border-orange-400 focus:bg-white focus:ring-3 focus:ring-orange-100"
          maxlength="150"
          placeholder="Contoh: Dompet hitam"
          required
        />

        <p v-if="form.errors.title" class="mt-1 text-sm text-red-700">
          {{ form.errors.title }}
        </p>
      </div>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
      <div>
        <label
          class="mb-1.5 block text-sm font-semibold text-slate-700"
          for="category"
        >
          Kategori
        </label>

        <select
          id="category"
          v-model="form.category"
          class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm transition outline-none focus:border-orange-400 focus:bg-white focus:ring-3 focus:ring-orange-100"
          required
        >
          <option disabled value="">Pilih kategori</option>

          <option
            v-for="category in categories"
            :key="category"
            :value="category"
          >
            {{ category }}
          </option>
        </select>

        <p v-if="form.errors.category" class="mt-1 text-sm text-red-700">
          {{ form.errors.category }}
        </p>
      </div>

      <div>
        <label
          class="mb-1.5 block text-sm font-semibold text-slate-700"
          for="color"
        >
          Warna
        </label>

        <input
          id="color"
          v-model="form.color"
          class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm transition outline-none placeholder:text-slate-400 focus:border-orange-400 focus:bg-white focus:ring-3 focus:ring-orange-100"
          maxlength="50"
          placeholder="Opsional"
        />

        <p v-if="form.errors.color" class="mt-1 text-sm text-red-700">
          {{ form.errors.color }}
        </p>
      </div>
    </div>

    <div>
      <label
        class="mb-1.5 block text-sm font-semibold text-slate-700"
        for="description"
      >
        Ciri-ciri barang
      </label>

      <textarea
        id="description"
        v-model="form.description"
        class="min-h-32 w-full resize-y rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 text-sm leading-6 transition outline-none placeholder:text-slate-400 focus:border-orange-400 focus:bg-white focus:ring-3 focus:ring-orange-100"
        placeholder="Tambahkan bentuk, merek, ukuran, atau ciri khas barang..."
        required
      ></textarea>

      <p v-if="form.errors.description" class="mt-1 text-sm text-red-700">
        {{ form.errors.description }}
      </p>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
      <div>
        <label
          class="mb-1.5 block text-sm font-semibold text-slate-700"
          for="location"
        >
          Lokasi kejadian
        </label>

        <input
          id="location"
          v-model="form.location"
          class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm transition outline-none placeholder:text-slate-400 focus:border-orange-400 focus:bg-white focus:ring-3 focus:ring-orange-100"
          maxlength="150"
          placeholder="Contoh: Gedung A lantai 2"
          required
        />

        <p v-if="form.errors.location" class="mt-1 text-sm text-red-700">
          {{ form.errors.location }}
        </p>
      </div>

      <div>
        <label
          class="mb-1.5 block text-sm font-semibold text-slate-700"
          for="event_date"
        >
          Tanggal kejadian
        </label>

        <input
          id="event_date"
          v-model="form.event_date"
          class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm transition outline-none focus:border-orange-400 focus:bg-white focus:ring-3 focus:ring-orange-100"
          type="date"
          required
        />

        <p v-if="form.errors.event_date" class="mt-1 text-sm text-red-700">
          {{ form.errors.event_date }}
        </p>
      </div>
    </div>

    <div
      class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4"
    >
      <label class="block text-sm font-semibold text-slate-700" for="image">
        Foto opsional
      </label>

      <input
        id="image"
        class="mt-2 block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-white file:px-3 file:py-2 file:text-sm file:font-medium file:text-slate-700 file:shadow-sm"
        type="file"
        accept="image/jpeg,image/png,image/webp"
        @change="selectImage"
      />

      <p class="mt-1 text-xs text-gray-500">
        JPG, PNG, atau WebP, maksimal 5 MB.
      </p>

      <p v-if="report?.image_url" class="mt-2 text-sm text-gray-600">
        Foto saat ini tersimpan. Pilih foto baru untuk menggantinya.
      </p>

      <p v-if="form.errors.image" class="mt-1 text-sm text-red-700">
        {{ form.errors.image }}
      </p>
    </div>

    <div class="flex justify-end border-t border-slate-100 pt-5">
      <button
        class="rounded-xl bg-orange-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-orange-700 disabled:opacity-50"
        :disabled="form.processing"
      >
        {{ form.processing ? 'Menyimpan...' : submitLabel }}
      </button>
    </div>
  </form>
</template>
