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
  <form class="space-y-5" @submit.prevent="submit">
    <div>
      <label class="block text-sm font-medium" for="type">
        Jenis laporan
      </label>

      <select
        id="type"
        v-model="form.type"
        class="mt-1 w-full rounded-lg border px-3 py-2"
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
      <label class="block text-sm font-medium" for="title"> Nama barang </label>

      <input
        id="title"
        v-model="form.title"
        class="mt-1 w-full rounded-lg border px-3 py-2"
        maxlength="150"
        required
      />

      <p v-if="form.errors.title" class="mt-1 text-sm text-red-700">
        {{ form.errors.title }}
      </p>
    </div>

    <div>
      <label class="block text-sm font-medium" for="category"> Kategori </label>

      <select
        id="category"
        v-model="form.category"
        class="mt-1 w-full rounded-lg border px-3 py-2"
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
      <label class="block text-sm font-medium" for="description">
        Ciri-ciri barang
      </label>

      <textarea
        id="description"
        v-model="form.description"
        class="mt-1 min-h-32 w-full rounded-lg border px-3 py-2"
        required
      ></textarea>

      <p v-if="form.errors.description" class="mt-1 text-sm text-red-700">
        {{ form.errors.description }}
      </p>
    </div>

    <div>
      <label class="block text-sm font-medium" for="color"> Warna </label>

      <input
        id="color"
        v-model="form.color"
        class="mt-1 w-full rounded-lg border px-3 py-2"
        maxlength="50"
      />

      <p v-if="form.errors.color" class="mt-1 text-sm text-red-700">
        {{ form.errors.color }}
      </p>
    </div>

    <div>
      <label class="block text-sm font-medium" for="location">
        Lokasi kejadian
      </label>

      <input
        id="location"
        v-model="form.location"
        class="mt-1 w-full rounded-lg border px-3 py-2"
        maxlength="150"
        required
      />

      <p v-if="form.errors.location" class="mt-1 text-sm text-red-700">
        {{ form.errors.location }}
      </p>
    </div>

    <div>
      <label class="block text-sm font-medium" for="event_date">
        Tanggal kejadian
      </label>

      <input
        id="event_date"
        v-model="form.event_date"
        class="mt-1 w-full rounded-lg border px-3 py-2"
        type="date"
        required
      />

      <p v-if="form.errors.event_date" class="mt-1 text-sm text-red-700">
        {{ form.errors.event_date }}
      </p>
    </div>

    <div>
      <label class="block text-sm font-medium" for="image">
        Foto opsional
      </label>

      <input
        id="image"
        class="mt-1 block w-full text-sm"
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

    <button
      class="rounded-lg bg-orange-500 px-5 py-2.5 font-medium text-white disabled:opacity-50"
      :disabled="form.processing"
    >
      {{ form.processing ? 'Menyimpan...' : submitLabel }}
    </button>
  </form>
</template>
