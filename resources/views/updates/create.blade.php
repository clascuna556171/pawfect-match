@extends('app')

@section('title', 'Post-Adoption Update - ' . $application->pet->name)

@section('styles')
<style>
    .update-page {
        max-width: 800px;
        margin: 0 auto;
        padding: 7.5rem 1.5rem 3rem;
    }

    .update-shell {
        background: #ffffff;
        border: 1px solid rgba(26, 35, 50, 0.08);
        border-radius: 20px;
        box-shadow: 0 16px 30px rgba(26, 35, 50, 0.08);
        overflow: hidden;
    }

    .update-header {
        padding: 2.2rem;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-bottom: 1px solid rgba(26, 35, 50, 0.08);
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .update-header img {
        width: 110px;
        height: 110px;
        object-fit: cover;
        border-radius: 16px;
        box-shadow: 0 6px 14px rgba(0,0,0,0.06);
    }

    .update-header h1 {
        margin: 0 0 0.4rem;
        font-family: var(--font-serif);
        color: var(--navy);
        font-size: clamp(1.6rem, 2.8vw, 2.1rem);
    }

    .update-header p {
        margin: 0;
        color: #475569;
        line-height: 1.6;
    }

    .update-body {
        padding: 2.2rem;
        display: grid;
        gap: 1.8rem;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }

    .field label {
        font-weight: 600;
        color: #1e293b;
    }

    .field textarea, .field input[type="file"] {
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        padding: 0.85rem 1rem;
        font-size: 0.95rem;
        font-family: var(--font-sans);
        transition: all 200ms ease;
    }

    .field textarea {
        min-height: 160px;
        resize: vertical;
        line-height: 1.5;
    }

    .field textarea:focus, .field input[type="file"]:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }

    .error-text {
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 0.2rem;
    }

    .actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1rem;
        padding-top: 1.5rem;
        border-top: 1px solid #f1f5f9;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.8rem 1.7rem;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        color: #fff;
        font-weight: 700;
        background: linear-gradient(135deg, #6366f1, #818cf8);
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.25);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 26px rgba(99, 102, 241, 0.35);
    }

    .btn-cancel {
        text-decoration: none;
        color: #475569;
        font-weight: 600;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 0.8rem 1.6rem;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    @media (max-width: 640px) {
        .actions {
            flex-direction: column-reverse;
        }
        .actions a, .actions button {
            width: 100%;
            text-align: center;
        }
        .update-header {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
@endsection

@section('content')
<div class="update-page" data-aos="fade-up">
    <div class="update-shell">
        <div class="update-header">
            <img src="{{ $application->pet->image_url ? asset('images/pets/' . $application->pet->image_url) : asset('images/auth-pet.png') }}" alt="{{ $application->pet->name }}">
            <div>
                <h1>Update on {{ $application->pet->name }}</h1>
                <p>We absolutely love hearing how {{ $application->pet->name }} is doing! Share a message and a recent photo.</p>
            </div>
        </div>

        <form action="{{ route('updates.store', $application) }}" method="POST" enctype="multipart/form-data" class="update-body">
            @csrf

            <div class="field">
                <label for="status_message">How is everything going?</label>
                <textarea name="status_message" id="status_message" required placeholder="Share your favorite moments, how they are adjusting, new tricks they've learned, etc...">{{ old('status_message') }}</textarea>
                @error('status_message')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="field">
                <label for="image">Attach a Photo (Optional)</label>
                <input type="file" name="image" id="image" accept="image/*">
                @error('image')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div class="actions">
                <a href="{{ route('applications.show', $application) }}" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">Post Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
