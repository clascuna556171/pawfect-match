<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function respond(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);

        $message = strtolower(trim($request->message));
        $query   = Pet::available();
        $matched = [];

        // --- Species ---
        $speciesMap = [
            'dog'     => 'Dog',
            'dogs'    => 'Dog',
            'puppy'   => 'Dog',
            'puppies' => 'Dog',
            'cat'     => 'Cat',
            'cats'    => 'Cat',
            'kitten'  => 'Cat',
            'kittens' => 'Cat',
            'rabbit'  => 'Rabbit',
            'rabbits' => 'Rabbit',
            'bunny'   => 'Rabbit',
            'bunnies' => 'Rabbit',
            'hamster' => 'Hamster',
            'hamsters'=> 'Hamster',
            'bird'    => 'Bird',
            'birds'   => 'Bird',
            'parrot'  => 'Bird',
            'parrots' => 'Bird',
        ];

        foreach ($speciesMap as $keyword => $species) {
            if (preg_match('/\b' . preg_quote($keyword, '/') . '\b/', $message)) {
                $query->where('species', $species);
                $matched[] = "species: {$species}";
                break;
            }
        }

        // --- Size ---
        $sizeMap = [
            'extra large' => 'Extra Large',
            'extra-large' => 'Extra Large',
            'very large'  => 'Extra Large',
            'xl'          => 'Extra Large',
            'large'       => 'Large',
            'big'         => 'Large',
            'medium'      => 'Medium',
            'mid-size'    => 'Medium',
            'small'       => 'Small',
            'tiny'        => 'Small',
            'little'      => 'Small',
            'mini'        => 'Small',
        ];

        foreach ($sizeMap as $keyword => $size) {
            if (preg_match('/\b' . preg_quote($keyword, '/') . '\b/', $message)) {
                $query->where('size', $size);
                $matched[] = "size: {$size}";
                break;
            }
        }

        // --- Energy level ---
        $energyKeywords = [
            'calm'        => ['op' => '<=', 'val' => 2],
            'chill'       => ['op' => '<=', 'val' => 2],
            'relaxed'     => ['op' => '<=', 'val' => 2],
            'lazy'        => ['op' => '<=', 'val' => 1],
            'low energy'  => ['op' => '<=', 'val' => 2],
            'not too energetic' => ['op' => '<=', 'val' => 3],
            'moderate'    => ['op' => '<=', 'val' => 3],
            'active'      => ['op' => '>=', 'val' => 4],
            'energetic'   => ['op' => '>=', 'val' => 4],
            'playful'     => ['op' => '>=', 'val' => 3],
            'high energy' => ['op' => '>=', 'val' => 4],
        ];

        foreach ($energyKeywords as $keyword => $filter) {
            if (str_contains($message, $keyword)) {
                $query->where('energy_level', $filter['op'], $filter['val']);
                $matched[] = "energy: {$keyword}";
                break;
            }
        }

        // --- Traits ---
        if (preg_match('/\b(kid|kids|child|children|family)\b/', $message)) {
            $query->where('good_with_kids', true);
            $matched[] = 'good with kids';
        }

        if (preg_match('/\bgood with (other )?pets\b/', $message) || preg_match('/\bfriendly with (other )?(pets|animals)\b/', $message)) {
            $query->where('good_with_pets', true);
            $matched[] = 'good with pets';
        }

        if (preg_match('/\bvaccinat/', $message)) {
            $query->where('vaccinated', true);
            $matched[] = 'vaccinated';
        }

        if (preg_match('/\b(neuter|spay)/', $message)) {
            $query->where('neutered', true);
            $matched[] = 'neutered/spayed';
        }

        // --- Gender ---
        if (preg_match('/\b(female|girl)\b/', $message)) {
            $query->where('gender', 'Female');
            $matched[] = 'gender: Female';
        } elseif (preg_match('/\b(male|boy)\b/', $message)) {
            $query->where('gender', 'Male');
            $matched[] = 'gender: Male';
        }

        // --- Breed (fuzzy match against DB) ---
        $breeds = Pet::select('breed')->distinct()->pluck('breed')->filter()->map(fn ($b) => strtolower($b));
        foreach ($breeds as $breed) {
            if (str_contains($message, $breed)) {
                $query->where('breed', 'like', "%{$breed}%");
                $matched[] = "breed: {$breed}";
                break;
            }
        }

        // --- Fallback: if nothing matched, do a LIKE search ---
        if (empty($matched)) {
            $cleaned = preg_replace('/[^a-z0-9\s]/', '', $message);
            $words   = array_filter(explode(' ', $cleaned), fn ($w) => strlen($w) > 2);
            $stopWords = ['the', 'any', 'are', 'there', 'have', 'you', 'can', 'want',
                          'need', 'looking', 'for', 'find', 'show', 'please', 'what',
                          'which', 'does', 'available', 'get', 'with', 'that', 'this',
                          'from', 'has', 'how', 'about', 'help', 'like', 'some'];
            $words = array_values(array_diff($words, $stopWords));

            if (!empty($words)) {
                $query->where(function ($q) use ($words) {
                    foreach ($words as $word) {
                        $q->orWhere('name', 'like', "%{$word}%")
                          ->orWhere('breed', 'like', "%{$word}%")
                          ->orWhere('description', 'like', "%{$word}%");
                    }
                });
                $matched[] = 'general search';
            }
        }

        $pets = $query->limit(5)->get();

        // --- Build reply ---
        if ($pets->isEmpty() && empty($matched)) {
            $reply = $this->getGreeting($message);
        } elseif ($pets->isEmpty()) {
            $reply = "I searched for pets matching **" . implode(', ', $matched) . "**, but I couldn't find any available right now. Try different keywords or browse all pets!";
        } else {
            $count = $pets->count();
            $label = $count === 1 ? '1 pet' : "{$count} pets";
            $reply = "I found **{$label}** matching your search! Here they are:";
        }

        return response()->json([
            'reply' => $reply,
            'pets'  => $pets->map(fn ($pet) => [
                'id'          => $pet->id,
                'name'        => $pet->name,
                'breed'       => $pet->breed,
                'species'     => $pet->species,
                'size'        => $pet->size,
                'description' => \Illuminate\Support\Str::limit($pet->description, 100),
                'image_url'   => $pet->image_url,
                'url'         => route('pets.show', $pet->id),
            ]),
        ]);
    }

    private function getGreeting(string $message): string
    {
        $greetings = ['hi', 'hello', 'hey', 'sup', 'yo', 'good morning', 'good afternoon', 'good evening'];
        foreach ($greetings as $g) {
            if (str_contains($message, $g)) {
                return "Hey there! 🐾 I'm **PawBot**, your pet adoption assistant. Try asking me things like:\n\n• \"Show me small dogs\"\n• \"Any cats good with kids?\"\n• \"I want a calm pet\"\n• \"Is there a Golden Retriever?\"";
            }
        }

        return "I'm **PawBot** 🐾 — I help you find pets! Try asking about a **breed**, **species** (dog, cat, rabbit…), **size** (small, large), or **traits** (good with kids, calm, energetic). For example: *\"Show me calm small dogs.\"*";
    }
}
