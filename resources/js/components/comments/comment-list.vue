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
  <div v-if="comments.length" class="divide-y divide-slate-100">
    <article
      v-for="comment in comments"
      :key="comment.id"
      class="py-5 first:pt-0 last:pb-0"
    >
      <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
          <p class="font-medium text-slate-900">
            {{ comment.user.name }}
          </p>

          <time class="text-xs text-slate-400" :datetime="comment.created_at">
            {{ formatDate(comment.created_at) }}
          </time>
        </div>

        <button
          v-if="comment.canDelete"
          class="text-sm font-medium text-red-600 hover:text-red-700 disabled:opacity-50"
          :disabled="deleteForm.processing"
          type="button"
          @click="deleteComment(comment.id)"
        >
          {{ deleteForm.processing ? 'Menghapus...' : 'Hapus' }}
        </button>
      </div>

      <p class="mt-3 leading-7 whitespace-pre-line text-slate-600">
        {{ comment.body }}
      </p>
    </article>
  </div>

  <p
    v-else
    class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-5 text-center text-sm text-slate-500"
  >
    Belum ada komentar. Jadilah yang pertama berkomentar.
  </p>
</template>
