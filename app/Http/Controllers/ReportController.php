<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Report;
use App\Models\User;
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
    public function index(): Response
    {
        $this->authorize('viewAny', Report::class);

        return Inertia::render('report/index', [
            'reports' => Report::query()
                ->where('is_hidden', false)
                ->with('user:id,name')
                ->orderByDesc('event_date')
                ->orderByDesc('id')
                ->paginate(12),
        ]);
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

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $this->authorize('create', Report::class);

        return Inertia::render('report/create', [
            'categories' => config('report.categories', []),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(Report $report): Response
    {
        $this->authorize('view', $report);
        $report->load('user:id,name');
        $user = $this->authenticatedUser(request());

        return Inertia::render('report/show', [
            'report' => $report,
            'canEdit' => $user->can('update', $report),
            'canResolve' => $user->can('resolve', $report),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report): Response
    {
        $this->authorize('update', $report);

        return Inertia::render('report/edit', [
            'report' => $report,
            'categories' => config('report.categories', []),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportRequest $request, Report $report): RedirectResponse
    {
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): void
    {
        //
    }
}
