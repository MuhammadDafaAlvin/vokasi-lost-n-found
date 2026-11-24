<script setup lang="ts">
  import { useForm } from '@inertiajs/vue3';
  import commentRoutes from '@/routes/comments';

  const props = defineProps<{ reportId: number }>();

  const form = useForm<{ body: string }>({
    body: '',
  });

  function submit(): void {
    form.post(commentRoutes.store(props.reportId).url, {
      preserveScroll: true,
      onSuccess: () => form.reset('body'),
    });
  }
</script>

<template>
  <form class="space-y-3" @submit.prevent="submit">
    <label class="block text-sm font-semibold text-slate-700" for="comment-body">
      Tulis komentar
    </label>

    <textarea
      id="comment-body"
      v-model="form.body"
      class="min-h-28 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 text-sm leading-6 outline-none transition placeholder:text-slate-400 focus:border-orange-400 focus:bg-white focus:ring-3 focus:ring-orange-100"
      maxlength="1000"
      name="body"
      placeholder="Tulis pertanyaan atau informasi tambahan..."
      required
    ></textarea>

    <div class="flex flex-wrap items-center justify-between gap-3">
      <p class="text-xs text-slate-400">{{ form.body.length }}/1000 karakter</p>

      <button
        class="rounded-xl bg-orange-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-orange-700 disabled:opacity-50"
        :disabled="form.processing"
        type="submit"
      >
        {{ form.processing ? 'Mengirim...' : 'Kirim komentar' }}
      </button>
    </div>

    <p v-if="form.errors.body" class="text-sm text-red-700" role="alert">
      {{ form.errors.body }}
    </p>
  </form>
</template>
