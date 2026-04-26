<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PetUpdateController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\ChatbotController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Resourceful Routes for Pets and Applications
Route::resource('pets', PetController::class)->only(['index', 'show']);

// Aliases for compatibility
Route::get('/browse', function () {
    return redirect()->route('pets.index');
})->name('browse');

// Public pages
Route::get('/about', [\App\Http\Controllers\PageController::class, 'about'])->name('about');
Route::get('/privacy', [\App\Http\Controllers\PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [\App\Http\Controllers\PageController::class, 'terms'])->name('terms');
Route::get('/faq', [\App\Http\Controllers\PageController::class, 'faq'])->name('faq');

// Contact
Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'show'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

// Chatbot
Route::post('/chatbot', [ChatbotController::class, 'respond'])->name('chatbot.respond');

// Testimonials (Public)
Route::get('/testimonials/create', [TestimonialController::class, 'create'])->name('testimonials.create');
Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
Route::get('/testimonials/{testimonial}', [TestimonialController::class, 'show'])->name('testimonials.show');

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public admin login entrypoint (bypasses admin route group middleware edge-cases)
Route::get('/admin-access', [AdminController::class, 'showLogin'])->name('admin.access');

// Donation flow
Route::get('/donate', [DonationController::class, 'create'])->name('donations.create');
Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');

// Auth Check (AJAX)
Route::get('/check-auth', function() {
    return response()->json(['authenticated' => auth()->check()]);
});

// Protected User Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/favorites/toggle/{petId}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorites/ids', [FavoriteController::class, 'ids'])->name('favorites.ids');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
    Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');

    // Adoption application flow
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/create', [ApplicationController::class, 'create'])->name('applications.create');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');

    // Post-Adoption Updates
    Route::get('/applications/{application}/update', [PetUpdateController::class, 'create'])->name('updates.create');
    Route::post('/applications/{application}/update', [PetUpdateController::class, 'store'])->name('updates.store');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminController::class, 'login'])->name('login.post');
    });
    
    // Protected Admin Dashboard Route Group
    Route::middleware(['auth:admin', 'role:admin,staff'])->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

        // Admin application review
        Route::get('/applications', [AdminController::class, 'applicationsIndex'])->name('applications.index');
        Route::get('/applications/{application}', [AdminController::class, 'applicationsShow'])->name('applications.show');
        Route::patch('/applications/{application}/status', [AdminController::class, 'applicationsUpdateStatus'])->name('applications.updateStatus');

        // Admin donations tracking
        Route::get('/donations', [AdminController::class, 'donationsIndex'])->name('donations.index');
        Route::patch('/donations/{donation}/status', [AdminController::class, 'donationsUpdateStatus'])->name('donations.updateStatus');
        
        // Admin testimonials tracking
        Route::get('/testimonials', [AdminController::class, 'testimonialsIndex'])->name('testimonials.index');
        Route::patch('/testimonials/{testimonial}/status', [AdminController::class, 'testimonialsUpdateStatus'])->name('testimonials.updateStatus');
        Route::delete('/testimonials/{testimonial}', [AdminController::class, 'testimonialsDestroy'])->name('testimonials.destroy');
        
        // Admin-only pet creation
        Route::middleware(['role:admin'])->group(function () {
            Route::get('/pets/create', [AdminController::class, 'create'])->name('pets.create');
            Route::post('/pets', [AdminController::class, 'store'])->name('pets.store');
            Route::delete('/pets/{pet}', [AdminController::class, 'destroy'])->name('pets.destroy');
        });

        // Admin/staff pet management routes (no create/store for staff)
        Route::get('/pets', [AdminController::class, 'index'])->name('pets.index');
        Route::get('/pets/{pet}', [AdminController::class, 'show'])->name('pets.show');
        Route::put('/pets/{pet}', [AdminController::class, 'update'])->name('pets.update');
        Route::put('/pets/{pet}/status', [AdminController::class, 'updateStatus'])->name('pets.status');
    });
});