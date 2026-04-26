<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function create()
    {
        return view('testimonials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'adopter_name' => 'required|string|max:255',
            'pet_name' => 'required|string|max:255',
            'story_text' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $testimonial = new Testimonial();
        $testimonial->adopter_name = $validated['adopter_name'];
        $testimonial->pet_name = $validated['pet_name'];
        $testimonial->story_text = $validated['story_text'];
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('testimonials', 'public');
            $testimonial->image_path = $path;
        }

        $testimonial->is_approved = false;
        $testimonial->save();

        return redirect()->route('home')->with('success', 'Thank you for sharing your story! It has been submitted for review.');
    }

    public function show(Testimonial $testimonial)
    {
        if (!$testimonial->is_approved && !auth('admin')->check()) {
            abort(404);
        }

        return view('testimonials.show', compact('testimonial'));
    }
}

