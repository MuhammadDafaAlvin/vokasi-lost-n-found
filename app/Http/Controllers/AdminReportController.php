<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $filters = $this->normalizeFilters($request);

        $reports = Report::query();

        if ($filters['q'] !== '') {
            $search = "%{$filters['q']}%";

            $reports->where('title', 'like', $search);
        }

        if ($filters['type'] !== '') {
            $reports->where('type', $filters['type']);
        }

        if ($filters['status'] !== '') {
            $reports->where('status', $filters['status']);
        }

        if ($filters['visibility'] !== '') {
            $reports->where(
                'is_hidden',
                $filters['visibility'] === 'hidden',
            );
        }

        $reports
            ->with('user:id,name')
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        $stats = [
            'total' => Report::query()->count(),
            'active' => Report::query()->where('status', Status::ACTIVE->value)->count(),
            'resolved' => Report::query()->where('status', Status::RESOLVED->value)->count(),
            'hidden' => Report::query()->where('is_hidden', true)->count(),
        ];

        return Inertia::render('admin/report/index', [
            'reports' => $reports
                ->paginate(12)
                ->appends(array_filter(
                    $filters,
                    static fn (string $value): bool => $value !== '',
                )),
            'filters' => $filters,
            'stats' => $stats,
        ]);
    }

    public function updateVisibility(Request $request, Report $report): RedirectResponse
    {
        $this->authorize('moderate', $report);

        $validated = $request->validate([
            'is_hidden' => ['required', 'boolean'],
        ]);

        $report->is_hidden = (bool) $validated['is_hidden'];
        $report->save();

        $message = $report->is_hidden
            ? 'Laporan berhasil disembunyikan.'
            : 'Laporan berhasil ditampilkan kembali.';

        return back()->with('success', $message);
    }

    /**
     * @return array{q: string, type: string, status: string, visibility: string}
     */
    private function normalizeFilters(Request $request): array
    {
        $rawQuery = $request->query('q');
        $rawType = $request->query('type');
        $rawStatus = $request->query('status');
        $rawVisibility = $request->query('visibility');

        return [
            'q' => is_string($rawQuery)
                ? mb_substr(trim($rawQuery), 0, 100)
                : '',
            'type' => is_string($rawType)
                && in_array($rawType, ['lost', 'found'], true)
                    ? $rawType
                    : '',
            'status' => is_string($rawStatus)
                && in_array($rawStatus, ['active', 'resolved'], true)
                    ? $rawStatus
                    : '',
            'visibility' => is_string($rawVisibility)
                && in_array($rawVisibility, ['visible', 'hidden'], true)
                    ? $rawVisibility
                    : '',
        ];
    }
}
