<script setup lang="ts">
  import { useForm } from '@inertiajs/vue3';
  import commentRoutes from '@/routes/comments';
  import type { ReportComment } from '@/types/comment';

  const props = defineProps<{
    comments: ReportComment[];
    reportId: number;
  }>();

  const deleteForm = useForm({});

  const dateFormatter = new Intl.DateTimeFormat('id-ID', {
    dateStyle: 'medium',
    timeStyle: 'short',
  });

  function formatDate(createdAt: string): string {
    return dateFormatter.format(new Date(createdAt));
  }

  function deleteComment(commentId: number): void {
    deleteForm.delete(
      commentRoutes.destroy({
        report: props.reportId,
        comment: commentId,
      }).url,
      {
        preserveScroll: true,
      },
    );
  }
</script>

<template>
  <div v-if="comments.length" class="divide-y">
    <article
      v-for="comment in comments"
      :key="comment.id"
      class="py-5 first:pt-0 last:pb-0"
    >
      <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
          <p class="font-medium text-gray-900">
            {{ comment.user.name }}
          </p>

          <time class="text-sm text-gray-500" :datetime="comment.created_at">
            {{ formatDate(comment.created_at) }}
          </time>
        </div>

        <button
          v-if="comment.canDelete"
          class="text-sm text-red-700 underline disabled:opacity-50"
          :disabled="deleteForm.processing"
          type="button"
          @click="deleteComment(comment.id)"
        >
          {{ deleteForm.processing ? 'Menghapus...' : 'Hapus' }}
        </button>
      </div>

      <p class="mt-3 whitespace-pre-line text-gray-700">
        {{ comment.body }}
      </p>
    </article>
  </div>

  <p v-else class="rounded-lg bg-gray-50 p-4 text-sm text-gray-600">
    Belum ada komentar. Jadilah yang pertama berkomentar.
  </p>
</template>
