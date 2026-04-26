<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    protected $model = Pet::class;

    public function definition(): array
    {
        $species = $this->faker->randomElement(['Dog', 'Cat', 'Rabbit', 'Bird']);
        
        $lower_species = strtolower($species);

        return [
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'name' => $this->faker->firstName(),
            'breed' => $species === 'Dog' ? 'Mixed Breed' : 'Domestic',
            'species' => $species,
            'size' => $this->faker->randomElement(['Small', 'Medium', 'Large']),
            'age_months' => $this->faker->numberBetween(2, 60),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'energy_level' => $this->faker->numberBetween(1, 10),
            'health_status' => $this->faker->randomElement(['Excellent', 'Good']),
            'adoption_status' => $this->faker->randomElement(['Available', 'Available', 'Available', 'Pending', 'Adopted']),
            'description' => $this->faker->paragraph(3),
            'temperament' => $this->faker->randomElements(['Friendly', 'Playful', 'Calm', 'Alert', 'Curious', 'Shy'], 3),
            'dietary_requirements' => ['Standard diet'],
            'medical_notes' => 'Up to date with vaccinations.',
            'intake_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'image_url' => "default-{$lower_species}-1.jpg",
            'gallery' => ["default-{$lower_species}-2.jpg", "default-{$lower_species}-3.jpg"],
        ];
    }
}
