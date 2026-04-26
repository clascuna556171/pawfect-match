@extends('app')

@section('title', 'Share Your Story - PawfectMatch')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<style>
  .form-section { padding: 8rem 2rem 4rem; background: var(--cream); min-height: 80vh; }
  .form-container { max-width: 600px; margin: 0 auto; background: white; padding: 3rem; border-radius: 20px; box-shadow: var(--shadow-xl); }
  .form-group { margin-bottom: 1.5rem; }
  .form-label { display: block; font-family: var(--font-sans); font-weight: 600; color: var(--navy); margin-bottom: 0.5rem; }
  .form-input, .form-textarea { width: 100%; padding: 0.8rem 1rem; border: 1px solid var(--gray-light); border-radius: 8px; font-family: var(--font-sans); }
  .form-textarea { min-height: 150px; resize: vertical; }
  .submit-btn { display: inline-block; padding: 1rem 2rem; background: var(--coral); color: white; font-weight: 600; border-radius: 50px; border: none; cursor: pointer; transition: background 0.3s; width: 100%; font-size: 1.1rem; }
  .submit-btn:hover { background: var(--coral-light); }
  .page-title { font-family: var(--font-serif); font-size: 2.5rem; color: var(--navy); margin-bottom: 1rem; text-align: center; }
</style>
@endsection

@section('content')
<section class="form-section">
  <div class="form-container">
    <h1 class="page-title">Share Your Story</h1>
    <p style="text-align: center; color: var(--gray); margin-bottom: 2rem;">Inspire others to adopt by sharing your journey with your new best friend.</p>
    
    <form action="{{ route('testimonials.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      
      <div class="form-group">
        <label class="form-label" for="adopter_name">Your Name</label>
        <input type="text" id="adopter_name" name="adopter_name" class="form-input" required maxlength="255">
      </div>

      <div class="form-group">
        <label class="form-label" for="pet_name">Pet's Name</label>
        <input type="text" id="pet_name" name="pet_name" class="form-input" required maxlength="255">
      </div>

      <div class="form-group">
        <label class="form-label" for="story_text">Your Story</label>
        <textarea id="story_text" name="story_text" class="form-textarea" required maxlength="1000" placeholder="Tell us about how your life has changed since adopting..."></textarea>
      </div>

      <div class="form-group">
        <label class="form-label" for="image">Photo (Optional)</label>
        <input type="file" id="image" name="image" class="form-input" accept="image/*">
        <small style="color: var(--gray); margin-top: 0.5rem; display: block;">Upload a picture of you and your pet.</small>
      </div>

      <div style="text-align: center; margin-top: 2rem;">
        <button type="submit" class="submit-btn" aria-label="Submit your testimonial">Submit Testimonial</button>
      </div>
    </form>
  </div>
</section>
@endsection
