@extends('app')

@section('title', 'Add New Pet — Admin | PawfectMatch')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/modules.css') }}">
<style>
    .create-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    .create-form-grid .full-width { grid-column: 1 / -1; }

    .admin-form-group { margin-bottom: 0; }
    .admin-form-group label {
        display: block; font-family: var(--font-sans);
        font-size: 0.8rem; font-weight: 600; color: #94a3b8;
        text-transform: uppercase; letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }
    .admin-form-group input,
    .admin-form-group select,
    .admin-form-group textarea {
        width: 100%; padding: 0.85rem 1rem;
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(148, 163, 184, 0.15);
        border-radius: 10px; color: #e2e8f0;
        font-size: 0.95rem; font-family: var(--font-sans);
        transition: all 250ms; outline: none;
        box-sizing: border-box;
    }
    .admin-form-group input:focus,
    .admin-form-group select:focus,
    .admin-form-group textarea:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
    .admin-form-group input::placeholder,
    .admin-form-group textarea::placeholder { color: #475569; }
    .admin-form-group select { cursor: pointer; }
    .admin-form-group select option { background: #1e293b; color: white; }
    .admin-form-group textarea { resize: vertical; min-height: 100px; }
    .admin-form-group .hint {
        display: block; font-size: 0.75rem; color: #64748b;
        margin-top: 0.4rem;
    }

    .form-actions {
        display: flex; justify-content: flex-end; gap: 1rem;
        margin-top: 2rem; padding-top: 1.5rem;
        border-top: 1px solid rgba(148, 163, 184, 0.1);
    }
    .btn-cancel {
        padding: 0.75rem 1.5rem; border-radius: 10px;
        background: rgba(51, 65, 85, 0.6); color: #94a3b8;
        border: 1px solid rgba(148, 163, 184, 0.15);
        font-family: var(--font-sans); font-weight: 600;
        text-decoration: none; font-size: 0.95rem;
        transition: all 200ms;
    }
    .btn-cancel:hover { background: rgba(51, 65, 85, 0.8); color: #e2e8f0; }

    .energy-range-display {
        display: flex; align-items: center; gap: 1rem;
    }
    .energy-range-display input[type="range"] {
        flex: 1; -webkit-appearance: none; appearance: none;
        height: 6px; border-radius: 5px; background: #1e293b;
        outline: none; border: none; padding: 0;
    }
    .energy-range-display input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none; appearance: none;
        width: 22px; height: 22px; border-radius: 50%;
        background: #3b82f6; cursor: pointer;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.4);
    }
    .energy-value {
        font-size: 1.25rem; font-weight: 700; color: #3b82f6;
        min-width: 30px; text-align: center;
    }

    .admin-form-error {
        color: #fca5a5; font-size: 0.8rem; margin-top: 0.4rem;
    }

    /* Image preview */
    .image-preview {
        margin-top: 0.75rem; border-radius: 10px; overflow: hidden;
        max-height: 200px; display: none;
        border: 1px solid rgba(148, 163, 184, 0.1);
    }
    .image-preview img {
        width: 100%; height: 200px; object-fit: cover;
    }

    @media (max-width: 768px) {
        .create-form-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="admin-wrapper relative min-h-screen overflow-hidden">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="admin-dashboard-container relative z-10">
        <header class="admin-header glass-panel">
            <div>
                <h1 class="admin-title">Add New Pet</h1>
                <p class="admin-subtitle">Fill in the details to add a new companion to the system.</p>
            </div>
            <a href="{{ route('admin.pets.index') }}" class="btn-cancel">← Back to Pets</a>
        </header>

        <div class="glass-panel" style="margin-top: 1.5rem;">
            {{-- Validation Errors --}}
            @if ($errors->any())
            <div style="padding: 1rem; border-radius: 10px; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #fca5a5; margin-bottom: 2rem; font-size: 0.9rem;">
                <strong>Please fix the following errors:</strong>
                <ul style="margin: 0.5rem 0 0 1.25rem; padding: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.pets.store') }}">
                @csrf

                <div class="create-form-grid">
                    {{-- Pet Name --}}
                    <div class="admin-form-group">
                        <label for="name">Pet Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Atlas" required>
                    </div>

                    {{-- Species --}}
                    <div class="admin-form-group">
                        <label for="species">Species *</label>
                        <select id="species" name="species" required>
                            <option value="">Select species...</option>
                            @foreach(['Dog', 'Cat', 'Rabbit', 'Hamster', 'Bird', 'Other'] as $species)
                            <option value="{{ $species }}" {{ old('species') == $species ? 'selected' : '' }}>{{ $species }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Breed --}}
                    <div class="admin-form-group">
                        <label for="breed">Breed *</label>
                        <input type="text" id="breed" name="breed" value="{{ old('breed') }}" placeholder="e.g. Golden Retriever" required>
                    </div>

                    {{-- Age --}}
                    <div class="admin-form-group">
                        <label for="age_months">Age (in months) *</label>
                        <input type="number" id="age_months" name="age_months" value="{{ old('age_months', 12) }}" min="0" max="360" required>
                        <span class="hint">e.g. 24 = 2 years</span>
                    </div>

                    {{-- Size --}}
                    <div class="admin-form-group">
                        <label for="size">Size *</label>
                        <select id="size" name="size" required>
                            <option value="">Select size...</option>
                            @foreach(['Small', 'Medium', 'Large', 'Extra Large'] as $size)
                            <option value="{{ $size }}" {{ old('size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Gender --}}
                    <div class="admin-form-group">
                        <label for="gender">Gender *</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select gender...</option>
                            @foreach(['Male', 'Female', 'Unknown'] as $gender)
                            <option value="{{ $gender }}" {{ old('gender') == $gender ? 'selected' : '' }}>{{ $gender }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Energy Level --}}
                    <div class="admin-form-group">
                        <label for="energy_level">Energy Level *</label>
                        <div class="energy-range-display">
                            <input type="range" id="energy_level" name="energy_level" min="0" max="10" value="{{ old('energy_level', 5) }}" oninput="document.getElementById('energyVal').textContent = this.value">
                            <span class="energy-value" id="energyVal">{{ old('energy_level', 5) }}</span>
                        </div>
                        <span class="hint">0 = Sedentary → 10 = Highly Active</span>
                    </div>

                    {{-- Health Status --}}
                    <div class="admin-form-group">
                        <label for="health_status">Health Status *</label>
                        <select id="health_status" name="health_status" required>
                            @foreach(['Excellent', 'Good', 'Fair', 'Poor', 'Medical Attention Required'] as $status)
                            <option value="{{ $status }}" {{ old('health_status', 'Good') == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Temperament --}}
                    <div class="admin-form-group full-width">
                        <label for="temperament">Temperament</label>
                        <input type="text" id="temperament" name="temperament" value="{{ old('temperament') }}" placeholder="e.g. Calm, Loyal, Sociable">
                        <span class="hint">Separate traits with commas</span>
                    </div>

                    {{-- Image URL --}}
                    <div class="admin-form-group full-width">
                        <label for="image_url">Image Filename or URL *</label>
                        <input type="text" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="e.g. my-pet.jpg or https://example.com/photo.jpg" required oninput="previewImage(this.value)">
                        <span class="hint">Enter a filename from /images/pets/ or a full URL</span>
                        <div class="image-preview" id="imagePreview">
                            <img id="previewImg" src="" alt="Preview">
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="admin-form-group full-width">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" placeholder="Write a compelling description of this pet..." required>{{ old('description') }}</textarea>
                    </div>

                    {{-- Medical Notes --}}
                    <div class="admin-form-group full-width">
                        <label for="medical_notes">Medical Notes (Optional)</label>
                        <textarea id="medical_notes" name="medical_notes" placeholder="Any medical history, vaccinations, or notes...">{{ old('medical_notes') }}</textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.pets.index') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-action">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Save Pet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(value) {
    const preview = document.getElementById('imagePreview');
    const img = document.getElementById('previewImg');
    if (value) {
        const src = value.startsWith('http') ? value : `/images/pets/${value}`;
        img.src = src;
        img.onerror = () => { preview.style.display = 'none'; };
        img.onload = () => { preview.style.display = 'block'; };
    } else {
        preview.style.display = 'none';
    }
}
</script>
@endsection
