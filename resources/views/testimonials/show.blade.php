@extends('app')

@section('title', 'Success Story: ' . $testimonial->pet_name . ' - PawfectMatch')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<style>
  .show-section { padding: 8rem 2rem 4rem; background: var(--cream); min-height: 80vh; display: flex; justify-content: center; align-items: center; }
  .show-container { max-width: 900px; width: 100%; background: white; border-radius: 20px; box-shadow: var(--shadow-xl); overflow: hidden; display: flex; flex-direction: column; }
  @media(min-width: 768px) { .show-container { flex-direction: row; } }
  .show-image { flex: 1; min-height: 300px; background: var(--gray-light); display: flex; justify-content: center; align-items: center; }
  .show-image img { width: 100%; height: 100%; object-fit: cover; }
  .show-content { flex: 1; padding: 4rem 3rem; display: flex; flex-direction: column; justify-content: center; }
  .show-quote { font-family: var(--font-sans); font-size: 1.25rem; color: var(--navy); line-height: 1.8; font-style: italic; margin-bottom: 2rem; }
  .show-author h3 { font-family: var(--font-serif); font-size: 1.8rem; color: var(--navy); margin-bottom: 0.2rem; }
  .show-author p { font-family: var(--font-sans); color: var(--gray); font-size: 1.1rem; }
  .show-author p strong { color: var(--coral); font-weight: 600; }
  .back-link { display: inline-block; margin-top: 3rem; color: var(--coral); text-decoration: none; font-weight: 600; font-family: var(--font-sans); transition: color 0.3s; }
  .back-link:hover { color: var(--coral-light); }
</style>
@endsection

@section('content')
<section class="show-section">
  <div class="show-container">
    <div class="show-image">
      @if($testimonial->image_path)
        <img src="{{ asset('storage/' . $testimonial->image_path) }}" alt="{{ $testimonial->adopter_name }}">
      @elseif($testimonial->pet && $testimonial->pet->image_url)
        <img src="{{ Str::startsWith($testimonial->pet->image_url, ['http://', 'https://']) ? $testimonial->pet->image_url : asset('images/pets/' . $testimonial->pet->image_url) }}" alt="{{ $testimonial->pet_name }}">
      @else
        <img src="{{ asset('images/pets/default-dog-1.jpg') }}" alt="Happy Pet">
      @endif
    </div>
    <div class="show-content">
      <div style="color: var(--coral-light); margin-bottom: 1.5rem; opacity: 0.4;">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
          <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
        </svg>
      </div>
      <div class="show-quote">"{{ $testimonial->story_text }}"</div>
      <div class="show-author">
        <h3>{{ $testimonial->adopter_name }}</h3>
        <p>Adopted <strong>{{ $testimonial->pet_name }}</strong></p>
      </div>
      <a href="{{ route('home') }}#testimonials" class="back-link">&larr; Back to all stories</a>
    </div>
  </div>
</section>
@endsection
