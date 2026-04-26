@extends('app')

@section('title', 'Create Account — PawfectMatch')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<div class="auth-page" id="register-page">
    {{-- ======================== LEFT PANEL: Cinematic Pet Image ======================== --}}
    <div class="auth-image-panel">
        <img
            src="{{ asset('images/auth-pet.png') }}"
            alt="A heartwarming golden retriever puppy and tabby kitten cuddling together in warm sunlight"
            loading="eager"
        >
        <div class="auth-image-overlay"></div>

        {{-- Floating paw decorations --}}
        <span class="paw-float"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" opacity="0.6"><path d="M12 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-4.5-2c-.83 0-1.5.67-1.5 1.5S6.67 11 7.5 11 9 10.33 9 9.5 8.33 8 7.5 8zm9 0c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5S17.33 8 16.5 8zm-9-4C6.67 4 6 4.67 6 5.5S6.67 7 7.5 7 9 6.33 9 5.5 8.33 4 7.5 4zm9 0c-.83 0-1.5.67-1.5 1.5S15.67 7 16.5 7 18 6.33 18 5.5 17.33 4 16.5 4zM12 16c-2.21 0-4 1.34-4 3 0 .55.45 1 1 1h6c.55 0 1-.45 1-1 0-1.66-1.79-3-4-3z"/></svg></span>
        <span class="paw-float"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" opacity="0.6"><path d="M12 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-4.5-2c-.83 0-1.5.67-1.5 1.5S6.67 11 7.5 11 9 10.33 9 9.5 8.33 8 7.5 8zm9 0c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5S17.33 8 16.5 8zm-9-4C6.67 4 6 4.67 6 5.5S6.67 7 7.5 7 9 6.33 9 5.5 8.33 4 7.5 4zm9 0c-.83 0-1.5.67-1.5 1.5S15.67 7 16.5 7 18 6.33 18 5.5 17.33 4 16.5 4zM12 16c-2.21 0-4 1.34-4 3 0 .55.45 1 1 1h6c.55 0 1-.45 1-1 0-1.66-1.79-3-4-3z"/></svg></span>
        <span class="paw-float"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" opacity="0.6"><path d="M12 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-4.5-2c-.83 0-1.5.67-1.5 1.5S6.67 11 7.5 11 9 10.33 9 9.5 8.33 8 7.5 8zm9 0c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5S17.33 8 16.5 8zm-9-4C6.67 4 6 4.67 6 5.5S6.67 7 7.5 7 9 6.33 9 5.5 8.33 4 7.5 4zm9 0c-.83 0-1.5.67-1.5 1.5S15.67 7 16.5 7 18 6.33 18 5.5 17.33 4 16.5 4zM12 16c-2.21 0-4 1.34-4 3 0 .55.45 1 1 1h6c.55 0 1-.45 1-1 0-1.66-1.79-3-4-3z"/></svg></span>

        <div class="auth-image-content">
            <h2>Start Your Journey</h2>
            <p>Create an account to find your perfect furry companion and give them a forever home.</p>
        </div>
    </div>

    {{-- ======================== RIGHT PANEL: Glassmorphism Register Form ======================== --}}
    <div class="auth-form-panel">
        <div class="auth-glass-card">
            {{-- Logo --}}
            <div class="auth-logo">
                <img src="{{ asset('images/logo.png') }}" alt="PawfectMatch Logo">
                <span>PawfectMatch</span>
            </div>

            <h1 class="auth-heading">Create Account</h1>
            <p class="auth-subheading">Join PawfectMatch and begin your adoption journey today.</p>

            {{-- Error Messages --}}
            @if ($errors->any())
            <div class="auth-alert auth-alert-error" role="alert">
                <span class="auth-alert-icon">⚠️</span>
                <div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Register Form --}}
            <form method="POST" action="{{ route('register.post') }}" id="register-form" novalidate>
                @csrf

                {{-- Full Name --}}
                <div class="form-floating">
                    <input
                        type="text"
                        id="register-name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Full Name"
                        required
                        autofocus
                        autocomplete="name"
                        aria-label="Full name"
                        class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                    >
                    <label for="register-name">Full Name</label>
                </div>

                {{-- Email --}}
                <div class="form-floating">
                    <input
                        type="email"
                        id="register-email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Email Address"
                        required
                        autocomplete="email"
                        aria-label="Email address"
                        class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                    >
                    <label for="register-email">Email Address</label>
                </div>

                {{-- Password --}}
                <div class="form-floating">
                    <div class="password-wrapper">
                        <input
                            type="password"
                            id="register-password"
                            name="password"
                            placeholder="Password"
                            required
                            autocomplete="new-password"
                            aria-label="Password"
                            class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                        >
                        <label for="register-password">Password</label>
                        <button type="button" class="password-toggle" onclick="togglePassword('register-password', this)" aria-label="Show password" aria-pressed="false">
                            <svg class="icon-eye" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="icon-eye-off" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a21.77 21.77 0 0 1 5.06-5.94"></path>
                                <path d="M9.9 4.24A10.93 10.93 0 0 1 12 5c7 0 11 7 11 7a21.79 21.79 0 0 1-3.17 4.3"></path>
                                <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div class="form-floating">
                    <div class="password-wrapper">
                        <input
                            type="password"
                            id="register-password-confirm"
                            name="password_confirmation"
                            placeholder="Confirm Password"
                            required
                            autocomplete="new-password"
                            aria-label="Confirm password"
                            class="{{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                        >
                        <label for="register-password-confirm">Confirm Password</label>
                        <button type="button" class="password-toggle" onclick="togglePassword('register-password-confirm', this)" aria-label="Show password" aria-pressed="false">
                            <svg class="icon-eye" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="icon-eye-off" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a21.77 21.77 0 0 1 5.06-5.94"></path>
                                <path d="M9.9 4.24A10.93 10.93 0 0 1 12 5c7 0 11 7 11 7a21.79 21.79 0 0 1-3.17 4.3"></path>
                                <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="auth-submit" id="register-submit-btn">
                    Create Account →
                </button>
            </form>

            {{-- Divider --}}
            <div class="auth-divider">or</div>

            {{-- Switch to Login --}}
            <div class="auth-switch">
                Already have an account?
                <a href="{{ route('login') }}">Sign in here</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const showingPassword = input.type === 'password';

        input.type = showingPassword ? 'text' : 'password';
        btn.classList.toggle('is-visible', showingPassword);
        btn.setAttribute('aria-pressed', showingPassword ? 'true' : 'false');
        btn.setAttribute('aria-label', showingPassword ? 'Hide password' : 'Show password');
    }
</script>
@endsection
