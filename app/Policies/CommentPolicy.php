<?php

namespace App\Policies;

use App\Enums\Status;
use App\Models\Comment;
use App\Models\Report;
use App\Models\User;

class CommentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user, Report $report): bool
    {
        return ! $report->is_hidden && $report->status === Status::ACTIVE->value;
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->isAdmin() || $user->id === $comment->user_id;
    }
}
