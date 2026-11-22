<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Report;
use App\Models\User;
use App\Policies\CommentPolicy;
use Tests\TestCase;

class CommentPolicyTest extends TestCase
{
    public function test_comment_creation_requires_visible_active_report(): void
    {
        $user = User::factory()->create();
        $policy = new CommentPolicy;
        $activeReport = Report::factory()->create();
        $hiddenReport = Report::factory()->hidden()->create();
        $resolvedReport = Report::factory()->resolved()->create();

        $this->assertTrue($policy->create($user, $activeReport));
        $this->assertFalse($policy->create($user, $hiddenReport));
        $this->assertFalse($policy->create($user, $resolvedReport));
    }

    public function test_only_comment_owner_or_admin_can_delete_comment(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $admin = $this->createAdmin();
        $comment = Comment::factory()->for($owner)->create();
        $policy = new CommentPolicy;

        $this->assertTrue($policy->delete($owner, $comment));
        $this->assertFalse($policy->delete($other, $comment));
        $this->assertTrue($policy->delete($admin, $comment));
    }
}
