<?php

namespace App\Policies;

use App\Enums\Status;
use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Report $report): bool
    {
        return ! $report->is_hidden || $user->id === $report->user_id || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Report $report): bool
    {
        return $user->id === $report->user_id;
    }

    public function resolve(User $user, Report $report): bool
    {
        return $user->id === $report->user_id && $report->status === Status::ACTIVE->value;
    }

    public function moderate(User $user, Report $report): bool
    {
        return $user->isAdmin();
    }
}
