@extends('app')

@section('title', 'Manage Pets — Admin | PawfectMatch')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/modules.css') }}">
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
                <h1 class="admin-title">Manage All Pets</h1>
                <p class="admin-subtitle">Add, edit, or remove pets from the system.</p>
            </div>

            @if($adminUser && $adminUser->hasRole(['admin']))
            <a href="{{ route('admin.pets.create') }}" class="btn-action">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add New Pet
            </a>
            @endif
        </header>

        {{-- Pet Management Table --}}
        <div class="admin-table-container glass-panel mt-4">
            <div class="table-header">
                <h3>All Pets ({{ $pets->total() }})</h3>
            </div>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Pet Name</th>
                        <th>Species</th>
                        <th>Breed</th>
                        <th>Energy</th>
                        <th>Age</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pets as $pet)
                    <tr id="pet-row-{{ $pet->id }}">
                        <td>
                            <div class="inline-edit" data-id="{{ $pet->id }}" data-field="name">
                                <span class="editable-text">{{ $pet->name }}</span>
                                <input type="text" class="edit-input hidden" value="{{ $pet->name }}">
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
                            <div class="inline-edit" data-id="{{ $pet->id }}" data-field="breed">
                                <span class="editable-text">{{ $pet->breed }}</span>
                                <input type="text" class="edit-input hidden" value="{{ $pet->breed }}">
                            </div>
                        </td>
                        <td>
                            <div class="inline-edit" data-id="{{ $pet->id }}" data-field="energy_level">
                                <span class="editable-text">{{ $pet->energy_level }}</span>
                                <input type="number" min="0" max="10" class="edit-input hidden" value="{{ $pet->energy_level }}">
                            </div>
                        </td>
                        <td>
                            @if($pet->age_months >= 12)
                                {{ floor($pet->age_months / 12) }} yr{{ floor($pet->age_months / 12) > 1 ? 's' : '' }}
                            @else
                                {{ $pet->age_months }} mo
                            @endif
                        </td>
                        <td>
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
                            <div class="table-actions" style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('pets.show', $pet->id) }}" target="_blank" class="action-icon view" aria-label="View">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </a>

                                {{-- Only Admins can delete pets, Staff cannot --}}
                                @if($adminUser && $adminUser->hasRole(['admin']))
                                <form action="{{ route('admin.pets.destroy', $pet->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this pet?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-icon" aria-label="Delete" style="background: rgba(255,107,107,0.2); border: none; cursor: pointer; color: white;">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">No pets found in the system.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            @if($pets->hasPages())
            <div style="display: flex; justify-content: center; align-items: center; margin-top: 2rem; gap: 0.5rem; font-family: var(--font-sans);">
                {{-- Previous --}}
                @if($pets->onFirstPage())
                    <span style="padding: 0.5rem 1rem; border-radius: 8px; background: rgba(51,65,85,0.3); color: #475569; cursor: not-allowed; font-size: 0.9rem;">&laquo; Prev</span>
                @else
                    <a href="{{ $pets->previousPageUrl() }}" style="padding: 0.5rem 1rem; border-radius: 8px; background: rgba(51,65,85,0.6); color: #e2e8f0; text-decoration: none; font-size: 0.9rem; transition: background 200ms;">&laquo; Prev</a>
                @endif

                {{-- Page Numbers --}}
                @foreach($pets->getUrlRange(1, $pets->lastPage()) as $page => $url)
                    @if($page == $pets->currentPage())
                        <span style="padding: 0.5rem 0.85rem; border-radius: 8px; background: #3b82f6; color: white; font-size: 0.9rem; font-weight: 600;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="padding: 0.5rem 0.85rem; border-radius: 8px; background: rgba(51,65,85,0.6); color: #94a3b8; text-decoration: none; font-size: 0.9rem; transition: background 200ms;">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($pets->hasMorePages())
                    <a href="{{ $pets->nextPageUrl() }}" style="padding: 0.5rem 1rem; border-radius: 8px; background: rgba(51,65,85,0.6); color: #e2e8f0; text-decoration: none; font-size: 0.9rem; transition: background 200ms;">Next &raquo;</a>
                @else
                    <span style="padding: 0.5rem 1rem; border-radius: 8px; background: rgba(51,65,85,0.3); color: #475569; cursor: not-allowed; font-size: 0.9rem;">Next &raquo;</span>
                @endif

                <span style="margin-left: 1rem; color: #64748b; font-size: 0.85rem;">Page {{ $pets->currentPage() }} of {{ $pets->lastPage() }}</span>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
const flattenErrors = (errors) => Object.values(errors || {}).flat().join(' ');

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
        throw new Error(payload.message || flattenErrors(payload.errors) || 'Update failed.');
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
                alert(e.message || 'Update failed.');
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
            alert(e.message || 'Update failed.');
        }
    });
});

// Status Toggle
async function updateStatus(selectElement) {
    const newStatus = selectElement.value;
    const petId = selectElement.dataset.id;

    selectElement.className = `status-select ${newStatus.toLowerCase()}`;
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
