@extends('app')

@section('title', 'Review Application #' . $application->id . ' — Admin | PawfectMatch')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/modules.css') }}">
<style>
    .review-wrap {
        max-width: 1100px;
        margin: 0 auto;
        padding: 2rem 0 3rem;
        display: grid;
        gap: 1rem;
    }

    .status-chip {
        display: inline-flex;
        padding: 0.28rem 0.6rem;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 700;
        color: #bfdbfe;
        background: rgba(59,130,246,0.2);
        border: 1px solid rgba(59,130,246,0.35);
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .field {
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(148, 163, 184, 0.1);
    }

    .field h4 {
        margin: 0 0 0.35rem;
        color: #94a3b8;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-family: var(--font-sans);
    }

    .field p {
        margin: 0;
        color: #e2e8f0;
        white-space: pre-wrap;
        line-height: 1.55;
    }

    .review-form label {
        display: block;
        margin-bottom: 0.4rem;
        color: #94a3b8;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .review-form select,
    .review-form textarea {
        width: 100%;
        padding: 0.75rem 0.85rem;
        border-radius: 10px;
        border: 1px solid rgba(148, 163, 184, 0.24);
        background: rgba(15, 23, 42, 0.8);
        color: #e2e8f0;
        font-family: var(--font-sans);
    }

    .review-form textarea {
        min-height: 120px;
        resize: vertical;
    }

    .actions {
        margin-top: 0.8rem;
        display: flex;
        justify-content: flex-end;
        gap: 0.7rem;
    }

    .btn-secondary-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.72rem 1rem;
        border-radius: 10px;
        border: 1px solid rgba(148, 163, 184, 0.35);
        text-decoration: none;
        color: #cbd5e1;
        font-weight: 700;
    }

    @media (max-width: 920px) {
        .grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="admin-wrapper relative min-h-screen overflow-hidden">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="admin-dashboard-container relative z-10 review-wrap">
        <header class="admin-header glass-panel">
            <div>
                <h1 class="admin-title">Application #{{ $application->id }}</h1>
                <p class="admin-subtitle">
                    {{ $application->user?->name ?? 'Unknown User' }} applying for {{ $application->pet?->name ?? 'Unknown Pet' }}
                </p>
                <span class="status-chip">{{ $application->status }}</span>
            </div>
            <a href="{{ route('admin.applications.index') }}" class="btn-secondary-link">Back to Queue</a>
        </header>

        @if(session('success'))
            <div class="glass-panel" style="border-color: rgba(34,197,94,0.4); color:#86efac;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="glass-panel" style="border-color: rgba(248,113,113,0.4); color:#fca5a5;">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="glass-panel">
            <div class="grid">
                <div class="field">
                    <h4>Submitted At</h4>
                    <p>{{ optional($application->submitted_at)->format('M d, Y h:i A') ?? $application->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div class="field">
                    <h4>Reviewed At</h4>
                    <p>{{ optional($application->reviewed_at)->format('M d, Y h:i A') ?? 'Not reviewed yet' }}</p>
                </div>
                <div class="field">
                    <h4>Home Type</h4>
                    <p>{{ $application->home_type ?: 'Not provided' }}</p>
                </div>
                <div class="field">
                    <h4>Household Members</h4>
                    <p>{{ $application->household_members ?: 'Not provided' }}</p>
                </div>
                <div class="field">
                    <h4>Has Other Pets</h4>
                    <p>{{ $application->has_other_pets ? 'Yes' : 'No' }}</p>
                </div>
                <div class="field">
                    <h4>Yard Available</h4>
                    <p>{{ $application->yard_available ? 'Yes' : 'No' }}</p>
                </div>
                <div class="field" style="grid-column: 1 / -1;">
                    <h4>Other Pets Details</h4>
                    <p>{{ $application->other_pets_details ?: 'Not provided' }}</p>
                </div>
                <div class="field" style="grid-column: 1 / -1;">
                    <h4>Experience With Pets</h4>
                    <p>{{ $application->experience_with_pets ?: 'Not provided' }}</p>
                </div>
                <div class="field" style="grid-column: 1 / -1;">
                    <h4>Employment & Financial Sustainability</h4>
                    <p>{{ $application->employment_sustainability ?: 'Not provided' }}</p>
                </div>
                <div class="field" style="grid-column: 1 / -1;">
                    <h4>Reason For Adoption</h4>
                    <p>{{ $application->reason_for_adoption ?: 'Not provided' }}</p>
                </div>
                <div class="field" style="grid-column: 1 / -1;">
                    <h4>References</h4>
                    <p>{{ $application->references ?: 'Not provided' }}</p>
                </div>
                <div class="field" style="grid-column: 1 / -1;">
                    <h4>Additional Information</h4>
                    <p>{{ $application->additional_information ?: 'Not provided' }}</p>
                </div>
            </div>
        </div>

        <div class="glass-panel">
            <h3 style="margin-top: 0; color: #e2e8f0;">Review Decision</h3>
            <form class="review-form" method="POST" action="{{ route('admin.applications.updateStatus', $application) }}">
                @csrf
                @method('PATCH')

                <div style="margin-bottom: 0.9rem;">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        @foreach(['Submitted', 'Under Review', 'Approved', 'Rejected', 'Withdrawn'] as $statusOption)
                            <option value="{{ $statusOption }}" {{ $application->status === $statusOption ? 'selected' : '' }}>
                                {{ $statusOption }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="review_notes">Review Notes</label>
                    <textarea id="review_notes" name="review_notes" placeholder="Document rationale, follow-up requests, or decision notes...">{{ old('review_notes', $application->review_notes) }}</textarea>
                </div>

                <div class="actions">
                    <a href="{{ route('admin.applications.index') }}" class="btn-secondary-link">Cancel</a>
                    <button type="submit" class="btn-action">Save Review</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
