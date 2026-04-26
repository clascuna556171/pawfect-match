<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\User;
use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['Submitted', 'Submitted', 'Under Review', 'Approved', 'Rejected', 'Withdrawn']);
        $submittedAt = $this->faker->dateTimeBetween('-2 months', 'now');
        $isReviewed = in_array($status, ['Under Review', 'Approved', 'Rejected', 'Withdrawn'], true);

        return [
            'user_id' => User::factory(),
            'pet_id' => Pet::factory(),
            'status' => $status,
            'home_type' => $this->faker->randomElement(['House', 'Apartment', 'Townhouse']),
            'household_members' => $this->faker->numberBetween(1, 5),
            'has_other_pets' => $this->faker->boolean(30),
            'yard_available' => $this->faker->boolean(60),
            'experience_with_pets' => $this->faker->paragraph(),
            'reason_for_adoption' => $this->faker->paragraph(),
            'submitted_at' => $submittedAt,
            'reviewed_at' => $isReviewed ? $this->faker->dateTimeBetween($submittedAt, 'now') : null,
            'review_notes' => $isReviewed ? $this->faker->sentence() : null,
        ];
    }
}
