@extends('app')

@section('title', 'Donations Ledger — Admin | PawfectMatch')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/modules.css') }}">
<style>
    .ledger-wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 0 3rem;
    }

    .ledger-filters {
        display: flex;
        gap: 0.6rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .ledger-pill {
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

    .ledger-pill:hover {
        border-color: rgba(34, 197, 94, 0.5);
        background: rgba(34, 197, 94, 0.1);
        color: #bbf7d0;
    }

    .ledger-pill.active {
        border-color: rgba(34, 197, 94, 0.65);
        background: rgba(34, 197, 94, 0.2);
        color: #bbf7d0;
    }

    .total-raised {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border-radius: 999px;
        border: 1px solid rgba(34, 197, 94, 0.45);
        padding: 0.4rem 0.8rem;
        color: #bbf7d0;
        font-size: 0.85rem;
        font-weight: 700;
        background: rgba(34, 197, 94, 0.13);
    }

    .ledger-table {
        width: 100%;
        border-collapse: collapse;
    }

    .ledger-table th,
    .ledger-table td {
        padding: 0.9rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.14);
        color: #e2e8f0;
        text-align: left;
        font-size: 0.92rem;
    }

    .ledger-table th {
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.72rem;
    }

    .status-badge {
        font-size: 0.85rem;
        font-weight: 700;
    }

    .status-badge.Confirmed { color: #10b981; }
    .status-badge.Pending { color: #f59e0b; }
    .status-badge.Refunded,
    .status-badge.Cancelled { color: #ef4444; }

    .status-form {
        display: inline-flex;
        gap: 0.5rem;
        align-items: center;
    }

    .status-select {
        border-radius: 8px;
        border: 1px solid rgba(148, 163, 184, 0.4);
        background: rgba(15, 23, 42, 0.8);
        color: #e2e8f0;
        font-size: 0.78rem;
        padding: 0.3rem 0.45rem;
    }

    .status-save {
        border: 1px solid rgba(148, 163, 184, 0.4);
        background: rgba(30, 41, 59, 0.7);
        color: #e2e8f0;
        border-radius: 8px;
        padding: 0.28rem 0.52rem;
        font-size: 0.74rem;
        font-weight: 700;
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="admin-wrapper relative min-h-screen overflow-hidden">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="admin-dashboard-container relative z-10 ledger-wrap">
        @if(session('success'))
            <div style="margin-bottom: 1rem; padding: 0.85rem 1rem; border-radius: 10px; background: rgba(22, 163, 74, 0.14); border: 1px solid rgba(22, 163, 74, 0.35); color: #bbf7d0;">
                {{ session('success') }}
            </div>
        @endif

        <header class="admin-header glass-panel">
            <div>
                <h1 class="admin-title">Donations Ledger</h1>
                <p class="admin-subtitle">Track financial support for operations and pet care.</p>
            </div>
            <div class="total-raised">Total Confirmed: ${{ number_format((float) $totalRaised, 2) }}</div>
        </header>

        <div class="glass-panel" style="margin-top: 1rem;">
            <div class="ledger-filters">
                <a class="ledger-pill {{ empty($status) ? 'active' : '' }}" href="{{ route('admin.donations.index') }}">All ({{ $statusCounts['All'] ?? 0 }})</a>
                @foreach(['Confirmed', 'Pending', 'Refunded', 'Cancelled'] as $label)
                    <a
                        class="ledger-pill {{ $status === $label ? 'active' : '' }}"
                        href="{{ route('admin.donations.index', ['status' => $label]) }}"
                    >
                        {{ $label }} ({{ $statusCounts[$label] ?? 0 }})
                    </a>
                @endforeach
            </div>

            <div style="overflow:auto;">
                <table class="ledger-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Donor</th>
                            <th>Pet</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donations as $donation)
                            <tr>
                                <td>#{{ $donation->id }}</td>
                                <td>{{ $donation->is_anonymous ? 'Anonymous' : $donation->donor_name }}</td>
                                <td>{{ $donation->pet?->name ?? 'General Support' }}</td>
                                <td>{{ $donation->currency }} {{ number_format((float) $donation->amount, 2) }}</td>
                                <td>
                                    {{ $donation->payment_method }}
                                    @if($donation->payment_reference)
                                        <div style="font-size: 0.78rem; color: #94a3b8; margin-top: 0.2rem;">{{ $donation->payment_reference }}</div>
                                    @endif
                                </td>
                                <td>{{ optional($donation->donated_at)->format('M d, Y h:i A') ?? $donation->created_at->format('M d, Y h:i A') }}</td>
                                <td>
                                    <form class="status-form" method="POST" action="{{ route('admin.donations.updateStatus', ['donation' => $donation, 'status' => $status]) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select class="status-select" name="status" aria-label="Update donation status for donation {{ $donation->id }}">
                                            @foreach(['Confirmed', 'Pending', 'Refunded', 'Cancelled'] as $statusOption)
                                                <option value="{{ $statusOption }}" {{ $donation->status === $statusOption ? 'selected' : '' }}>{{ $statusOption }}</option>
                                            @endforeach
                                        </select>
                                        <button class="status-save" type="submit">Save</button>
                                    </form>
                                    <span class="status-badge {{ $donation->status }}" style="margin-top: 0.35rem;">{{ $donation->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center; color:#94a3b8;">No donations found for this filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1rem;">
                {{ $donations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
