@extends('app')

@section('title', 'Contact Us - PawfectMatch')

@section('styles')
<style>
    .contact-container {
        max-width: 700px;
        margin: 0 auto;
        background: var(--surface-color, #ffffff);
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid var(--border-color, #e2e8f0);
    }
    .dark-mode .contact-container {
        background: var(--surface-color, #1e293b);
        border-color: var(--border-color, #334155);
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--text-color, #1e293b);
    }
    .dark-mode .form-group label {
        color: #f1f5f9;
    }
    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--border-color, #cbd5e1);
        border-radius: 8px;
        background-color: var(--input-bg, #ffffff);
        color: var(--text-color, #1e293b);
        font-family: inherit;
        font-size: 1rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .dark-mode .form-control {
        border-color: var(--border-color, #475569);
        background-color: var(--input-bg, #0f172a);
        color: #f1f5f9;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--primary-color, #3b82f6);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }
    .text-danger {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 6px;
        display: block;
    }
    .alert-success {
        background-color: #dcfce7;
        color: #166534;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        border: 1px solid #bbf7d0;
    }
    .dark-mode .alert-success {
        background-color: rgba(22, 101, 52, 0.2);
        color: #4ade80;
        border-color: rgba(74, 222, 128, 0.3);
    }
    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    @media (max-width: 768px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }
        .contact-container {
            padding: 24px 20px;
        }
    }
</style>
@endsection

@section('content')
<section style="padding: 100px 0 50px; text-align: center;">
    <div class="container">
        <h1 data-aos="fade-up" style="font-family: 'Playfair Display', serif; font-size: 3rem; margin-bottom: 20px;">Contact Us</h1>
        <p data-aos="fade-up" data-aos-delay="100" style="max-width: 600px; margin: 0 auto; color: var(--text-muted); font-size: 1.1rem;">
            Whether you have a question about adoption or just want to say hi, we'd love to hear from you.
        </p>
    </div>
</section>

<section style="padding: 20px 0 100px;">
    <div class="container">
        <div class="contact-container" data-aos="fade-up">

            @if(session('success'))
                <div class="alert-success">
                    <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST">
                @csrf
                
                <div class="contact-grid">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" class="form-control" value="{{ old('subject') }}" required>
                    @error('subject')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" class="form-control" rows="5" required>{{ old('message') }}</textarea>
                    @error('message')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; font-size: 1.1rem; margin-top: 10px;">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
