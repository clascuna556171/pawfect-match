@extends('app')

@section('title', 'My Favorites - PawfectMatch')

@section('styles')
<style>
    .favorites-wrap {
        max-width: 1100px;
        margin: 0 auto;
        padding: 7.5rem 1.5rem 3.5rem;
    }

    .favorites-head h1 {
        margin: 0;
        font-family: var(--font-serif);
        color: var(--navy);
        font-size: clamp(1.8rem, 3.4vw, 2.4rem);
    }

    .favorites-head p {
        margin: 0.4rem 0 0;
        color: #64748b;
    }

    .favorites-grid {
        margin-top: 1.5rem;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1rem;
    }

    .favorite-card {
        border: 1px solid rgba(26, 35, 50, 0.08);
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 10px 20px rgba(26, 35, 50, 0.08);
        display: flex;
        flex-direction: column;
    }

    .favorite-card img {
        width: 100%;
        aspect-ratio: 4 / 3;
        object-fit: cover;
    }

    .favorite-content {
        padding: 0.9rem;
        display: grid;
        gap: 0.5rem;
    }

    .favorite-content h2 {
        margin: 0;
        color: var(--navy);
        font-size: 1.12rem;
    }

    .favorite-meta {
        color: #64748b;
        font-size: 0.9rem;
    }

    .favorite-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5rem;
    }

    .pill {
        display: inline-flex;
        padding: 0.25rem 0.55rem;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 700;
        color: #1d4ed8;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
    }

    .btn-view {
        text-decoration: none;
        font-weight: 700;
        color: #fff;
        padding: 0.45rem 0.7rem;
        border-radius: 8px;
        background: linear-gradient(120deg, #ff6b6b, #ff8e8e);
    }

    .empty {
        margin-top: 1.5rem;
        border: 1px dashed #cbd5e1;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        background: #fff;
        color: #64748b;
    }

    .empty a {
        color: #1d4ed8;
        text-decoration: none;
        font-weight: 700;
    }
</style>
@endsection

@section('content')
<div class="favorites-wrap">
    <div class="favorites-head">
        <h1>My Favorite Pets</h1>
        <p>Quick access to pets you are most interested in adopting.</p>
    </div>

    @if($favorites->isEmpty())
        <div class="empty">
            <p>You have not favorited any pets yet.</p>
            <a href="{{ route('pets.index') }}">Browse pets</a>
        </div>
    @else
        <div class="favorites-grid">
            @foreach($favorites as $pet)
                <article class="favorite-card">
                    <img src="{{ $pet->image_url ? asset('images/pets/' . $pet->image_url) : asset('images/auth-pet.png') }}" alt="{{ $pet->name }}">

                    <div class="favorite-content">
                        <h2>{{ $pet->name }}</h2>
                        <div class="favorite-meta">{{ $pet->breed }} • {{ $pet->species }} • {{ $pet->size }}</div>

                        <div class="favorite-actions">
                            <span class="pill">{{ $pet->adoption_status }}</span>
                            <a href="{{ route('pets.show', $pet) }}" class="btn-view">View Pet</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
