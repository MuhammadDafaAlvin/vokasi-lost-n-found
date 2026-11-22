<?php

namespace Tests\Feature;

use App\Models\Report;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ReportControllerCoverageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_member_can_open_report_creation_form(): void
    {
        $this->actingAsMember();

        $this->get(route('report.create'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('report/create')
                ->has('categories'));
    }

    public function test_owner_can_open_report_edit_form(): void
    {
        $owner = $this->createMember();
        $report = Report::factory()->for($owner)->create();

        $this->actingAs($owner)
            ->get(route('report.edit', $report))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('report/edit')
                ->where('report.id', $report->id));
    }

    public function test_owner_can_replace_report_image(): void
    {
        Storage::fake('public');
        $owner = $this->createMember();
        $report = Report::factory()->for($owner)->create([
            'image_path' => 'reports/old-image.jpg',
        ]);
        Storage::disk('public')->put('reports/old-image.jpg', 'old-image');

        $this->actingAs($owner)
            ->put(route('report.update', $report), [
                'image' => UploadedFile::fake()->image('new-image.jpg'),
            ])
            ->assertRedirect(route('report.show', $report));

        Storage::disk('public')->assertMissing('reports/old-image.jpg');
        Storage::disk('public')->assertExists($report->fresh()->image_path);
    }

    public function test_resolved_report_cannot_be_resolved_again(): void
    {
        $owner = $this->createMember();
        $report = Report::factory()->for($owner)->resolved()->create();

        $this->actingAs($owner)
            ->patch(route('report.status.update', $report))
            ->assertForbidden();
    }
}
