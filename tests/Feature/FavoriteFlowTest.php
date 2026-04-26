<?php

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_favorites_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('favorites'));

        $response->assertOk();
        $response->assertSeeText('My Favorite Pets');
    }

    public function test_user_can_toggle_favorite_for_pet(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create(['adoption_status' => 'Available']);

        $firstResponse = $this->actingAs($user)
            ->postJson(route('favorites.toggle', ['petId' => $pet->id]));

        $firstResponse->assertOk();
        $firstResponse->assertJson([
            'success' => true,
            'favorited' => true,
        ]);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'pet_id' => $pet->id,
        ]);

        $secondResponse = $this->actingAs($user)
            ->postJson(route('favorites.toggle', ['petId' => $pet->id]));

        $secondResponse->assertOk();
        $secondResponse->assertJson([
            'success' => true,
            'favorited' => false,
        ]);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'pet_id' => $pet->id,
        ]);
    }
}
