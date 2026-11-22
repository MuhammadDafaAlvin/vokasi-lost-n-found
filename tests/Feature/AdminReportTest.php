<?php

namespace Tests\Feature;

use App\Enums\Status;
use App\Models\Comment;
use App\Models\Report;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminReportTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_guest_and_member_cannot_access_admin_panel_or_moderate(): void
    {
        $report = Report::factory()->create();

        $this->get(route('admin.report.index'))
            ->assertRedirect(route('login.create'));

        $this->actingAsMember();
        $this->get(route('admin.report.index'))->assertForbidden();

        $this->patch(route('admin.report.visibility', $report), [
            'is_hidden' => true,
        ])->assertForbidden();

        $this->assertFalse($report->fresh()->is_hidden);
    }

    public function test_admin_can_view_all_reports_and_summary_stats(): void
    {
        $this->actingAsAdmin();
        Report::factory()->count(2)->create();
        Report::factory()->resolved()->create();
        Report::factory()->hidden()->create();

        $this->get(route('admin.report.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('admin/report/index')
                ->has('reports.data', 4)
                ->where('stats.total', 4)
                ->where('stats.active', 3)
                ->where('stats.resolved', 1)
                ->where('stats.hidden', 1)
                ->has('filters'));
    }

    public function test_admin_can_hide_and_restore_report(): void
    {
        $this->actingAsAdmin();
        $report = Report::factory()->create();

        $this->patch(route('admin.report.visibility', $report), [
            'is_hidden' => true,
        ])->assertRedirect();
        $this->assertTrue($report->fresh()->is_hidden);

        $this->patch(route('admin.report.visibility', $report), [
            'is_hidden' => false,
        ])->assertRedirect();
        $this->assertFalse($report->fresh()->is_hidden);
    }

    public function test_admin_visibility_endpoint_validates_boolean_value(): void
    {
        $this->actingAsAdmin();
        $report = Report::factory()->create();

        $this->patch(route('admin.report.visibility', $report), [
            'is_hidden' => 'not-a-boolean',
        ])->assertSessionHasErrors('is_hidden');
    }

    public function test_hidden_report_is_not_in_public_list_but_admin_can_see_it(): void
    {
        $report = Report::factory()->hidden()->create();

        $this->actingAsMember();
        $this->get(route('report.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('report/index')
                ->has('reports.data', 0));

        $this->actingAsAdmin();
        $this->get(route('admin.report.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('admin/report/index')
                ->where('reports.data.0.id', $report->id));
    }

    public function test_admin_filters_reports_by_title_type_status_and_visibility(): void
    {
        $this->actingAsAdmin();
        $matching = Report::factory()->hidden()->resolved()->create([
            'title' => 'Laptop moderasi',
            'type' => 'found',
        ]);
        Report::factory()->create(['title' => 'Dompet aktif']);

        $this->get(route('admin.report.index', [
            'q' => 'Laptop',
            'type' => 'found',
            'status' => Status::RESOLVED->value,
            'visibility' => 'hidden',
        ]))->assertInertia(fn (Assert $page) => $page
            ->component('admin/report/index')
            ->where('reports.data.0.id', $matching->id)
            ->where('filters.q', 'Laptop')
            ->where('filters.type', 'found')
            ->where('filters.status', 'resolved')
            ->where('filters.visibility', 'hidden'));
    }

    public function test_admin_can_delete_comment_through_comment_policy(): void
    {
        $report = Report::factory()->create();
        $comment = Comment::factory()->for($report)->create();

        $this->actingAsAdmin();
        $this->delete(route('comments.destroy', [$report, $comment]))
            ->assertRedirect(route('report.show', $report));

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
