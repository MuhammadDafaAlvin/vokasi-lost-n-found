<?php

namespace Tests\Feature;

use App\Enums\Status;
use App\Enums\Type;
use App\Models\Report;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ReportTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    /** @return array<string, string> */
    private function reportData(string $type = 'lost'): array
    {
        return [
            'type' => $type,
            'title' => 'Dompet hitam',
            'category' => 'Aksesori',
            'description' => 'Dompet kecil dengan gantungan kunci.',
            'color' => 'Hitam',
            'location' => 'Gedung Fakultas Vokasi',
            'event_date' => '2026-11-20',
        ];
    }

    public function test_member_can_create_lost_and_found_reports(): void
    {
        $this->actingAsMember();

        foreach ([Type::LOST->value, Type::FOUND->value] as $type) {
            $data = $this->reportData($type);
            $data['title'] = $type.' item';

            $response = $this->post(route('report.store'), $data);
            $report = Report::query()->where('title', $data['title'])->firstOrFail();

            $response->assertRedirect(route('report.show', $report));
            $this->assertSame($this->user->id, $report->user_id);
            $this->assertSame(Status::ACTIVE->value, $report->status);
            $this->assertFalse($report->is_hidden);
        }
    }

    public function test_creation_ignores_spoofed_owner_and_moderation_fields(): void
    {
        $this->actingAsMember();
        $otherUser = $this->createMember();

        $this->post(route('report.store'), [
            ...$this->reportData(),
            'user_id' => $otherUser->id,
            'status' => Status::RESOLVED->value,
            'is_hidden' => true,
        ])->assertRedirect();

        $report = Report::query()->firstOrFail();
        $this->assertSame($this->user->id, $report->user_id);
        $this->assertSame(Status::ACTIVE->value, $report->status);
        $this->assertFalse($report->is_hidden);
    }

    public function test_report_creation_validates_required_fields_type_and_category(): void
    {
        $this->actingAsMember();

        $this->post(route('report.store'), [])
            ->assertSessionHasErrors([
                'type',
                'title',
                'category',
                'description',
                'location',
                'event_date',
            ]);

        $this->post(route('report.store'), [
            ...$this->reportData('stolen'),
            'category' => 'Tidak dikenal',
        ])->assertSessionHasErrors(['type', 'category']);

        $this->assertDatabaseCount('reports', 0);
    }

    public function test_valid_image_is_stored_and_invalid_image_is_rejected(): void
    {
        Storage::fake('public');
        $this->actingAsMember();

        $this->post(route('report.store'), [
            ...$this->reportData(),
            'image' => UploadedFile::fake()->image('dompet.jpg'),
        ])->assertRedirect();

        $report = Report::query()->firstOrFail();
        $this->assertNotNull($report->image_path);
        Storage::disk('public')->assertExists($report->image_path);

        $this->post(route('report.store'), [
            ...$this->reportData(),
            'title' => 'Invalid upload',
            'image' => UploadedFile::fake()->create('document.txt', 20, 'text/plain'),
        ])->assertSessionHasErrors('image');
    }

    public function test_member_can_view_index_and_report_detail(): void
    {
        $this->actingAsMember();
        $report = Report::factory()->for($this->user)->create();

        $this->get(route('report.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('report/index')
                ->has('reports.data', 1)
                ->has('filters')
                ->has('categories'));

        $this->get(route('report.show', $report))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('report/show')
                ->where('report.id', $report->id)
                ->has('comments'));
    }

    public function test_guest_is_redirected_from_report_pages(): void
    {
        $report = Report::factory()->create();

        $this->get(route('report.index'))
            ->assertRedirect(route('login.create'));
        $this->get(route('report.show', $report))
            ->assertRedirect(route('login.create'));
        $this->get(route('report.create'))
            ->assertRedirect(route('login.create'));
    }

    public function test_only_owner_can_edit_report_and_an_owner_can_update_it(): void
    {
        $owner = $this->createMember();
        $other = $this->createMember();
        $report = Report::factory()->for($owner)->create();

        $this->actingAs($other)
            ->get(route('report.edit', $report))
            ->assertForbidden();

        $this->actingAs($owner)
            ->put(route('report.update', $report), [
                ...$this->reportData(Type::FOUND->value),
                'title' => 'Tas ditemukan',
            ])
            ->assertRedirect(route('report.show', $report));

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'user_id' => $owner->id,
            'title' => 'Tas ditemukan',
            'type' => Type::FOUND->value,
        ]);
    }

    public function test_user_cannot_update_another_users_report(): void
    {
        $owner = $this->createMember();
        $other = $this->createMember();
        $report = Report::factory()->for($owner)->create();

        $this->actingAs($other)
            ->put(route('report.update', $report), $this->reportData())
            ->assertForbidden();

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'user_id' => $owner->id,
        ]);
    }

    public function test_owner_can_resolve_report_but_other_user_cannot(): void
    {
        $owner = $this->createMember();
        $other = $this->createMember();
        $report = Report::factory()->for($owner)->create();

        $this->actingAs($other)
            ->patch(route('report.status.update', $report))
            ->assertForbidden();

        $this->actingAs($owner)
            ->patch(route('report.status.update', $report))
            ->assertRedirect();

        $this->assertSame(Status::RESOLVED->value, $report->fresh()->status);
    }

    public function test_private_reports_are_hidden_from_public_list_and_other_users(): void
    {
        $owner = $this->createMember();
        $viewer = $this->createMember();
        $hiddenReport = Report::factory()->for($owner)->hidden()->create();

        $this->actingAs($viewer)
            ->get(route('report.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('report/index')
                ->has('reports.data', 0));

        $this->get(route('report.show', $hiddenReport))->assertForbidden();

        $this->actingAs($owner)
            ->get(route('report.show', $hiddenReport))
            ->assertOk();
    }

    public function test_search_and_filters_find_matching_reports(): void
    {
        $this->actingAsMember();
        $matching = Report::factory()->for($this->user)->create([
            'title' => 'Kunci motor hilang',
            'description' => 'Tertinggal dekat ruang laboratorium.',
            'type' => Type::LOST->value,
            'category' => 'Aksesori',
        ]);
        Report::factory()->create([
            'title' => 'Botol ditemukan',
            'type' => Type::FOUND->value,
            'category' => 'Lainnya',
        ]);

        $this->get(route('report.index', [
            'q' => 'laboratorium',
            'type' => 'lost',
            'category' => 'Aksesori',
        ]))->assertInertia(fn (Assert $page) => $page
            ->component('report/index')
            ->where('reports.data.0.id', $matching->id)
            ->where('filters.q', 'laboratorium')
            ->where('filters.type', 'lost')
            ->where('filters.category', 'Aksesori'));
    }

    public function test_invalid_search_filters_are_normalized_safely(): void
    {
        $this->actingAsMember();

        $this->get(route('report.index', [
            'q' => ['invalid'],
            'type' => 'title desc',
            'category' => 'invalid',
        ]))->assertOk()->assertInertia(fn (Assert $page) => $page
            ->where('filters.q', '')
            ->where('filters.type', '')
            ->where('filters.category', ''));
    }

    public function test_pagination_returns_twelve_rows_and_keeps_filters(): void
    {
        $this->actingAsMember();
        Report::factory()->count(13)->for($this->user)->create([
            'type' => Type::LOST->value,
            'category' => 'Aksesori',
        ]);

        $this->get(route('report.index', [
            'q' => 'dompet',
            'type' => 'lost',
            'category' => 'Aksesori',
            'page' => 2,
        ]))->assertInertia(fn (Assert $page) => $page
            ->component('report/index')
            ->where('reports.current_page', 2)
            ->where('reports.per_page', 12)
            ->where('filters.q', 'dompet')
            ->where('filters.type', 'lost')
            ->where('filters.category', 'Aksesori'));
    }

    public function test_member_can_view_only_their_own_report_list(): void
    {
        $this->actingAsMember();
        Report::factory()->count(2)->for($this->user)->create();
        Report::factory()->create();

        $this->get(route('report.mine'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('report/mine')
                ->has('reports.data', 2));
    }
}
