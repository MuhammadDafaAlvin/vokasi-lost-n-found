<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Comment;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use RuntimeException;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Report::class);

        $filters = $this->normalizeFilters($request);
        $reports = Report::query()
            ->where('is_hidden', false)
            ->when($filters['q'] !== '', function (Builder $query) use ($filters): void {
                $search = '%'.$filters['q'].'%';

                $query->where(function (Builder $searchQuery) use ($search): void {
                    $searchQuery
                        ->where('title', 'like', $search)
                        ->orWhere('description', 'like', $search);
                });
            })
            ->when($filters['type'] !== '', function (Builder $query) use ($filters): void {
                $query->where('type', $filters['type']);
            })
            ->when($filters['category'] !== '', function (Builder $query) use ($filters): void {
                $query->where('category', $filters['category']);
            })
            ->with('user:id,name')
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        return Inertia::render('report/index', [
            'reports' => $reports
                ->paginate(12)
                ->appends(array_filter(
                    $filters,
                    static fn (string $value): bool => $value !== '',
                )),
            'filters' => $filters,
            'categories' => config('report.categories', []),
        ]);
    }

    /**
     * @return array{q: string, type: string, category: string}
     */
    private function normalizeFilters(Request $request): array
    {
        $rawQuery = $request->query('q');
        $rawType = $request->query('type');
        $rawCategory = $request->query('category');
        $categories = (array) config('report.categories', []);

        return [
            'q' => is_string($rawQuery)
                ? mb_substr(trim($rawQuery), 0, 100)
                : '',
            'type' => is_string($rawType)
                && in_array($rawType, ['lost', 'found'], true)
                    ? $rawType
                    : '',
            'category' => is_string($rawCategory)
                && in_array($rawCategory, $categories, true)
                    ? $rawCategory
                    : '',
        ];
    }

    public function mine(Request $request): Response
    {
        $user = $this->authenticatedUser($request);

        return Inertia::render('report/mine', [
            'reports' => Report::query()
                ->where('user_id', $user->id)
                ->with('user:id,name')
                ->orderByDesc('created_at')
                ->paginate(12),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Report::class);

        return Inertia::render('report/create', [
            'categories' => config('report.categories', []),
        ]);
    }

    public function store(StoreReportRequest $request): RedirectResponse
    {
        $this->authorize('create', Report::class);

        $validated = $request->validated();
        $image = $validated['image'] ?? null;
        unset($validated['image']);

        $report = new Report($validated);
        $this->authenticatedUser($request)->reports()->save($report);

        if ($image instanceof UploadedFile) {
            $report->image_path = $this->storeImage($image);
            $report->save();
        }

        return redirect()
            ->route('report.show', $report)
            ->with('success', 'Laporan berhasil dibuat.');
    }

    public function show(Report $report): Response
    {
        $this->authorize('view', $report);

        $report->load([
            'user:id,name',
            'comments' => static function (HasMany $comments): void {
                $comments->with('user:id,name');
                $comments->orderBy('created_at')->orderBy('id');
            },
        ]);

        $user = $this->authenticatedUser(request());

        $comments = $report->comments->map(
            static fn (Comment $comment): array => [
                'id' => $comment->id,
                'report_id' => $comment->report_id,
                'user_id' => $comment->user_id,
                'body' => $comment->body,
                'created_at' => $comment->created_at?->toIso8601String() ?? '',
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                ],
                'canDelete' => $user->can('delete', $comment),
            ],
        )->values();

        return Inertia::render('report/show', [
            'report' => $report,
            'canEdit' => $user->can('update', $report),
            'canResolve' => $user->can('resolve', $report),
            'canComment' => $user->can('create', [Comment::class, $report]),
            'comments' => $comments,
        ]);
    }

    public function edit(Report $report): Response
    {
        $this->authorize('update', $report);

        return Inertia::render('report/edit', [
            'report' => $report,
            'categories' => config('report.categories', []),
        ]);
    }

    public function update(UpdateReportRequest $request, Report $report,
    ): RedirectResponse {
        $this->authorize('update', $report);

        $validated = $request->validated();
        $image = $validated['image'] ?? null;
        unset($validated['image']);

        $oldImagePath = $report->image_path;
        $newImagePath = null;

        if ($image instanceof UploadedFile) {
            $newImagePath = $this->storeImage($image);
            $report->image_path = $newImagePath;
        }

        $report->fill($validated);
        $report->save();

        if ($newImagePath !== null && $oldImagePath !== null) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return redirect()
            ->route('report.show', $report)
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function updateStatus(Report $report): RedirectResponse
    {
        $this->authorize('resolve', $report);

        $report->status = Status::RESOLVED->value;
        $report->save();

        return back()->with('success', 'Laporan ditandai selesai.');
    }

    private function authenticatedUser(Request $request): User
    {
        $user = $request->user();

        abort_unless($user instanceof User, 403);

        return $user;
    }

    private function storeImage(UploadedFile $image): string
    {
        $path = $image->store('reports', 'public');

        if (! is_string($path)) {
            throw new RuntimeException('Gagal menyimpan foto laporan.');
        }

        return $path;
    }
}
