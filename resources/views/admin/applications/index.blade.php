@extends('app')

@section('title', 'Application Review Queue — Admin | PawfectMatch')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/modules.css') }}">
<style>
    .queue-wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 0 3rem;
    }

    .queue-filters {
        display: flex;
        gap: 0.6rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .queue-pill {
        padding: 0.45rem 0.8rem;
        border-radius: 999px;
        border: 1px solid rgba(148, 163, 184, 0.35);
        text-decoration: none;
        color: #e2e8f0;
        background: rgba(15, 23, 42, 0.5);
        font-size: 0.84rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 200ms ease;
    }

    .queue-pill:hover {
        border-color: rgba(59, 130, 246, 0.5);
        background: rgba(59, 130, 246, 0.1);
        color: #bfdbfe;
    }

    .queue-pill.active {
        border-color: rgba(59, 130, 246, 0.65);
        background: rgba(59, 130, 246, 0.2);
        color: #bfdbfe;
    }

    .queue-table {
        width: 100%;
        border-collapse: collapse;
    }

    .queue-table th,
    .queue-table td {
        padding: 0.9rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.14);
        color: #e2e8f0;
        text-align: left;
        font-size: 0.92rem;
    }

    .queue-table th {
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.72rem;
    }

    .status-badge {
        font-size: 0.85rem;
        font-weight: 700;
    }

    .status-badge.Submitted { color: #3b82f6; }
    .status-badge.Under.Review { color: #f59e0b; }
    .status-badge.Approved { color: #10b981; }
    .status-badge.Rejected,
    .status-badge.Withdrawn { color: #ef4444; }

    .row-link {
        display: inline-block;
        padding: 0.45rem 0.8rem;
        background: rgba(59, 130, 246, 0.1);
        color: #60a5fa;
        border: 1px solid rgba(59, 130, 246, 0.3);
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.82rem;
        transition: all 200ms ease;
    }

    .row-link:hover {
        background: rgba(59, 130, 246, 0.15);
        color: #93c5fd;
        border-color: rgba(59, 130, 246, 0.5);
    }

    /* Pagination Styling */
    .queue-pagination nav {
        display: flex;
        justify-content: center;
    }

    .queue-pagination nav > div:first-child {
        display: none; /* Hide "Showing X to Y of Z results" on small screens */
    }

    .queue-pagination nav > div:last-child {
        width: 100%;
    }

    .queue-pagination nav span,
    .queue-pagination nav a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 0.6rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 200ms;
    }

    .queue-pagination nav span[aria-current="page"] span {
        background: rgba(59, 130, 246, 0.25);
        border: 1px solid rgba(59, 130, 246, 0.5);
        color: #bfdbfe;
    }

    .queue-pagination nav a {
        color: #94a3b8;
        border: 1px solid rgba(148, 163, 184, 0.2);
        background: rgba(15, 23, 42, 0.4);
    }

    .queue-pagination nav a:hover {
        background: rgba(59, 130, 246, 0.15);
        border-color: rgba(59, 130, 246, 0.4);
        color: #bfdbfe;
    }

    .queue-pagination nav span[aria-disabled="true"] span {
        color: #475569;
        border: 1px solid rgba(148, 163, 184, 0.1);
        background: transparent;
        cursor: default;
    }

    /* Pagination flex row */
    .queue-pagination nav > div > span {
        display: inline-flex;
        gap: 0.35rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    /* Style for light mode admin */
    [data-theme="light"] .queue-pagination nav span[aria-current="page"] span {
        background: rgba(59, 130, 246, 0.12);
        border-color: rgba(59, 130, 246, 0.4);
        color: #2563eb;
    }

    [data-theme="light"] .queue-pagination nav a {
        color: #475569;
        border-color: rgba(26, 35, 50, 0.15);
        background: rgba(226, 232, 240, 0.4);
    }

    [data-theme="light"] .queue-pagination nav a:hover {
        background: rgba(59, 130, 246, 0.1);
        color: #2563eb;
    }

    [data-theme="light"] .queue-pagination nav span[aria-disabled="true"] span {
        color: #94a3b8;
    }

    [data-theme="light"] .row-link {
        background: rgba(59, 130, 246, 0.1);
        color: #2563eb;
        border-color: rgba(59, 130, 246, 0.4);
    }

    [data-theme="light"] .row-link:hover {
        background: rgba(59, 130, 246, 0.15);
        color: #1d4ed8;
        border-color: rgba(59, 130, 246, 0.5);
    }
</style>
@endsection

@section('content')
<div class="admin-wrapper relative min-h-screen overflow-hidden">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="admin-dashboard-container relative z-10 queue-wrap">
        <header class="admin-header glass-panel">
            <div>
                <h1 class="admin-title">Application Review Queue</h1>
                <p class="admin-subtitle">Review incoming applications and update decision status.</p>
            </div>
        </header>

        <div class="glass-panel" style="margin-top: 1rem;">
            <div class="queue-filters">
                <a class="queue-pill {{ empty($status) ? 'active' : '' }}" href="{{ route('admin.applications.index') }}">All ({{ $statusCounts['All'] ?? 0 }})</a>
                @foreach(['Submitted', 'Under Review', 'Approved', 'Rejected', 'Withdrawn'] as $label)
                    <a
                        class="queue-pill {{ $status === $label ? 'active' : '' }}"
                        href="{{ route('admin.applications.index', ['status' => $label]) }}"
                    >
                        {{ $label }} ({{ $statusCounts[$label] ?? 0 }})
                    </a>
                @endforeach
            </div>

            <div style="overflow:auto;">
                <table class="queue-table">
                    <thead>
                        <tr>
                            <th>Application</th>
                            <th>Applicant</th>
                            <th>Pet</th>
                            <th>Submitted</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            <tr>
                                <td>#{{ $application->id }}</td>
                                <td>{{ $application->user?->name ?? 'Unknown User' }}</td>
                                <td>{{ $application->pet?->name ?? 'Unknown Pet' }}</td>
                                <td>{{ optional($application->submitted_at)->format('M d, Y h:i A') ?? $application->created_at->format('M d, Y h:i A') }}</td>
                                <td>
                                    <span class="status-badge {{ $application->status }}">{{ $application->status }}</span>
                                </td>
                                <td>
                                    <a class="row-link" href="{{ route('admin.applications.show', $application) }}">Review</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center; color:#94a3b8;">No applications found for this filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="queue-pagination" style="margin-top: 1rem;">
                {{ $applications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
