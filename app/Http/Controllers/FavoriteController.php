<?php

namespace App\Http\Controllers;

use App\Models\Pet;

class FavoriteController extends Controller
{
    public function toggle($petId)
    {
        $user = auth()->user();
        $isFavorited = $user->hasFavorited($petId);
        
        if ($isFavorited) {
            $user->favorites()->detach($petId);
            $favorited = false;
        } else {
            $user->favorites()->syncWithoutDetaching([$petId]);
            $favorited = true;
        }
        
        return response()->json([
            'success' => true,
            'favorited' => $favorited,
        ]);
    }
    
    public function index()
    {
        $favorites = auth()->user()->favorites()
            ->orderByPivot('created_at', 'desc')
            ->get();
        
        return view('favorites', compact('favorites'));
    }

    /**
     * Return the authenticated user's favorited pet IDs as JSON.
     * Used by the frontend to render filled hearts on page load.
     */
    public function ids()
    {
        $ids = auth()->user()->favorites()->pluck('pets.id');
        return response()->json(['ids' => $ids]);
    }
}