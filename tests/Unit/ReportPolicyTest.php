<?php

namespace Tests\Unit;

use App\Enums\Status;
use App\Models\Report;
use App\Models\User;
use App\Policies\ReportPolicy;
use Tests\TestCase;

class ReportPolicyTest extends TestCase
{
    public function test_only_owner_can_update_report_even_if_viewer_is_admin(): void
    {
        $owner = User::factory()->create();
        $admin = $this->createAdmin();
        $report = Report::factory()->for($owner)->create();
        $policy = new ReportPolicy;

        $this->assertTrue($policy->update($owner, $report));
        $this->assertFalse($policy->update($admin, $report));
    }

    public function test_only_owner_can_resolve_active_report(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $report = Report::factory()->for($owner)->create();
        $policy = new ReportPolicy;

        $this->assertTrue($policy->resolve($owner, $report));
        $this->assertFalse($policy->resolve($other, $report));

        $report->status = Status::RESOLVED->value;
        $this->assertFalse($policy->resolve($owner, $report));
    }

    public function test_hidden_report_is_visible_only_to_owner_or_admin(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $admin = $this->createAdmin();
        $report = Report::factory()->for($owner)->hidden()->create();
        $policy = new ReportPolicy;

        $this->assertTrue($policy->view($owner, $report));
        $this->assertFalse($policy->view($other, $report));
        $this->assertTrue($policy->view($admin, $report));
    }
}
