@extends('app')

@section('title', 'Manage Testimonials — Admin | PawfectMatch')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/modules.css') }}">
<style>
    .ledger-wrap { max-width: 1200px; margin: 0 auto; padding: 2rem 0 3rem; }
    .ledger-filters { display: flex; gap: 0.6rem; flex-wrap: wrap; margin-bottom: 1rem; }
    .ledger-pill { padding: 0.45rem 0.8rem; border-radius: 999px; border: 1px solid rgba(148, 163, 184, 0.35); text-decoration: none; color: #e2e8f0; background: rgba(15, 23, 42, 0.5); font-size: 0.84rem; font-weight: 700; cursor: pointer; transition: all 200ms ease; }
    .ledger-pill:hover { border-color: rgba(34, 197, 94, 0.5); background: rgba(34, 197, 94, 0.1); color: #bbf7d0; }
    .ledger-pill.active { border-color: rgba(34, 197, 94, 0.65); background: rgba(34, 197, 94, 0.2); color: #bbf7d0; }
    .ledger-table { width: 100%; border-collapse: collapse; }
    .ledger-table th, .ledger-table td { padding: 0.9rem; border-bottom: 1px solid rgba(148, 163, 184, 0.14); color: #e2e8f0; text-align: left; font-size: 0.92rem; }
    .ledger-table th { color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.72rem; }
    .status-badge { font-size: 0.85rem; font-weight: 700; }
    .status-badge.Approved { color: #10b981; }
    .status-badge.Pending { color: #f59e0b; }
    .action-btn { background: rgba(30, 41, 59, 0.7); color: #e2e8f0; border-radius: 8px; border: 1px solid rgba(148, 163, 184, 0.4); padding: 0.3rem 0.6rem; font-size: 0.78rem; text-decoration: none; display: inline-block; cursor: pointer; transition: 0.2s; margin-right: 0.3rem; }
    .action-btn:hover { background: rgba(51, 65, 85, 0.8); }
    .action-btn.approve { border-color: rgba(34, 197, 94, 0.5); color: #86efac; }
    .action-btn.reject { border-color: rgba(248, 113, 113, 0.5); color: #fca5a5; }
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
                <h1 class="admin-title">Manage Testimonials</h1>
                <p class="admin-subtitle">Review, approve, or reject user-submitted stories before they go live.</p>
            </div>
        </header>

        <div class="glass-panel" style="margin-top: 1rem;">
            <div class="ledger-filters">
                <a class="ledger-pill {{ empty($status) ? 'active' : '' }}" href="{{ route('admin.testimonials.index') }}">All ({{ $statusCounts['All'] ?? 0 }})</a>
                <a class="ledger-pill {{ $status === 'pending' ? 'active' : '' }}" href="{{ route('admin.testimonials.index', ['status' => 'pending']) }}">Pending ({{ $statusCounts['Pending'] ?? 0 }})</a>
                <a class="ledger-pill {{ $status === 'approved' ? 'active' : '' }}" href="{{ route('admin.testimonials.index', ['status' => 'approved']) }}">Approved ({{ $statusCounts['Approved'] ?? 0 }})</a>
            </div>

            <div style="overflow:auto;">
                <table class="ledger-table">
                    <thead>
                        <tr>
                            <th>Adopter</th>
                            <th>Pet</th>
                            <th>Story Preview</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($testimonials as $testimonial)
                            <tr>
                                <td>{{ $testimonial->adopter_name }}</td>
                                <td>{{ $testimonial->pet_name }}</td>
                                <td style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    "{{ $testimonial->story_text }}"
                                </td>
                                <td>{{ $testimonial->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="status-badge {{ $testimonial->is_approved ? 'Approved' : 'Pending' }}">
                                        {{ $testimonial->is_approved ? 'Approved' : 'Pending' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('testimonials.show', $testimonial) }}" target="_blank" class="action-btn">View</a>
                                    
                                    <form method="POST" action="{{ route('admin.testimonials.updateStatus', $testimonial) }}" style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $testimonial->is_approved ? '0' : '1' }}">
                                        <button type="submit" class="action-btn {{ $testimonial->is_approved ? 'reject' : 'approve' }}">
                                            {{ $testimonial->is_approved ? 'Unapprove' : 'Approve' }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this testimonial?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn reject">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center; color:#94a3b8;">No testimonials found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1rem;">
                {{ $testimonials->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
