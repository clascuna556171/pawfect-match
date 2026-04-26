@extends('app')

@section('title', 'PawfectMatch — Find Your Perfect Companion')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/modules.css') }}">
@endsection

@section('content')

{{-- ============================================
    SECTION 1: FULL-BLEED VIDEO HERO
    ============================================ --}}
<section class="hero" id="hero" aria-label="Welcome hero section with video background">
  {{-- Video Background --}}
  <div class="hero-video-wrapper">
    <video 
      class="hero-video"
      autoplay 
      muted 
      loop 
      playsinline
      poster="{{ asset('images/auth-pet.png') }}"
      aria-hidden="true"
    >
      <source src="{{ asset('videos/Animated_Dog_and_Cat_Playing.mp4') }}" type="video/mp4">
      {{-- Closed captions track for accessibility --}}
      <track 
        kind="captions" 
        src="{{ asset('videos/captions.vtt') }}" 
        srclang="en" 
        label="English captions"
        default
      >
    </video>
    <div class="hero-overlay" aria-hidden="true"></div>
  </div>

  <div class="hero-content hero-text-split">
    <div class="hero-left-col">
      <div class="reveal-mask">
        <div class="hero-accent-line reveal-text delay-1"></div>
      </div>
      <div class="reveal-mask">
        <span class="hero-tagline reveal-text delay-2">Premium Pet Adoption</span>
      </div>
      <h1 class="hero-title">
        <div class="reveal-mask"><span class="title-line1 reveal-text delay-3">Find Your</span></div>
        <div class="reveal-mask"><em class="title-line2 reveal-text delay-4">Perfect</em></div>
        <div class="reveal-mask"><em class="title-line3 reveal-text delay-5">Companion</em></div>
      </h1>
    </div>
    <div class="hero-right-col">
      <div class="reveal-mask">
        <p class="hero-subtitle reveal-text delay-6">
          Experience a refined approach to pet adoption. Connect with extraordinary companions waiting for their forever homes.
        </p>
      </div>
      <div class="hero-actions reveal-fade delay-7">
        <a href="{{ route('browse') }}" class="hero-btn-primary" data-magnetic aria-label="Browse available pets">
          <span class="btn-text">Find Your Companion</span>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
        <a href="#process" class="hero-btn-ghost" data-magnetic aria-label="Learn about our process">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
          <span class="btn-text">How It Works</span>
        </a>
      </div>
    </div>
  </div>

  {{-- Scroll Indicator --}}
  <div class="hero-scroll-indicator" aria-hidden="true">
    <span>Scroll</span>
    <div class="scroll-line"></div>
  </div>
</section>

{{-- ============================================
    SECTION 2: ANIMATED STATISTICS COUNTER
    ============================================ --}}
<section class="stats-section" id="stats" aria-label="Adoption statistics">
  <div class="stats-container">
    {{-- Stat: Successful Adoptions --}}
    <div class="stat-item fade-in-up">
      <div class="stat-icon" aria-hidden="true">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
        </svg>
      </div>
      <div class="stat-number">
        <span class="counter" data-target="{{ $stats['total_adoptions'] }}">0</span>
        <span class="stat-suffix">+</span>
      </div>
      <p class="stat-label">Successful Adoptions</p>
    </div>

    {{-- Stat: Available Pets --}}
    <div class="stat-item fade-in-up">
      <div class="stat-icon" aria-hidden="true">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="3"/>
          <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
        </svg>
      </div>
      <div class="stat-number">
        <span class="counter" data-target="{{ $stats['available_pets'] }}">0</span>
      </div>
      <p class="stat-label">Pets Available</p>
    </div>

    {{-- Stat: Happy Families --}}
    <div class="stat-item fade-in-up">
      <div class="stat-icon" aria-hidden="true">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
          <polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
      </div>
      <div class="stat-number">
        <span class="counter" data-target="{{ $stats['happy_families'] }}">0</span>
        <span class="stat-suffix">+</span>
      </div>
      <p class="stat-label">Happy Families</p>
    </div>
  </div>
</section>

{{-- ============================================
    SECTION 3: ADOPTION PROCESS SHOWCASE
    ============================================ --}}
<section class="process-section" id="process" aria-label="Our adoption process">
  <div class="process-header">
    <h2>Our Process</h2>
    <p>A thoughtfully streamlined approach to connecting you with your perfect companion.</p>
  </div>

  <div class="process-grid">
    {{-- Step 1: Discover --}}
    <article class="process-card fade-in-up" aria-label="Step 1: Discover available pets">
      <span class="process-step-number">01</span>
      <div class="process-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" role="img" aria-label="Magnifying glass icon representing browsing and discovering pets">
          <title>Discover - Browse available pets</title>
          <circle cx="11" cy="11" r="8"/>
          <path d="M21 21l-4.35-4.35"/>
        </svg>
      </div>
      <h3>Discover</h3>
      <p>Browse our curated collection of exceptional pets searching for a loving home. Filter by species, age, temperament, and more.</p>
    </article>

    {{-- Step 2: Connect --}}
    <article class="process-card fade-in-up" aria-label="Step 2: Connect through the application process">
      <span class="process-step-number">02</span>
      <div class="process-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" role="img" aria-label="Shield icon representing the trusted application process">
          <title>Connect - Submit your application</title>
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
        </svg>
      </div>
      <h3>Connect</h3>
      <p>Submit a personalized application and engage with our adoption specialists to find the best match for your lifestyle.</p>
    </article>

    {{-- Step 3: Welcome Home --}}
    <article class="process-card fade-in-up" aria-label="Step 3: Welcome your new pet home">
      <span class="process-step-number">03</span>
      <div class="process-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" role="img" aria-label="House with heart icon representing a successful adoption and new home">
          <title>Welcome Home - Complete adoption</title>
          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
          <path d="M12 13.5c-1.5-1.5-3-1-3 .5s1.5 2.5 3 4c1.5-1.5 3-2.5 3-4s-1.5-2-3-.5z"/>
        </svg>
      </div>
      <h3>Welcome Home</h3>
      <p>Complete the adoption process and begin your journey with your new family member. We support you every step of the way.</p>
    </article>
  </div>
</section>

{{-- ============================================
    SECTION 4: FEATURED COMPANIONS
    ============================================ --}}
<section class="featured-section" id="featured" aria-label="Featured pets available for adoption">
  <div class="featured-header">
    <div class="featured-header-text">
      <h2>Featured Companions</h2>
      <p>Meet our newest arrivals</p>
    </div>
    <a href="{{ route('browse') }}" class="featured-view-all">
      View All &rarr;
    </a>
  </div>

  @if($featuredPets->count() > 0)
    <div class="featured-grid">
      @foreach($featuredPets as $pet)
        <a href="{{ route('pets.show', $pet->id) }}" class="browse-card fade-in-up" aria-label="{{ $pet->name }} - {{ $pet->breed }}">
          <div class="card-image-wrapper">
            @if($pet->image_url)
              <img src="{{ asset('images/pets/' . $pet->image_url) }}" alt="Photo of {{ $pet->name }}" loading="lazy">
            @else
              <img src="{{ asset('images/auth-pet.png') }}" alt="Placeholder photo" loading="lazy">
            @endif
            
            <button class="card-fav-btn" aria-label="Add {{ $pet->name }} to favorites" onclick="event.preventDefault(); event.stopPropagation(); window.toggleFavorite({{ $pet->id }}, this);">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </button>

            <div class="card-overlay">
              <div class="card-energy">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                Energy Level: {{ $pet->energy_level ?? 6 }}/10
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
              <span class="status-badge status-available">
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
    </div>
  @else
    <div class="featured-empty">
      <div class="empty-icon">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
      </div>
      <p>No pets available at the moment. Check back soon!</p>
    </div>
  @endif
</section>

{{-- ============================================
    SECTION 5: TESTIMONIALS
    ============================================ --}}
<section class="testimonials-section" id="testimonials" aria-label="Success stories and testimonials">
  <div class="testimonials-header">
    <div class="testimonials-header-text">
      <h2>Success Stories</h2>
      <p>Hear from our happy families</p>
    </div>
    <div>
      <a href="{{ route('testimonials.create') }}" style="display: inline-block; padding: 0.8rem 1.8rem; background-color: transparent; color: var(--coral); border: 2px solid var(--coral); border-radius: 50px; font-family: var(--font-sans); font-weight: 600; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='var(--coral)'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--coral)';">
        Tell Us Your Story
      </a>
    </div>
  </div>

  @if(isset($testimonials) && $testimonials->count() > 0)
    <div class="testimonials-grid">
      @foreach($testimonials as $testimonial)
        <x-testimonial-card :testimonial="$testimonial" />
      @endforeach
    </div>
  @else
    <div class="testimonials-empty">
      <div class="empty-icon">
         <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
      </div>
      <p>More heartwarming stories coming soon!</p>
    </div>
  @endif
</section>

{{-- ============================================
    SECTION 6: BOTTOM CTA
    ============================================ --}}
<section class="cta-section" aria-label="Call to action">
  <div class="cta-content fade-in-up">
    <h2>Ready to Meet Your Match?</h2>
    <p>Every pet deserves a loving home. Start your search today and discover the joy of adoption.</p>
    <a href="{{ route('browse') }}" class="cta-btn" aria-label="Start searching for a pet to adopt">
      Start Your Search
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M5 12h14M12 5l7 7-7 7"/>
      </svg>
    </a>
  </div>
</section>

@endsection

@section('scripts')
<script src="{{ asset('js/home.js') }}"></script>
@endsection
