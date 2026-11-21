<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Report $report): RedirectResponse
    {
        $this->authorize('create', [Comment::class, $report]);

        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $comment = new Comment($request->validated());
        $comment->user()->associate($user);
        $report->comments()->save($comment);

        return redirect()
            ->route('report.show', $report)
            ->with('success', 'Komentar berhasil ditambahkan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report, Comment $comment): RedirectResponse
    {
        abort_unless($comment->report_id === $report->id, 404);
        $this->authorize('delete', $comment);

        $comment->delete();

        return redirect()
            ->route('report.show', $report)
            ->with('success', 'Komentar berhasil dihapus.');
    }
}
