@if($pets->count() > 0)
  @foreach($pets as $pet)
    <a href="{{ route('pets.show', $pet->id) }}" class="browse-card fade-in-up" aria-label="View details for {{ $pet->name }}, a {{ $pet->breed }}">
      <div class="card-image-wrapper">
        @if($pet->image_url)
          <img src="{{ asset('images/pets/' . $pet->image_url) }}" alt="{{ $pet->name }}" loading="lazy">
        @else
          <img src="{{ asset('images/auth-pet.png') }}" alt="Placeholder for {{ $pet->name }}" loading="lazy">
        @endif
        
        {{-- Favorite Button --}}
        <button class="card-fav-btn" aria-label="Add {{ $pet->name }} to favorites" onclick="event.preventDefault(); event.stopPropagation(); window.toggleFavorite({{ $pet->id }}, this);">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </button>

        {{-- Hover overlay with energy + traits --}}
        <div class="card-overlay">
          <div class="card-energy">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
            Energy Level: {{ $pet->energy_level ?? 5 }}/10
          </div>
          @if($pet->temperament)
          <div class="card-trait-pills">
            @php
              $traits = is_array($pet->temperament) ? $pet->temperament : json_decode($pet->temperament, true) ?? [];
              if (!is_array($traits)) $traits = [];
            @endphp
            @foreach(array_slice($traits, 0, 3) as $tag)
              <span class="card-pill">{{ $tag }}</span>
            @endforeach
          </div>
          @endif
        </div>
      </div>
      
      <div class="card-content">
        <div class="card-header">
          <div>
            <h3 class="card-title">{{ $pet->name }}</h3>
            <p class="card-breed">{{ $pet->breed }}</p>
          </div>
          <span class="status-badge {{ strtolower($pet->adoption_status) == 'available' ? 'status-available' : 'status-pending' }}">
            {{ $pet->species }}
          </span>
        </div>
        
        <p class="card-desc">{{ Str::limit($pet->description, 110) }}</p>
        
        <div class="card-footer">
          <span class="card-age">{{ $pet->age_months < 12 ? $pet->age_months . ' months old' : intdiv($pet->age_months, 12) . ' years old' }}</span>
          <span class="view-details">
            Learn More &rarr;
          </span>
        </div>
      </div>
    </a>
  @endforeach
@else
  <div class="no-results">
    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="11" cy="11" r="8"/>
      <path d="M21 21l-4.35-4.35"/>
      <path d="M9 9l4 4m0-4l-4 4"/>
    </svg>
    <h3>No Pets Found</h3>
    <p>Try adjusting your filters or search terms.</p>
  </div>
@endif
