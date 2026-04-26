<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PawfectMatch - Premium Pet Adoption')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animations.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
    @if(!request()->is('admin*') && !request()->is('login') && !request()->is('register'))
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
    @endif
    
    @yield('styles')
</head>
<body>
    @php($adminUser = \Illuminate\Support\Facades\Auth::guard('admin')->user())
    <!-- Navigation -->
    @if(request()->is('admin*') && $adminUser && $adminUser->hasRole(['admin', 'staff']))
    <nav class="navbar" id="navbarAdmin">
        <div class="navbar-content container">
            <a href="{{ route('admin.dashboard') }}" class="navbar-logo">
                <img src="{{ asset('images/logo.png') }}" alt="PawfectMatch Logo">
                <span>
                    @if($adminUser->hasRole(['admin']))
                        Admin Portal
                    @else
                        Staff Portal
                    @endif
                </span>
            </a>
            <ul class="navbar-menu" id="navbarMenuAdmin">
                <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a></li>
                <li><a href="{{ route('admin.pets.index') }}" class="{{ request()->routeIs('admin.pets.*') ? 'active' : '' }}">Manage Pets</a></li>
                <li><a href="{{ route('admin.applications.index') }}" class="{{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">Applications</a></li>
                <li><a href="{{ route('admin.donations.index') }}" class="{{ request()->routeIs('admin.donations.*') ? 'active' : '' }}">Donations</a></li>
                <li><a href="{{ route('admin.testimonials.index') }}" class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">Testimonials</a></li>
                <li><a href="{{ route('home') }}" target="_blank" style="color: #64748b;">View Public Site ↗</a></li>
                <li class="mobile-auth-items">
                    <div class="mobile-auth-group">
                        <span class="mobile-user-info">{{ $adminUser->name }} ({{ ucfirst($adminUser->role) }})</span>
                        <button id="theme-toggle-mobile-admin" class="theme-toggle-btn" aria-label="Toggle dark mode"></button>
                        <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-outline">Logout</button>
                        </form>
                    </div>
                </li>
            </ul>
            <div class="navbar-auth">
                <span class="text-dark" style="margin-right: 1rem;">Logged in as {{ $adminUser->name }} ({{ ucfirst($adminUser->role) }})</span>
                <button id="theme-toggle" class="theme-toggle-btn" aria-label="Toggle dark mode"></button>
                <form action="{{ route('admin.logout') }}" method="POST" style="display: inline; margin-left: 0.5rem;">
                    @csrf
                    <button type="submit" class="btn-outline">Logout</button>
                </form>
            </div>
            <button class="mobile-menu-toggle" id="mobileToggleAdmin">☰</button>
        </div>
    </nav>
    @else
    <nav class="navbar" id="navbar">
        <div class="navbar-content container">
            <a href="{{ route('home') }}" class="navbar-logo">
                <img src="{{ asset('images/logo.png') }}" alt="PawfectMatch Logo">
                <span>PawfectMatch</span>
            </a>
            
            <ul class="navbar-menu" id="navbarMenu">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('browse') }}" class="{{ request()->routeIs('browse') ? 'active' : '' }}">Browse Pets</a></li>
                <li><a href="{{ route('donations.create') }}" class="{{ request()->routeIs('donations.*') ? 'active' : '' }}">Donate</a></li>
                @auth
                    <li><a href="{{ route('favorites') }}">Favorites</a></li>
                    <li><a href="{{ route('applications.index') }}" class="{{ request()->routeIs('applications.*') ? 'active' : '' }}">My Applications</a></li>
                    <li><a href="{{ route('donations.index') }}" class="{{ request()->routeIs('donations.index') ? 'active' : '' }}">My Donations</a></li>
                @endauth
                <li class="mobile-auth-items">
                    <div class="mobile-auth-group">
                        @guest
                            <a href="{{ route('login') }}" class="btn-outline">Sign In</a>
                            <a href="{{ route('register') }}" class="btn-primary">Get Started</a>
                        @else
                            <span class="mobile-user-info">Hi, {{ auth()->user()->name }}</span>
                            @if(auth()->user()->hasRole(['admin', 'staff']))
                                <a href="{{ route('admin.dashboard') }}" class="btn-secondary">Admin</a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-outline">Logout</button>
                            </form>
                        @endguest
                    </div>
                </li>
            </ul>
            
            <div class="navbar-auth">
                <button id="theme-toggle" class="theme-toggle-btn" aria-label="Toggle dark mode" style="margin-right: 0.5rem;"></button>
                @guest
                    <a href="{{ route('login') }}" class="btn-outline">Sign In</a>
                    <a href="{{ route('register') }}" class="btn-primary">Get Started</a>
                    <a href="{{ route('admin.access') }}" id="adminLoginLink" class="btn-outline btn-admin-link" aria-label="Open admin login">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Admin
                    </a>
                @else
                    <span style="margin-right: 1rem; color: var(--navy);">Hi, {{ auth()->user()->name }}</span>
                    @if(auth()->user()->hasRole(['admin', 'staff']))
                        <a href="{{ route('admin.dashboard') }}" class="btn-secondary">Admin</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-outline">Logout</button>
                    </form>
                @endguest
            </div>
            
            <button class="mobile-menu-toggle" id="mobileToggle">☰</button>
        </div>
    </nav>
    @endif
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- PawBot Chatbot (public pages only, not on auth pages) -->
    @if(!request()->is('admin*') && !request()->is('login') && !request()->is('register'))
        @include('components.chatbot')
    @endif

    <!-- Footer -->
    @if(!request()->is('admin*'))
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <img src="{{ asset('images/logo.png') }}" alt="PawfectMatch">
                        <span>PawfectMatch</span>
                    </div>
                    <p class="footer-description">
                        Experience a refined approach to pet adoption. Connect with extraordinary companions waiting for their forever homes.
                    </p>
                </div>
                
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <div class="footer-links">
                        <a href="{{ route('home') }}">Home</a>
                        <a href="{{ route('browse') }}">Browse Pets</a>
                        <a href="{{ route('donations.create') }}">Donate</a>
                        <a href="{{ route('login') }}">Sign In</a>
                        <a href="{{ route('register') }}">Register</a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Support</h4>
                    <div class="footer-links">
                        <a href="{{ route('about') }}">About Us</a>
                        <a href="{{ route('faq') }}">FAQs</a>
                        <a href="{{ route('privacy') }}">Privacy Policy</a>
                        <a href="{{ route('terms') }}">Terms of Service</a>
                        <a href="{{ route('contact') }}">Contact</a>
                    </div>
                </div>
                
                <div class="footer-section footer-contact">
                    <h4>Contact</h4>
                    <p>Email: hello@pawfectmatch.com</p>
                    <p>Phone: (555) 123-4567</p>
                    <p>Hours: Mon-Sat 9AM-6PM</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} PawfectMatch. All rights reserved.</p>
            </div>
        </div>
    </footer>
    @endif
    
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    
    <!-- Chart.js (for admin) -->
    @if(request()->is('admin*'))
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    @endif
    
    <!-- Custom JS -->
    <script src="{{ asset('js/theme.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @if(!request()->is('admin*') && !request()->is('login') && !request()->is('register'))
    <script src="{{ asset('js/chatbot.js') }}"></script>
    @endif
    
    @yield('scripts')
</body>
</html>