@props(['testimonial'])

<a href="{{ route('testimonials.show', $testimonial) }}" class="testimonial-card fade-in-up" aria-label="Testimonial from {{ $testimonial->adopter_name }}" style="text-decoration: none; color: inherit;">
  <div class="testimonial-icon" aria-hidden="true">
    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
      <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
    </svg>
  </div>
  
  <p class="testimonial-text">"{{ $testimonial->story_text }}"</p>
  
  <div class="testimonial-author">
    <div class="testimonial-avatar">
      @if($testimonial->image_path)
        <img src="{{ asset('storage/' . $testimonial->image_path) }}" alt="{{ $testimonial->adopter_name }} and {{ $testimonial->pet_name }}" loading="lazy">
      @elseif($testimonial->pet && $testimonial->pet->image_url)
        <img src="{{ Str::startsWith($testimonial->pet->image_url, ['http://', 'https://']) ? $testimonial->pet->image_url : asset('images/pets/' . $testimonial->pet->image_url) }}" alt="{{ $testimonial->pet_name }}" loading="lazy">
      @else
        <img src="{{ asset('images/pets/default-dog-1.jpg') }}" alt="Happy Pet" loading="lazy">
      @endif
    </div>
    <div class="testimonial-info">
      <h4 class="testimonial-name">{{ $testimonial->adopter_name }}</h4>
      <p class="testimonial-pet">Adopted <strong>{{ $testimonial->pet_name }}</strong></p>
    </div>
  </div>
</a>
