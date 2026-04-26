@extends('app')

@section('title', 'My Donations - PawfectMatch')

@section('styles')
<style>
    .donations-wrap {
        max-width: 1000px;
        margin: 0 auto;
        padding: 7.5rem 1.5rem 3.5rem;
    }

    .donations-head {
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .donations-head h1 {
        margin: 0;
        font-family: var(--font-serif);
        color: var(--navy);
        font-size: clamp(1.8rem, 3.4vw, 2.4rem);
    }

    .donations-head p {
        margin: 0.4rem 0 0;
        color: #64748b;
    }

    .total-pill {
        border-radius: 999px;
        background: #ecfeff;
        border: 1px solid #a5f3fc;
        color: #155e75;
        padding: 0.5rem 0.9rem;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .donations-grid {
        display: grid;
        gap: 1rem;
    }

    .donation-card {
        border: 1px solid rgba(26, 35, 50, 0.08);
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 10px 24px rgba(26, 35, 50, 0.07);
        padding: 1rem;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 1rem;
        align-items: center;
    }

    .donation-title {
        margin: 0;
        color: var(--navy);
        font-size: 1.1rem;
    }

    .meta {
        margin-top: 0.35rem;
        color: #64748b;
        font-size: 0.92rem;
        line-height: 1.5;
    }

    .amount {
        font-weight: 800;
        color: #0f766e;
        font-size: 1.15rem;
    }

    .status {
        margin-top: 0.35rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.28rem 0.62rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.75rem;
        border: 1px solid #a7f3d0;
        background: #ecfdf5;
        color: #047857;
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

    .alert-success {
        margin-bottom: 1rem;
        padding: 0.85rem 1rem;
        border-radius: 12px;
        border: 1px solid #a7f3d0;
        background: #ecfdf5;
        color: #065f46;
    }

    @media (max-width: 768px) {
        .donation-card {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="donations-wrap">
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="donations-head">
        <div>
            <h1>My Donations</h1>
            <p>See your contribution history and impact.</p>
        </div>
        <div class="total-pill">Total Confirmed: ${{ number_format((float) $totalDonated, 2) }}</div>
    </div>

    @if($donations->isEmpty())
        <div class="empty">
            <p>You have not made any donations yet.</p>
            <a href="{{ route('donations.create') }}">Make your first donation</a>
        </div>
    @else
        <div class="donations-grid">
            @foreach($donations as $donation)
                <article class="donation-card">
                    <div>
                        <h2 class="donation-title">
                            {{ $donation->pet?->name ? 'Support for ' . $donation->pet->name : 'General Rescue Donation' }}
                        </h2>
                        <div class="meta">
                            <div>
                                {{ $donation->payment_method }} • {{ $donation->currency }}
                                @if($donation->payment_reference)
                                    • {{ $donation->payment_reference }}
                                @endif
                            </div>
                            <div>Donated {{ optional($donation->donated_at)->format('M d, Y h:i A') ?? $donation->created_at->format('M d, Y h:i A') }}</div>
                            @if($donation->message)
                                <div>Message: {{ $donation->message }}</div>
                            @endif
                        </div>
                        <span class="status">{{ $donation->status }}</span>
                    </div>

                    <div class="amount">
                        {{ $donation->currency }} {{ number_format((float) $donation->amount, 2) }}
                    </div>
                </article>
            @endforeach
        </div>

        <div class="pagination-wrap">
            {{ $donations->links() }}
        </div>
    @endif
</div>
@endsection
