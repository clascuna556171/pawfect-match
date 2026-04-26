@extends('app')

@section('title', 'Admin Dashboard — PawfectMatch')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/modules.css') }}">
{{-- Load Chart.js for data visualization --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
@php($adminUser = \Illuminate\Support\Facades\Auth::guard('admin')->user())
<div class="admin-wrapper relative min-h-screen overflow-hidden">
    {{-- Animated Background Orbs --}}
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="admin-dashboard-container relative z-10">
        <header class="admin-header glass-panel">
            <div>
                <h1 class="admin-title">
                    @if($adminUser && $adminUser->hasRole(['admin']))
                        Admin Portal
                    @else
                        Staff Portal
                    @endif
                </h1>
                <p class="admin-subtitle">Welcome back, {{ $adminUser?->name }}. Here's what's happening today.</p>
            </div>
            
            @if($adminUser && $adminUser->hasRole(['admin']))
            <a href="{{ route('admin.pets.create') }}" class="btn-action">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add New Pet
            </a>
            @endif
        </header>

        {{-- Top Stats row --}}
        <div class="admin-stats-grid">
            <div class="stat-card glass-panel">
                <h4>Total Pets</h4>
                <div class="stat-value">{{ $stats['total_pets'] }}</div>
            </div>
            <div class="stat-card glass-panel">
                <h4>Available</h4>
                <div class="stat-value text-mint">{{ $stats['available'] }}</div>
            </div>
            <div class="stat-card glass-panel">
                <h4>Pending</h4>
                <div class="stat-value text-amber">{{ $stats['pending'] }}</div>
            </div>
            <div class="stat-card glass-panel">
                <h4>Adopted</h4>
                <div class="stat-value text-coral">{{ $stats['adopted'] }}</div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="admin-charts-grid">
            <div class="chart-container glass-panel">
                <h3>Adoption Status Distribution</h3>
                <div class="chart-wrapper pie-chart-wrapper">
                    <canvas id="statusPieChart"></canvas>
                </div>
            </div>
            
            <div class="chart-container glass-panel">
                <h3>Monthly Adoption Trends</h3>
                <div class="chart-wrapper">
                    <canvas id="trendsLineChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Recent Activity / Inline Editing Table --}}
        <div class="admin-table-container glass-panel mt-4">
            <div class="table-header">
                <h3>Recent Pets & Status Management</h3>
                <a href="{{ route('admin.pets.index') }}" class="view-all-link">Manage All Pets &rarr;</a>
            </div>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Pet Name</th>
                        <th>Breed</th>
                        <th>Species</th>
                        <th>Energy</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentPets as $pet)
                    <tr id="pet-row-{{ $pet->id }}">
                        {{-- Inline editable name field --}}
                        <td>
                            <div class="inline-edit" data-id="{{ $pet->id }}" data-field="name">
                                <span class="editable-text">{{ $pet->name }}</span>
                                <input type="text" class="edit-input hidden" value="{{ $pet->name }}">
                            </div>
                        </td>
                        <td>
                            <div class="inline-edit" data-id="{{ $pet->id }}" data-field="breed">
                                <span class="editable-text">{{ $pet->breed }}</span>
                                <input type="text" class="edit-input hidden" value="{{ $pet->breed }}">
                            </div>
                        </td>
                        <td>
                            <select class="inline-select" data-id="{{ $pet->id }}" data-field="species">
                                @foreach(['Dog', 'Cat', 'Rabbit', 'Hamster', 'Bird', 'Other'] as $species)
                                    <option value="{{ $species }}" {{ $pet->species === $species ? 'selected' : '' }}>{{ $species }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <div class="inline-edit" data-id="{{ $pet->id }}" data-field="energy_level">
                                <span class="editable-text">{{ $pet->energy_level }}</span>
                                <input type="number" min="0" max="10" class="edit-input hidden" value="{{ $pet->energy_level }}">
                            </div>
                        </td>
                        <td>
                            {{-- Animated Select Toggle for Status Management --}}
                            <div class="status-toggle-wrapper">
                                <select class="status-select {{ strtolower($pet->adoption_status) }}" data-id="{{ $pet->id }}" onchange="updateStatus(this)">
                                    <option value="Available" {{ $pet->adoption_status == 'Available' ? 'selected' : '' }}>Available</option>
                                    <option value="Pending" {{ $pet->adoption_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Adopted" {{ $pet->adoption_status == 'Adopted' ? 'selected' : '' }}>Adopted</option>
                                    <option value="Not Available" {{ $pet->adoption_status == 'Not Available' ? 'selected' : '' }}>Not Available</option>
                                    <option value="On Hold" {{ $pet->adoption_status == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('pets.show', $pet->id) }}" target="_blank" class="action-icon view" aria-label="View">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No recent pets found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
const flattenErrors = (errors) => Object.values(errors || {}).flat().join(' ');

document.addEventListener('DOMContentLoaded', function() {
    const ctxPie = document.getElementById('statusPieChart').getContext('2d');
    const ctxLine = document.getElementById('trendsLineChart').getContext('2d');

    // Chart.js global defaults for glass styling
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.font.family = "'Inter', sans-serif";

    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ['Available', 'Pending', 'Adopted'],
            datasets: [{
                data: [{{ $stats['available'] }}, {{ $stats['pending'] }}, {{ $stats['adopted'] }}],
                backgroundColor: [
                    '#34d399', // emerald
                    '#fbbf24', // amber
                    '#f87171'  // red
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 800,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#94a3b8', padding: 20 }
                }
            }
        }
    });

    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: @json($chartData['trend_labels']),
            datasets: [{
                label: 'Applications',
                data: @json($chartData['monthly_applications']),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#e2e8f0',
                pointBorderColor: '#3b82f6',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }, {
                label: 'Adoptions',
                data: @json($chartData['monthly_adoptions']),
                borderColor: '#34d399',
                backgroundColor: 'rgba(52, 211, 153, 0.08)',
                tension: 0.35,
                fill: false,
                pointBackgroundColor: '#a7f3d0',
                pointBorderColor: '#34d399',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(148, 163, 184, 0.08)', borderDash: [5, 5] }
                },
                x: {
                    grid: { display: false }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    labels: { color: '#94a3b8' }
                }
            }
        }
    });

    async function updatePetField(petId, field, value) {
        const response = await fetch(`/admin/pets/${petId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ [field]: value })
        });

        const payload = await response.json().catch(() => ({}));

        if (!response.ok || !payload.success) {
            const errorText = payload.message || flattenErrors(payload.errors) || 'Update failed.';
            throw new Error(errorText);
        }
    }

    // Inline Editing Logic
    document.querySelectorAll('.inline-edit').forEach(cell => {
        const textSpan = cell.querySelector('.editable-text');
        const inputField = cell.querySelector('.edit-input');

        textSpan.addEventListener('click', () => {
            textSpan.classList.add('hidden');
            inputField.classList.remove('hidden');
            inputField.focus();
        });

        inputField.addEventListener('blur', async () => {
            const previousValue = textSpan.textContent;
            const newValue = inputField.value;
            const petId = cell.dataset.id;
            const field = cell.dataset.field;

            if (newValue !== previousValue) {
                try {
                    await updatePetField(petId, field, newValue);
                    textSpan.textContent = newValue;
                } catch (e) {
                    textSpan.textContent = previousValue;
                    window.alert(e.message || 'Update failed.');
                }
            }
            
            inputField.classList.add('hidden');
            textSpan.classList.remove('hidden');
        });

        inputField.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') inputField.blur();
        });
    });

    document.querySelectorAll('.inline-select').forEach(select => {
        let previousValue = select.value;

        select.addEventListener('focus', () => {
            previousValue = select.value;
        });

        select.addEventListener('change', async () => {
            try {
                await updatePetField(select.dataset.id, select.dataset.field, select.value);
                previousValue = select.value;
            } catch (e) {
                select.value = previousValue;
                window.alert(e.message || 'Update failed.');
            }
        });
    });
});

// Select Dropdown Status Manager
async function updateStatus(selectElement) {
    const newStatus = selectElement.value;
    const petId = selectElement.dataset.id;
    
    // Update color class dynamically
    selectElement.className = `status-select ${newStatus.toLowerCase()}`;
    
    // Animate visual feedback
    selectElement.parentElement.classList.add('updating');
    
    try {
        const response = await fetch(`/admin/pets/${petId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        });

        const payload = await response.json().catch(() => ({}));
        if (!response.ok || !payload.success) {
            throw new Error(payload.message || flattenErrors(payload.errors) || 'Failed to update status.');
        }
    } catch (e) {
        console.error('Status update failed', e);
        alert(e.message || 'Failed to update status.');
    } finally {
        setTimeout(() => selectElement.parentElement.classList.remove('updating'), 300);
    }
}
</script>
@endsection
