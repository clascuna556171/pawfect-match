<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\PetUpdate;

class PetUpdateController extends Controller
{
    public function create(Application $application)
    {
        if ($application->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('updates.create', compact('application'));
    }

    public function store(Request $request, Application $application)
    {
        if ($application->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($application->status !== 'Approved') {
            abort(403, 'Updates can only be posted for approved applications.');
        }

        $request->validate([
            'status_message' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('updates', 'public');
        }

        PetUpdate::create([
            'adoption_id' => $application->id,
            'status_message' => $request->status_message,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('applications.show', $application)
                         ->with('success', 'Thank you! Your post-adoption update has been saved.');
    }
}
