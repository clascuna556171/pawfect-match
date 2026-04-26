@extends('app')

@section('title', 'Application Details - PawfectMatch')

@section('styles')
<style>
    .application-detail {
        max-width: 920px;
        margin: 0 auto;
        padding: 7.5rem 1.5rem 3.5rem;
    }

    .detail-card {
        border: 1px solid rgba(26, 35, 50, 0.08);
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 12px 26px rgba(26, 35, 50, 0.08);
        overflow: hidden;
    }

    .detail-head {
        padding: 1.4rem;
        border-bottom: 1px solid rgba(26, 35, 50, 0.08);
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .detail-head h1 {
        margin: 0;
        font-family: var(--font-serif);
        color: var(--navy);
        font-size: clamp(1.4rem, 2.8vw, 1.9rem);
    }

    .status-pill {
        display: inline-flex;
        border-radius: 999px;
        padding: 0.35rem 0.72rem;
        font-weight: 700;
        font-size: 0.8rem;
        border: 1px solid transparent;
    }

    .status-pill.Submitted { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
    .status-pill.Under.Review { background: #fffbeb; color: #b45309; border-color: #fde68a; }
    .status-pill.Approved { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }
    .status-pill.Rejected, .status-pill.Withdrawn { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }

    .detail-body {
        padding: 1.4rem;
        display: grid;
        gap: 1rem;
    }

    .row {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.85rem 0.95rem;
        background: #fff;
    }

    .row strong {
        display: block;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }

    .row span {
        color: #475569;
        white-space: pre-wrap;
        line-height: 1.55;
    }

    .actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 1rem;
    }

    .back {
        text-decoration: none;
        font-weight: 700;
        color: #334155;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 0.62rem 0.95rem;
        background: #fff;
    }
</style>
@endsection

@section('content')
<div class="application-detail">
    <div class="detail-card">
        <div class="detail-head">
            <h1>Application for {{ $application->pet?->name ?? 'Pet' }}</h1>
            <span class="status-pill {{ str_replace(' ', '.', $application->status) }}">{{ $application->status }}</span>
        </div>

        <div class="detail-body">
            <div class="row">
                <strong>Submitted at</strong>
                <span>{{ optional($application->submitted_at)->format('M d, Y h:i A') ?? $application->created_at->format('M d, Y h:i A') }}</span>
            </div>

            <div class="row">
                <strong>Home type</strong>
                <span>{{ $application->home_type ?: 'Not provided' }}</span>
            </div>

            <div class="row">
                <strong>Household members</strong>
                <span>{{ $application->household_members ?: 'Not provided' }}</span>
            </div>

            <div class="row">
                <strong>Has other pets</strong>
                <span>{{ $application->has_other_pets ? 'Yes' : 'No' }}</span>
            </div>

            <div class="row">
                <strong>Other pets details</strong>
                <span>{{ $application->other_pets_details ?: 'Not provided' }}</span>
            </div>

            <div class="row">
                <strong>Yard available</strong>
                <span>{{ $application->yard_available ? 'Yes' : 'No' }}</span>
            </div>

            <div class="row">
                <strong>Experience with pets</strong>
                <span>{{ $application->experience_with_pets ?: 'Not provided' }}</span>
            </div>

            <div class="row">
                <strong>Reason for adoption</strong>
                <span>{{ $application->reason_for_adoption ?: 'Not provided' }}</span>
            </div>

            <div class="row">
                <strong>References</strong>
                <span>{{ $application->references ?: 'Not provided' }}</span>
            </div>

            <div class="row">
                <strong>Additional information</strong>
                <span>{{ $application->additional_information ?: 'Not provided' }}</span>
            </div>

            <div class="actions" style="display: flex; gap: 1rem; align-items: center; justify-content: flex-end; margin-top: 1rem;">
                @if(strtolower($application->status) === 'approved')
                    <a href="{{ route('updates.create', $application) }}" class="back" style="background: linear-gradient(135deg, #10b981, #34d399); color: white; border: none; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);">Post an Update</a>
                @endif
                <a href="{{ route('applications.index') }}" class="back">Back to My Applications</a>
            </div>
        </div>
    </div>

    @php
        $updates = \App\Models\PetUpdate::where('adoption_id', $application->id)->latest()->get();
    @endphp

    @if($updates->count() > 0)
    <div class="detail-card" style="margin-top: 2rem;">
        <div class="detail-head">
            <h2 style="margin: 0; font-family: var(--font-serif); color: var(--navy); font-size: 1.5rem;">Post-Adoption Updates</h2>
        </div>
        <div class="detail-body" style="gap: 1.5rem;">
            @foreach($updates as $update)
                <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; background: #f8fafc;">
                    <span style="display: block; color: #64748b; font-size: 0.9rem; margin-bottom: 0.75rem;">{{ $update->created_at->format('M d, Y') }}</span>
                    <p style="margin: 0 0 1rem; color: #334155; line-height: 1.6; white-space: pre-line;">{{ $update->status_message }}</p>
                    
                    @if($update->image_path)
                        <img src="{{ asset('storage/' . $update->image_path) }}" alt="Update Image" style="max-width: 100%; border-radius: 8px; max-height: 300px; object-fit: cover;">
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
