@extends('app')

@section('title', 'Adoption Application - ' . $pet->name)

@section('styles')
<link rel="stylesheet" href="{{ asset('css/modules.css') }}">
<style>
    .application-page {
        max-width: 980px;
        margin: 0 auto;
        padding: 6rem 1.5rem 3rem;
        min-height: calc(100vh - 80px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
    }


    .application-shell {
        background: #ffffff;
        border: 1px solid rgba(26, 35, 50, 0.08);
        border-radius: 20px;
        box-shadow: 0 16px 30px rgba(26, 35, 50, 0.08);
        overflow: hidden;
        width: 100%;
    }


    .application-header {
        padding: 2rem;
        background: linear-gradient(120deg, #fff7f5, #f5fffd);
        border-bottom: 1px solid rgba(26, 35, 50, 0.08);
        display: grid;
        grid-template-columns: 120px 1fr;
        gap: 1.25rem;
        align-items: center;
    }

    .application-header img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 16px;
    }

    .application-header h1 {
        margin: 0 0 0.35rem;
        font-family: var(--font-serif);
        color: var(--navy);
        font-size: clamp(1.7rem, 3.2vw, 2.2rem);
    }

    .application-header p {
        margin: 0;
        color: #475569;
        line-height: 1.6;
    }

    .application-body {
        padding: 2rem;
        display: grid;
        gap: 1.75rem;
    }

    .application-section {
        border: 1px solid rgba(26, 35, 50, 0.08);
        border-radius: 16px;
        padding: 1.25rem;
        background: #fff;
    }

    .application-section h2 {
        margin: 0 0 1rem;
        color: var(--navy);
        font-family: var(--font-serif);
        font-size: 1.2rem;
    }

    .application-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }

    .field.full {
        grid-column: 1 / -1;
    }

    .field label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1f2937;
    }

    .field input,
    .field select,
    .field textarea {
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 0.75rem 0.85rem;
        font-size: 0.95rem;
        font-family: var(--font-sans);
        transition: border-color 180ms, box-shadow 180ms;
    }

    .field textarea {
        min-height: 130px;
        resize: vertical;
    }

    .field input:focus,
    .field select:focus,
    .field textarea:focus {
        outline: none;
        border-color: var(--coral);
        box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.16);
    }

    .radio-set {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .radio-set label {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-weight: 500;
        color: #334155;
    }

    .radio-set input[type="radio"] {
        width: 16px;
        height: 16px;
        accent-color: var(--coral);
    }

    .hint {
        color: #64748b;
        font-size: 0.84rem;
    }

    .error-text {
        color: #b91c1c;
        font-size: 0.84rem;
    }

    .error-box {
        padding: 0.95rem 1rem;
        border-radius: 12px;
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #991b1b;
        line-height: 1.5;
    }

    .application-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.8rem;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.15rem;
        border-radius: 10px;
        text-decoration: none;
        border: 1px solid #cbd5e1;
        color: #334155;
        font-weight: 600;
        background: #fff;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.2rem;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        color: #fff;
        font-weight: 700;
        background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
        box-shadow: 0 10px 18px rgba(255, 107, 107, 0.25);
    }

    @media (max-width: 768px) {
        .application-header {
            grid-template-columns: 1fr;
        }

        .application-header img {
            width: 100%;
            height: 220px;
        }

        .application-grid {
            grid-template-columns: 1fr;
        }

        .application-actions {
            flex-direction: column-reverse;
        }

        .btn-back,
        .btn-submit {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="application-page">
    <div class="application-shell">
        <div class="application-header">
            <img src="{{ $pet->image_url ? asset('images/pets/' . $pet->image_url) : asset('images/auth-pet.png') }}" alt="{{ $pet->name }}">
            <div>
                <h1>Apply to Adopt {{ $pet->name }}</h1>
                <p>You are applying for a {{ $pet->breed }}. Please complete the form with as much detail as possible so the team can review your application accurately.</p>
            </div>
        </div>

        <form action="{{ route('applications.store') }}" method="POST" class="application-body">
            @csrf
            <input type="hidden" name="pet_id" value="{{ $pet->id }}">

            @if($errors->any())
                <div class="error-box">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <section class="application-section">
                <h2>Home Information</h2>
                <div class="application-grid">
                    <div class="field">
                        <label for="home_type">Type of home</label>
                        <select id="home_type" name="home_type" required>
                            <option value="">Select your home type</option>
                            @foreach(['House', 'Apartment', 'Condo', 'Townhouse', 'Farm', 'Other'] as $homeType)
                                <option value="{{ $homeType }}" {{ old('home_type') === $homeType ? 'selected' : '' }}>{{ $homeType }}</option>
                            @endforeach
                        </select>
                        @error('home_type')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <label for="household_members">Number of household members</label>
                        <input id="household_members" type="number" name="household_members" min="1" max="30" value="{{ old('household_members') }}" required>
                        @error('household_members')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="field full">
                        <label>Is a yard available?</label>
                        <div class="radio-set">
                            <label><input type="radio" name="yard_available" value="1" {{ old('yard_available') === '1' ? 'checked' : '' }} required> Yes</label>
                            <label><input type="radio" name="yard_available" value="0" {{ old('yard_available') === '0' ? 'checked' : '' }} required> No</label>
                        </div>
                        @error('yard_available')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                </div>
            </section>

            <section class="application-section">
                <h2>Current Pets</h2>
                <div class="application-grid">
                    <div class="field full">
                        <label>Do you currently have other pets?</label>
                        <div class="radio-set">
                            <label><input type="radio" name="has_other_pets" value="1" {{ old('has_other_pets') === '1' ? 'checked' : '' }} required> Yes</label>
                            <label><input type="radio" name="has_other_pets" value="0" {{ old('has_other_pets') === '0' ? 'checked' : '' }} required> No</label>
                        </div>
                        @error('has_other_pets')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="field full">
                        <label for="other_pets_details">If yes, please describe them</label>
                        <textarea id="other_pets_details" name="other_pets_details" placeholder="Species, age, temperament, and how they interact with other animals...">{{ old('other_pets_details') }}</textarea>
                        <span class="hint">Required if you selected "Yes" above.</span>
                        @error('other_pets_details')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                </div>
            </section>

            <section class="application-section">
                <h2>Adoption Profile</h2>
                <div class="application-grid">
                    <div class="field full">
                        <label for="experience_with_pets">Your experience with pets</label>
                        <textarea id="experience_with_pets" name="experience_with_pets" required>{{ old('experience_with_pets') }}</textarea>
                        <span class="hint">Share your history caring for pets, training approach, and daily routine.</span>
                        @error('experience_with_pets')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="field full">
                        <label for="reason_for_adoption">Reason for adoption</label>
                        <textarea id="reason_for_adoption" name="reason_for_adoption" required>{{ old('reason_for_adoption') }}</textarea>
                        <span class="hint">Tell us why this pet is a good fit for your home and lifestyle.</span>
                        @error('reason_for_adoption')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="field full">
                        <label for="references">References (optional)</label>
                        <textarea id="references" name="references" placeholder="Vet details, previous rescue references, or personal references.">{{ old('references') }}</textarea>
                        @error('references')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="field full">
                        <label for="additional_information">Additional information (optional)</label>
                        <textarea id="additional_information" name="additional_information" placeholder="Anything else you want the team to know.">{{ old('additional_information') }}</textarea>
                        @error('additional_information')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                </div>
            </section>

            <div class="application-actions">
                <a class="btn-back" href="{{ route('pets.show', $pet) }}">Back to pet details</a>
                <button type="submit" class="btn-submit">Submit Application</button>
            </div>
        </form>
    </div>
</div>
@endsection
