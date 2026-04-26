<?php

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Enums\PetStatus;
use App\Models\Application;
use App\Models\Pet;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardMetricsService
{
    public function buildDashboardData(int $monthCount = 6): array
    {
        $months = collect(range($monthCount - 1, 0))
            ->map(fn (int $offset) => now()->startOfMonth()->subMonths($offset));

        $monthKeys = $months->map(fn (Carbon $month) => $month->format('Y-m'))->values();
        $trendLabels = $months->map(fn (Carbon $month) => $month->format('M'))->values();
        $monthGroupExpr = $this->monthGroupExpression('submitted_at');
        $adoptionMonthGroupExpr = $this->monthGroupExpression('COALESCE(adopted_date, updated_at)');

        $applicationCountsByMonth = Application::query()
            ->whereNotNull('submitted_at')
            ->where('submitted_at', '>=', $months->first()->copy()->startOfMonth())
            ->selectRaw("{$monthGroupExpr} as ym, COUNT(*) as total")
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $adoptionCountsByMonth = Pet::query()
            ->where('adoption_status', PetStatus::Adopted->value)
            ->where(function ($query) use ($months) {
                $query->whereBetween('adopted_date', [
                    $months->first()->copy()->startOfMonth(),
                    $months->last()->copy()->endOfMonth(),
                ])->orWhere(function ($fallback) use ($months) {
                    $fallback->whereNull('adopted_date')
                        ->whereBetween('updated_at', [
                            $months->first()->copy()->startOfMonth(),
                            $months->last()->copy()->endOfMonth(),
                        ]);
                });
            })
            ->selectRaw("{$adoptionMonthGroupExpr} as ym, COUNT(*) as total")
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $monthlyApplications = $monthKeys
            ->map(fn (string $key) => (int) ($applicationCountsByMonth[$key] ?? 0))
            ->values();

        $monthlyAdoptions = $monthKeys
            ->map(fn (string $key) => (int) ($adoptionCountsByMonth[$key] ?? 0))
            ->values();

        $petCounts = Pet::query()
            ->selectRaw('adoption_status, COUNT(*) as total')
            ->groupBy('adoption_status')
            ->pluck('total', 'adoption_status');

        $stats = [
            'total_pets' => (int) $petCounts->sum(),
            'available' => (int) ($petCounts[PetStatus::Available->value] ?? 0),
            'pending' => (int) ($petCounts[PetStatus::Pending->value] ?? 0),
            'adopted' => (int) ($petCounts[PetStatus::Adopted->value] ?? 0),
            'applications_pending_review' => Application::whereIn('status', [
                ApplicationStatus::Submitted->value,
                ApplicationStatus::UnderReview->value,
            ])->count(),
            'applications_total' => Application::count(),
        ];

        return [
            'stats' => $stats,
            'recentPets' => Pet::latest('created_at')->limit(5)->get(),
            'chartData' => [
                'trend_labels' => $trendLabels,
                'monthly_applications' => $monthlyApplications,
                'monthly_adoptions' => $monthlyAdoptions,
            ],
        ];
    }

    private function monthGroupExpression(string $column): string
    {
        return match (DB::connection()->getDriverName()) {
            'sqlite' => "strftime('%Y-%m', {$column})",
            'pgsql' => "to_char({$column}, 'YYYY-MM')",
            default => "DATE_FORMAT({$column}, '%Y-%m')",
        };
    }
}
