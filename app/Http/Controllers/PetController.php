<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $curatedPetNames = [
            'Atlas',
            'Celeste',
            'Theodore',
            'Luna',
            'Winston',
            'Maximus',
            'Daisy',
            'Oliver',
            'Bella',
            'Apollo',
        ];

        $query = Pet::query();
        
        if ($request->filled('species')) {
            $species = collect(is_array($request->species) ? $request->species : explode(',', $request->species))
                ->map(fn ($item) => trim((string) $item))
                ->filter()
                ->values()
                ->all();

            // Treat "Other/Others" as the non-dog/cat bucket shown in UI.
            $selectedOtherBucket = collect($species)->contains(function ($item) {
                return in_array(strtolower(trim($item)), ['other', 'others'], true);
            });

            if ($selectedOtherBucket) {
                $species = array_values(array_unique(array_merge($species, ['Rabbit', 'Hamster', 'Bird', 'Other'])));
            }

            $species = array_values(array_intersect($species, Pet::speciesOptions()));

            if (!empty($species)) {
                $query->whereIn('species', $species);
            }
        }
        
        if ($request->filled('size')) {
            $sizes = collect(is_array($request->size) ? $request->size : explode(',', $request->size))
                ->map(fn ($item) => trim((string) $item))
                ->filter()
                ->values()
                ->all();

            $sizes = array_values(array_intersect($sizes, Pet::sizeOptions()));

            if (!empty($sizes)) {
                $query->whereIn('size', $sizes);
            }
        }
        
        if ($request->filled('age_months')) {
            $query->where('age_months', '<=', $request->age_months);
        }
        
        if ($request->filled('energy_level')) {
            $query->where('energy_level', '<=', $request->energy_level);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('breed', 'like', "%{$request->search}%");
            });
        }
        
        $availableQuery = (clone $query)->available();
        $totalResults = $availableQuery->count();

        $curatedSortSql = 'CASE WHEN name IN (' . implode(',', array_fill(0, count($curatedPetNames), '?')) . ') THEN 0 ELSE 1 END';

        $pets = $availableQuery
            ->orderByRaw($curatedSortSql, $curatedPetNames)
            ->orderBy('created_at', 'desc')
            ->paginate(6);
                     
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('pets.partials.pet-grid', compact('pets'))->render(),
                'next_page_url' => $pets->nextPageUrl(),
                'total' => $totalResults,
            ]);
        }
        
        return view('pets.index', compact('pets', 'totalResults'));
    }
    
    public function show($id)
    {
        $pet = Pet::findOrFail($id);
        
        $similarPets = Pet::where('species', $pet->species)
            ->where('id', '!=', $pet->id)
            ->available()
            ->limit(3)
            ->get();
        
        return view('pets.show', compact('pet', 'similarPets'));
    }
}