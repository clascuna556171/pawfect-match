@extends('app')

@section('title', 'About Us - PawfectMatch')

@section('content')
<section class="page-header" style="padding: 100px 0 50px; text-align: center;">
    <div class="container">
        <h1 data-aos="fade-up" style="font-family: 'Playfair Display', serif; font-size: 3rem; margin-bottom: 20px;">About PawfectMatch</h1>
        <p data-aos="fade-up" data-aos-delay="100" style="max-width: 600px; margin: 0 auto; color: var(--text-muted); font-size: 1.1rem; line-height: 1.6;">
            We believe every pet deserves a loving home, and every home deserves the unconditional love of a pet.
        </p>
    </div>
</section>

<section class="about-content" style="padding: 50px 0 100px;">
    <div class="container">
        <div class="grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center;">
            <div data-aos="fade-right">
                <h2 style="font-family: 'Playfair Display', serif; font-size: 2.2rem; margin-bottom: 20px;">Our Mission</h2>
                <p style="margin-bottom: 15px; color: var(--text-color); line-height: 1.7;">
                    PawfectMatch was founded with a singular, passionate goal: to transform the pet adoption experience. We bridge the gap between extraordinary animals in need and compassionate individuals ready to provide a forever home.
                </p>
                <p style="color: var(--text-color); line-height: 1.7;">
                    By leveraging technology and an editorial approach, we bring the unique personalities of our adoptable pets to light, helping you find a companion that perfectly matches your lifestyle.
                </p>
            </div>
            <div data-aos="fade-left" style="border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                <img src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Happy dogs" style="width: 100%; height: auto; display: block; filter: brightness(0.9);">
            </div>
        </div>
        
        <div style="margin-top: 100px; text-align: center;" data-aos="fade-up">
            <h2 style="font-family: 'Playfair Display', serif; font-size: 2.2rem; margin-bottom: 20px;">Join Our Community</h2>
            <p style="max-width: 600px; margin: 0 auto 30px; color: var(--text-muted);">
                Ready to make a difference in a pet's life? Whether you're looking to adopt, foster, or donate, your support means the world to us and them.
            </p>
            <a href="{{ route('browse') }}" class="btn-primary" style="padding: 12px 30px; font-size: 1.1rem;">Browse Pets</a>
            <a href="{{ route('donations.create') }}" class="btn-outline" style="padding: 12px 30px; font-size: 1.1rem; margin-left: 10px;">Donate Now</a>
        </div>
    </div>
</section>
@endsection
