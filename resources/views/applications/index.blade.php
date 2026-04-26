@extends('app')

@section('title', 'My Applications - PawfectMatch')

@section('styles')
<style>
    .applications-wrap {
        max-width: 1000px;
        margin: 0 auto;
        padding: 7.5rem 1.5rem 3.5rem;
    }

    .applications-head {
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .applications-head h1 {
        margin: 0;
        font-family: var(--font-serif);
        color: var(--navy);
        font-size: clamp(1.8rem, 3.4vw, 2.4rem);
    }

    .applications-head p {
        margin: 0.4rem 0 0;
        color: #64748b;
    }

    .applications-grid {
        display: grid;
        gap: 1rem;
    }

    .application-card {
        border: 1px solid rgba(26, 35, 50, 0.08);
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 10px 24px rgba(26, 35, 50, 0.07);
        padding: 1rem;
        display: grid;
        grid-template-columns: 96px 1fr auto;
        gap: 1rem;
        align-items: center;
    }

    .application-card img {
        width: 96px;
        height: 96px;
        border-radius: 12px;
        object-fit: cover;
    }

    .application-card h2 {
        margin: 0;
        color: var(--navy);
        font-size: 1.2rem;
    }

    .meta {
        margin-top: 0.35rem;
        color: #64748b;
        font-size: 0.92rem;
        line-height: 1.5;
    }

    .status {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.32rem 0.68rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.78rem;
        letter-spacing: 0.01em;
        border: 1px solid transparent;
    }

    .status.Submitted {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }

    .status.Under.Review {
        background: #fffbeb;
        color: #b45309;
        border-color: #fde68a;
    }

    .status.Approved {
        background: #ecfdf5;
        color: #047857;
        border-color: #a7f3d0;
    }

    .status.Rejected,
    .status.Withdrawn {
        background: #fef2f2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .btn-view {
        text-decoration: none;
        padding: 0.64rem 0.95rem;
        border-radius: 10px;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(120deg, #ff6b6b, #ff8e8e);
        box-shadow: 0 8px 14px rgba(255, 107, 107, 0.22);
        white-space: nowrap;
    }

    .empty {
        border: 1px dashed #cbd5e1;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        background: #fff;
        color: #64748b;
    }

    .empty a {
        color: #1d4ed8;
        font-weight: 700;
        text-decoration: none;
    }

    .pagination-wrap {
        margin-top: 1.5rem;
    }

    @media (max-width: 820px) {
        .application-card {
            grid-template-columns: 72px 1fr;
        }

        .application-card img {
            width: 72px;
            height: 72px;
        }

        .application-card .action {
            grid-column: 1 / -1;
            justify-self: end;
        }
    }
</style>
@endsection

@section('content')
<div class="applications-wrap">
    <div class="applications-head">
        <div>
            <h1>My Applications</h1>
            <p>Track your adoption requests and review updates from the rescue team.</p>
        </div>
    </div>

    @if($applications->isEmpty())
        <div class="empty">
            <p>You have not submitted any applications yet.</p>
            <a href="{{ route('pets.index') }}">Browse available pets</a>
        </div>
    @else
        <div class="applications-grid">
            @foreach($applications as $application)
                <article class="application-card">
                    <img src="{{ $application->pet?->image_url ? asset('images/pets/' . $application->pet->image_url) : asset('images/auth-pet.png') }}" alt="{{ $application->pet?->name ?? 'Pet' }}">

                    <div>
                        <h2>{{ $application->pet?->name ?? 'Unknown pet' }}</h2>
                        <div class="meta">
                            <div>{{ $application->pet?->breed ?? 'No breed info' }} • {{ $application->pet?->species ?? 'Unknown species' }}</div>
                            <div>Submitted {{ optional($application->submitted_at)->format('M d, Y h:i A') ?? $application->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        <div style="margin-top: 0.55rem;">
                            <span class="status {{ str_replace(' ', '.', $application->status) }}">{{ $application->status }}</span>
                        </div>
                    </div>

                    <div class="action">
                        <a href="{{ route('applications.show', $application) }}" class="btn-view">View Details</a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="pagination-wrap">
            {{ $applications->links() }}
        </div>
    @endif
</div>
@endsection
