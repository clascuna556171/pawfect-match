<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $featuredPets = Pet::where('adoption_status', 'Available')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get()
            ->unique('image_url')
            ->take(10);

        $totalAdoptions = Pet::where('adoption_status', 'Adopted')->count();

        $stats = [
            'total_adoptions' => $totalAdoptions,
            'available_pets' => Pet::where('adoption_status', 'Available')->count(),
            'happy_families' => $totalAdoptions,
        ];

        $testimonials = Testimonial::with('pet')->where('is_approved', true)
            ->latest()
            ->take(3)
            ->get();

        return view('home', compact('featuredPets', 'stats', 'testimonials'));
    }
}