@extends('app')

@section('title', 'Admin Login — PawfectMatch')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<style>
    /* Override auth styles for dark admin login */
    body:has(#admin-login-page) .navbar,
    body:has(#admin-login-page) .footer { display: none !important; }
    body:has(#admin-login-page) main { margin-top: 0 !important; padding-top: 0 !important; }

    #admin-login-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #0f172a;
        position: relative;
        overflow: hidden;
    }

    #admin-login-page .bg-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(120px);
        opacity: 0.12;
    }
    #admin-login-page .bg-orb-1 { width: 500px; height: 500px; background: #3b82f6; top: -150px; left: -100px; }
    #admin-login-page .bg-orb-2 { width: 400px; height: 400px; background: #6366f1; bottom: -120px; right: -80px; }

    #admin-login-page .admin-login-card {
        position: relative; z-index: 1;
        width: 100%; max-width: 420px;
        padding: 3rem;
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(148, 163, 184, 0.1);
        border-radius: 20px;
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.3);
        animation: cardFadeIn 600ms cubic-bezier(0.22, 1, 0.36, 1);
    }

    #admin-login-page .lock-icon {
        width: 56px; height: 56px;
        margin: 0 auto 1.5rem;
        display: flex; align-items: center; justify-content: center;
        background: rgba(59, 130, 246, 0.15);
        border: 1px solid rgba(59, 130, 246, 0.25);
        border-radius: 16px;
        color: #3b82f6;
        transition: all 400ms;
    }

    #admin-login-page .lock-icon.unlocked {
        background: rgba(52, 211, 153, 0.15);
        border-color: rgba(52, 211, 153, 0.25);
        color: #34d399;
    }

    #admin-login-page h1 {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem; color: #f1f5f9;
        text-align: center; margin-bottom: 0.25rem;
    }

    #admin-login-page .subtitle {
        text-align: center; color: #64748b;
        font-size: 0.9rem; margin-bottom: 2rem;
    }

    #admin-login-page .admin-alert {
        padding: 0.85rem 1rem; border-radius: 10px;
        font-size: 0.85rem; margin-bottom: 1.5rem;
        display: flex; align-items: center; gap: 0.6rem;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #fca5a5;
        animation: alertSlideIn 400ms cubic-bezier(0.22, 1, 0.36, 1);
    }

    #admin-login-page .input-group { margin-bottom: 1.25rem; }
    #admin-login-page .input-group label {
        display: block; font-size: 0.8rem; font-weight: 600;
        color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;
        margin-bottom: 0.5rem; font-family: 'Inter', sans-serif;
    }
    #admin-login-page .input-group input {
        width: 100%; padding: 0.85rem 1rem;
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(148, 163, 184, 0.15);
        border-radius: 10px; color: #e2e8f0;
        font-size: 0.95rem; font-family: 'Inter', sans-serif;
        transition: all 250ms; outline: none;
        box-sizing: border-box;
    }
    #admin-login-page .input-group input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
    #admin-login-page .input-group input::placeholder { color: #475569; }

    #admin-login-page .submit-btn {
        width: 100%; padding: 0.9rem;
        background: #3b82f6; color: white;
        border: none; border-radius: 10px;
        font-size: 1rem; font-weight: 600; font-family: 'Inter', sans-serif;
        cursor: pointer; transition: all 250ms;
        margin-top: 0.5rem;
    }
    #admin-login-page .submit-btn:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
    }
    #admin-login-page .submit-btn:active { transform: translateY(0); }

    #admin-login-page .back-link {
        display: block; text-align: center;
        margin-top: 1.75rem; color: #475569;
        font-size: 0.85rem; text-decoration: none;
        transition: color 200ms;
    }
    #admin-login-page .back-link:hover { color: #94a3b8; }

    @keyframes alertSlideIn {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@section('content')
<div id="admin-login-page">
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>

    <div class="admin-login-card">
        {{-- Lock Icon --}}
        <div class="lock-icon" id="lockIcon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
        </div>

        <h1>Admin Portal</h1>
        <p class="subtitle">Authorized personnel only</p>

        {{-- Error Messages --}}
        @if ($errors->any())
        <div class="admin-alert" role="alert">
            <span>⚠️</span>
            <div>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}" id="admin-login-form" autocomplete="off">
            @csrf

            <div class="input-group">
                <label for="admin-email">Email Address</label>
                <input type="email" id="admin-email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus autocomplete="new-email">
            </div>

            <div class="input-group">
                <label for="admin-password">Password</label>
                <input type="password" id="admin-password" name="password" placeholder="Enter your password" required autocomplete="new-password">
            </div>

            <button type="submit" class="submit-btn">Sign In</button>
        </form>

        <a href="{{ route('home') }}" class="back-link">← Back to PawfectMatch</a>
    </div>
</div>
@endsection
