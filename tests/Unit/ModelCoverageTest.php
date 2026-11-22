<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ModelCoverageTest extends TestCase
{
    public function test_report_image_url_uses_public_storage_path(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('reports/coverage.jpg', 'image-content');

        $report = Report::factory()->create([
            'image_path' => 'reports/coverage.jpg',
        ]);

        $this->assertSame(
            Storage::disk('public')->url('reports/coverage.jpg'),
            $report->image_url,
        );
    }

    public function test_user_comments_relationship_returns_their_comments(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->for($user)->create();

        $this->assertCount(1, $user->comments);
        $this->assertSame($comment->id, $user->comments()->firstOrFail()->id);
    }
}
