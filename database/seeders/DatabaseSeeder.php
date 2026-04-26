<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pet;
use App\Models\Category;
use App\Models\Application;
use App\Models\Donation;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Dog', 'Cat', 'Rabbit', 'Hamster', 'Bird', 'Other'];
        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['name' => $cat],
                ['description' => "All available {$cat}s for adoption."]
            );
        }

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@pawfectmatch.com'],
            ['name' => 'Admin', 'password' => Hash::make('admin123'), 'role' => 'admin']
        );

        $staffUser = User::firstOrCreate(
            ['email' => 'staff@pawfectmatch.com'],
            ['name' => 'Staff Member', 'password' => Hash::make('staff123'), 'role' => 'staff']
        );
        
        $defaultUser = User::firstOrCreate(
            ['email' => 'user@pawfectmatch.com'],
            ['name' => 'John Doe', 'password' => Hash::make('user123'), 'role' => 'user']
        );

        $demoAdopterA = User::firstOrCreate(
            ['email' => 'emma.adopter@pawfectmatch.com'],
            ['name' => 'Emma Harper', 'password' => Hash::make('demo1234'), 'role' => 'user']
        );

        $demoAdopterB = User::firstOrCreate(
            ['email' => 'liam.adopter@pawfectmatch.com'],
            ['name' => 'Liam Carter', 'password' => Hash::make('demo1234'), 'role' => 'user']
        );

        $demoAdopterC = User::firstOrCreate(
            ['email' => 'olivia.adopter@pawfectmatch.com'],
            ['name' => 'Olivia Reed', 'password' => Hash::make('demo1234'), 'role' => 'user']
        );
        
        $hardcodedPets = [
            [
                'name' => 'Atlas', 'species' => 'Dog', 'breed' => 'Golden Retriever', 'age_months' => 36, 'size' => 'Large', 'gender' => 'Male',
                'description' => 'Atlas is a distinguished Golden Retriever with a gentle soul and refined demeanor.',
                'temperament' => ['Calm', 'Loyal', 'Sociable'], 'energy_level' => 6, 'health_status' => 'Excellent',
                'image_url' => 'atlas-1.jpg', 'gallery' => ['atlas-2.jpg', 'atlas-3.jpg'], 'adoption_status' => 'Available',
            ],
            [
                'name' => 'Celeste', 'species' => 'Cat', 'breed' => 'Russian Blue', 'age_months' => 24, 'size' => 'Medium', 'gender' => 'Female',
                'description' => 'Celeste embodies elegance and grace. This refined Russian Blue possesses a quiet confidence.',
                'temperament' => ['Quiet', 'Independent', 'Refined'], 'energy_level' => 4, 'health_status' => 'Excellent',
                'image_url' => 'celeste-1.jpg', 'gallery' => ['celeste-2.jpg', 'celeste-3.jpg'], 'adoption_status' => 'Available',
            ],
            [
                'name' => 'Theodore', 'species' => 'Dog', 'breed' => 'French Bulldog', 'age_months' => 18, 'size' => 'Small', 'gender' => 'Male',
                'description' => 'Theodore is a charismatic French Bulldog with an endearing personality. Ideal for urban living.',
                'temperament' => ['Friendly', 'Alert', 'Easygoing'], 'energy_level' => 5, 'health_status' => 'Excellent',
                'image_url' => 'theodore-1.jpg', 'gallery' => ['theodore-2.jpg', 'theodore-3.jpg'], 'adoption_status' => 'Available',
            ],
            [
                'name' => 'Luna', 'species' => 'Cat', 'breed' => 'Siamese', 'age_months' => 15, 'size' => 'Small', 'gender' => 'Female',
                'description' => 'Luna is an enchanting Siamese kitten with striking features and an engaging personality.',
                'temperament' => ['Active', 'Affectionate', 'Curious'], 'energy_level' => 9, 'health_status' => 'Excellent',
                'image_url' => 'luna-1.jpg', 'gallery' => ['luna-2.jpg', 'luna-3.jpg'], 'adoption_status' => 'Available',
            ],
            [
                'name' => 'Winston', 'species' => 'Dog', 'breed' => 'Labrador Retriever', 'age_months' => 72, 'size' => 'Large', 'gender' => 'Male',
                'description' => 'Winston is a distinguished senior gentleman with years of love to give.',
                'temperament' => ['Wise', 'Gentle', 'Loyal'], 'energy_level' => 3, 'health_status' => 'Good',
                'image_url' => 'winston-1.jpg', 'gallery' => ['winston-2.jpg', 'winston-3.jpg'], 'adoption_status' => 'Available',
            ],
            [
                'name' => 'Maximus', 'species' => 'Dog', 'breed' => 'German Shepherd', 'age_months' => 48, 'size' => 'Large', 'gender' => 'Male',
                'description' => 'Maximus is an impressive German Shepherd with exceptional training.',
                'temperament' => ['Protective', 'Loyal', 'Confident'], 'energy_level' => 7, 'health_status' => 'Excellent',
                'image_url' => 'maximus-1.jpg', 'gallery' => ['maximus-2.jpg', 'maximus-3.jpg'], 'adoption_status' => 'Available',
            ],
            [
                'name' => 'Daisy', 'species' => 'Rabbit', 'breed' => 'Holland Lop', 'age_months' => 8, 'size' => 'Small', 'gender' => 'Female',
                'description' => 'Daisy is a cute, floppy-eared bunny who loves fresh veggies.',
                'temperament' => ['Shy', 'Sweet', 'Gentle'], 'energy_level' => 4, 'health_status' => 'Excellent',
                'image_url' => 'daisy-1.jpg', 'gallery' => ['daisy-2.jpg', 'daisy-3.jpg'], 'adoption_status' => 'Available',
            ],
            [
                'name' => 'Oliver', 'species' => 'Cat', 'breed' => 'Maine Coon', 'age_months' => 36, 'size' => 'Large', 'gender' => 'Male',
                'description' => 'Oliver is a large, incredibly fluffy and majestic Maine Coon.',
                'temperament' => ['Gentle', 'Playful', 'Vocal'], 'energy_level' => 5, 'health_status' => 'Excellent',
                'image_url' => 'oliver-1.jpg', 'gallery' => ['oliver-2.jpg', 'oliver-3.jpg'], 'adoption_status' => 'Available',
            ],
            [
                'name' => 'Bella', 'species' => 'Dog', 'breed' => 'Beagle', 'age_months' => 24, 'size' => 'Medium', 'gender' => 'Female',
                'description' => 'Bella is a sweet, curious Beagle with expressive eyes.',
                'temperament' => ['Curious', 'Friendly', 'Active'], 'energy_level' => 8, 'health_status' => 'Good',
                'image_url' => 'bella-1.jpg', 'gallery' => ['bella-2.jpg', 'bella-3.jpg'], 'adoption_status' => 'Available',
            ],
            [
                'name' => 'Apollo', 'species' => 'Bird', 'breed' => 'Cockatiel', 'age_months' => 12, 'size' => 'Small', 'gender' => 'Male',
                'description' => 'Apollo is a bright, colorful parrot who loves to whistle.',
                'temperament' => ['Vocal', 'Social', 'Smart'], 'energy_level' => 6, 'health_status' => 'Excellent',
                'image_url' => 'apollo-1.jpg', 'gallery' => ['apollo-2.jpg', 'apollo-3.jpg'], 'adoption_status' => 'Available',
            ]
        ];
        
        // Keep the curated set stable across seed runs.
        foreach ($hardcodedPets as $petData) {
            $catId = Category::where('name', $petData['species'])->first()->id ?? 1;
            $petData['category_id'] = $catId;
            Pet::updateOrCreate(
                ['name' => $petData['name']],
                $petData
            );
        }

        $curatedPetNames = array_column($hardcodedPets, 'name');
        $curatedPetIds = [];

        // Remove duplicates for curated pets and keep the latest record per name.
        foreach ($curatedPetNames as $name) {
            $matchingIds = Pet::where('name', $name)
                ->orderByDesc('id')
                ->pluck('id');

            if ($matchingIds->isEmpty()) {
                continue;
            }

            $keepId = $matchingIds->first();
            $curatedPetIds[] = $keepId;

            Pet::where('name', $name)
                ->where('id', '!=', $keepId)
                ->delete();
        }

        $targetRandomPets = 10; // 5 existing factory pets + 5 additional random pets
        $existingRandomCount = Pet::whereNotIn('id', $curatedPetIds)->count();

        if ($existingRandomCount < $targetRandomPets) {
            Pet::factory()->count($targetRandomPets - $existingRandomCount)->create();
        }

        $randomPetIdsToKeep = Pet::whereNotIn('id', $curatedPetIds)
            ->latest('id')
            ->take($targetRandomPets)
            ->pluck('id')
            ->all();

        // Final cleanup: keep only curated 10 + random 10 pets.
        Pet::whereNotIn('id', array_merge($curatedPetIds, $randomPetIdsToKeep))->delete();

        // Create richer demo states so dashboards and review queues have meaningful data.
        // Get 5 random non-curated pets for the various demo states
        $randomPetsForDemo = Pet::whereNotIn('id', $curatedPetIds)->take(5)->get();
        
        $atlasDemo = $randomPetsForDemo->get(0);
        $celesteDemo = $randomPetsForDemo->get(1);
        $winstonDemo = $randomPetsForDemo->get(2);
        $maximusDemo = $randomPetsForDemo->get(3);
        $bellaDemo = $randomPetsForDemo->get(4);

        if ($atlasDemo) {
            $atlasDemo->update(['adoption_status' => 'Available', 'adopted_date' => null]);
        }

        if ($celesteDemo) {
            $celesteDemo->update(['adoption_status' => 'Pending', 'adopted_date' => null]);
        }

        if ($winstonDemo) {
            $winstonDemo->update(['adoption_status' => 'Adopted', 'adopted_date' => now()->subDays(18)]);
        }

        if ($maximusDemo) {
            $maximusDemo->update(['adoption_status' => 'On Hold', 'adopted_date' => null]);
        }

        if ($bellaDemo) {
            $bellaDemo->update(['adoption_status' => 'Available', 'adopted_date' => null]);
        }

        if ($atlasDemo && $demoAdopterA) {
            Application::updateOrCreate(
                ['user_id' => $demoAdopterA->id, 'pet_id' => $atlasDemo->id],
                [
                    'status' => 'Submitted',
                    'home_type' => 'House',
                    'household_members' => 3,
                    'has_other_pets' => false,
                    'yard_available' => true,
                    'experience_with_pets' => 'Fostered two senior dogs and currently volunteer at a local rescue on weekends.',
                    'reason_for_adoption' => 'Looking for a calm companion and can provide long daily walks and stable routines.',
                    'submitted_at' => now()->subHours(20),
                    'reviewed_at' => null,
                    'review_notes' => null,
                ]
            );
        }

        if ($celesteDemo && $demoAdopterB) {
            Application::updateOrCreate(
                ['user_id' => $demoAdopterB->id, 'pet_id' => $celesteDemo->id],
                [
                    'status' => 'Under Review',
                    'home_type' => 'Apartment',
                    'household_members' => 2,
                    'has_other_pets' => true,
                    'other_pets_details' => 'One indoor adult cat with calm temperament.',
                    'yard_available' => false,
                    'experience_with_pets' => 'Five years owning cats and prior volunteer experience in kitten socialization.',
                    'reason_for_adoption' => 'Seeking a second cat to match our current cat’s social personality.',
                    'submitted_at' => now()->subDays(3),
                    'reviewed_at' => now()->subDays(2),
                    'review_notes' => 'Reference check in progress. Scheduling final interview.',
                ]
            );
        }

        if ($winstonDemo && $defaultUser) {
            Application::updateOrCreate(
                ['user_id' => $defaultUser->id, 'pet_id' => $winstonDemo->id],
                [
                    'status' => 'Approved',
                    'home_type' => 'House',
                    'household_members' => 4,
                    'has_other_pets' => false,
                    'yard_available' => true,
                    'experience_with_pets' => 'Lifelong dog owner with experience caring for senior pets.',
                    'reason_for_adoption' => 'Prepared for senior care needs and looking to adopt a mature companion.',
                    'submitted_at' => now()->subDays(26),
                    'reviewed_at' => now()->subDays(22),
                    'review_notes' => 'Approved and completed handover successfully.',
                ]
            );
        }

        if ($maximusDemo && $demoAdopterC) {
            Application::updateOrCreate(
                ['user_id' => $demoAdopterC->id, 'pet_id' => $maximusDemo->id],
                [
                    'status' => 'Rejected',
                    'home_type' => 'Townhouse',
                    'household_members' => 1,
                    'has_other_pets' => false,
                    'yard_available' => false,
                    'experience_with_pets' => 'Some prior pet ownership but no large breed handling experience.',
                    'reason_for_adoption' => 'Interested in adopting a loyal guard-type companion.',
                    'submitted_at' => now()->subDays(9),
                    'reviewed_at' => now()->subDays(7),
                    'review_notes' => 'Not enough large-breed experience for this profile.',
                ]
            );
        }

        if ($bellaDemo && $demoAdopterB) {
            Application::updateOrCreate(
                ['user_id' => $demoAdopterB->id, 'pet_id' => $bellaDemo->id],
                [
                    'status' => 'Withdrawn',
                    'home_type' => 'Condo',
                    'household_members' => 2,
                    'has_other_pets' => false,
                    'yard_available' => false,
                    'experience_with_pets' => 'Owned a Beagle previously and can provide regular exercise and enrichment.',
                    'reason_for_adoption' => 'Wanted an active companion but changed plans due to relocation.',
                    'submitted_at' => now()->subDays(6),
                    'reviewed_at' => now()->subDays(4),
                    'review_notes' => 'Applicant withdrew before final review.',
                ]
            );
        }

        if ($atlasDemo && $defaultUser) {
            Donation::updateOrCreate(
                ['donor_email' => $defaultUser->email, 'pet_id' => $atlasDemo->id, 'amount' => 120.00],
                [
                    'user_id' => $defaultUser->id,
                    'donor_name' => $defaultUser->name,
                    'currency' => 'USD',
                    'is_anonymous' => false,
                    'payment_method' => 'Card',
                    'status' => 'Confirmed',
                    'message' => 'For vet checkups and food support.',
                    'donated_at' => now()->subDays(8),
                ]
            );
        }

        if ($celesteDemo && $demoAdopterA) {
            Donation::updateOrCreate(
                ['donor_email' => $demoAdopterA->email, 'pet_id' => $celesteDemo->id, 'amount' => 75.00],
                [
                    'user_id' => $demoAdopterA->id,
                    'donor_name' => $demoAdopterA->name,
                    'currency' => 'USD',
                    'is_anonymous' => true,
                    'payment_method' => 'PayPal',
                    'status' => 'Confirmed',
                    'message' => 'Happy to help rescue operations.',
                    'donated_at' => now()->subDays(5),
                ]
            );
        }

        if ($winstonDemo && $demoAdopterB) {
            Donation::updateOrCreate(
                ['donor_email' => $demoAdopterB->email, 'pet_id' => $winstonDemo->id, 'amount' => 150.00],
                [
                    'user_id' => $demoAdopterB->id,
                    'donor_name' => $demoAdopterB->name,
                    'currency' => 'USD',
                    'is_anonymous' => false,
                    'payment_method' => 'Bank Transfer',
                    'status' => 'Pending',
                    'message' => 'Please allocate this for senior dog medication.',
                    'donated_at' => now()->subDays(3),
                ]
            );
        }

        Donation::updateOrCreate(
            ['donor_email' => 'community@supporters.org', 'pet_id' => null, 'amount' => 250.00],
            [
                'user_id' => null,
                'donor_name' => 'Community Donor Group',
                'currency' => 'USD',
                'is_anonymous' => false,
                'payment_method' => 'Manual',
                'status' => 'Confirmed',
                'message' => 'General fund for shelter maintenance and utilities.',
                'donated_at' => now()->subDays(2),
            ]
        );

        User::factory()->count(4)->create();
        Application::factory()
            ->count(10)
            ->recycle(User::inRandomOrder()->take(10)->get())
            ->recycle(Pet::inRandomOrder()->take(20)->get())
            ->create();

        // Seed 3 pre-built Testimonial stories
        if ($winstonDemo) {
            Testimonial::create([
                'adopter_name' => 'The Smith Family',
                'pet_name' => $winstonDemo->name,
                'story_text' => "Bringing {$winstonDemo->name} into our home has been the most wonderful experience. From the first day, they fit right in and brought so much joy and laughter into our living room. The shelter staff were amazing in matching us with the perfect personality!",
                'image_path' => null,
                'is_approved' => true,
            ]);
        }
        
        if ($celesteDemo) {
            Testimonial::create([
                'adopter_name' => $demoAdopterB->name,
                'pet_name' => $celesteDemo->name,
                'story_text' => "{$celesteDemo->name} has completely changed our lives for the better! Such a loving and energetic companion. Our weekend hikes are now our favorite time of the week. The PawfectMatch adoption process was incredibly smooth and we felt supported every step of the way.",
                'image_path' => null,
                'is_approved' => true,
            ]);
        }
        
        if ($atlasDemo) {
            Testimonial::create([
                'adopter_name' => 'Emily & Marcus Chen',
                'pet_name' => $atlasDemo->name,
                'story_text' => "We are so deeply grateful to PawfectMatch for helping us find {$atlasDemo->name}. We love our quiet evenings cuddled on the couch and the incredible bond we've built. Thank you for doing such a great job caring for these animals and making our family complete!",
                'image_path' => null,
                'is_approved' => true,
            ]);
        }
    }
}