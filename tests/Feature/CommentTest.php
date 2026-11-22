<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Report;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CommentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_authenticated_member_can_comment_and_comment_is_rendered(): void
    {
        $author = $this->createMember();
        $this->actingAsMember();
        $report = Report::factory()->for($author)->create();

        $this->post(route('comments.store', $report), [
            'body' => 'Saya menemukan barang serupa.',
        ])->assertRedirect(route('report.show', $report));

        $comment = Comment::query()->firstOrFail();
        $this->assertSame($this->user->id, $comment->user_id);
        $this->assertSame($report->id, $comment->report_id);

        $this->get(route('report.show', $report))
            ->assertInertia(fn (Assert $page) => $page
                ->component('report/show')
                ->where('comments.0.body', 'Saya menemukan barang serupa.')
                ->where('comments.0.user.name', $this->user->name));
    }

    public function test_guest_cannot_create_comment(): void
    {
        $report = Report::factory()->create();

        $this->post(route('comments.store', $report), [
            'body' => 'Komentar guest',
        ])->assertRedirect(route('login.create'));

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_empty_and_oversized_comment_are_rejected(): void
    {
        $this->actingAsMember();
        $report = Report::factory()->create();

        $this->post(route('comments.store', $report), ['body' => ''])
            ->assertSessionHasErrors('body');

        $this->post(route('comments.store', $report), [
            'body' => str_repeat('a', 1001),
        ])->assertSessionHasErrors('body');

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_comment_is_rejected_for_resolved_or_hidden_report(): void
    {
        $this->actingAsMember();
        $resolvedReport = Report::factory()->resolved()->create();
        $hiddenReport = Report::factory()->hidden()->create();

        $this->post(route('comments.store', $resolvedReport), [
            'body' => 'Komentar pada laporan selesai',
        ])->assertForbidden();

        $this->post(route('comments.store', $hiddenReport), [
            'body' => 'Komentar pada laporan tersembunyi',
        ])->assertForbidden();

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_comment_owner_can_delete_and_other_member_cannot(): void
    {
        $report = Report::factory()->create();
        $owner = $this->createMember();
        $comment = Comment::factory()->for($report)->for($owner)->create();
        $other = $this->createMember();

        $this->actingAs($other)
            ->delete(route('comments.destroy', [$report, $comment]))
            ->assertForbidden();
        $this->assertDatabaseHas('comments', ['id' => $comment->id]);

        $this->actingAs($owner)
            ->delete(route('comments.destroy', [$report, $comment]))
            ->assertRedirect(route('report.show', $report));
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_admin_can_delete_comment_and_route_checks_report_parent(): void
    {
        $report = Report::factory()->create();
        $otherReport = Report::factory()->create();
        $comment = Comment::factory()->for($report)->create();
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->delete(route('comments.destroy', [$otherReport, $comment]))
            ->assertNotFound();

        $this->delete(route('comments.destroy', [$report, $comment]))
            ->assertRedirect(route('report.show', $report));

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
