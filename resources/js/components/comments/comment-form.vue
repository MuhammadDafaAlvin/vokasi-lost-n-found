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
    <label class="block text-sm font-medium" for="comment-body">
      Tulis komentar
    </label>

    <textarea
      id="comment-body"
      v-model="form.body"
      class="min-h-28 w-full rounded-lg border px-3 py-2"
      maxlength="1000"
      name="body"
      required
    ></textarea>

    <div class="flex flex-wrap items-center justify-between gap-3">
      <p class="text-sm text-gray-500">{{ form.body.length }}/1000 karakter</p>

      <button
        class="rounded-lg bg-blue-700 px-4 py-2 font-medium text-white disabled:opacity-50"
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
