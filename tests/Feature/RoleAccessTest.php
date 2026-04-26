<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_standard_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        
        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        
        $response->assertRedirect(route('admin.login'));
    }

    public function test_standard_user_in_admin_guard_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user, 'admin')->get(route('admin.dashboard'));

        $response->assertForbidden();
    }

    public function test_staff_user_can_access_admin_dashboard(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        
        $response = $this->actingAs($staff, 'admin')->get(route('admin.dashboard'));
        
        $response->assertOk(); 
    }

    public function test_admin_user_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));
        
        $response->assertOk();
    }

    public function test_staff_user_cannot_access_add_new_pet_page(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($staff, 'admin')->get(route('admin.pets.create'));

        $response->assertForbidden();
    }

    public function test_staff_user_cannot_create_pet(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        Category::factory()->create(['name' => 'Dog']);

        $payload = [
            'name' => 'Test Pet',
            'species' => 'Dog',
            'breed' => 'Mixed Breed',
            'age_months' => 12,
            'size' => 'Medium',
            'gender' => 'Male',
            'description' => 'Friendly test pet profile.',
            'energy_level' => 5,
            'health_status' => 'Good',
            'image_url' => 'test.jpg',
        ];

        $response = $this->actingAs($staff, 'admin')->post(route('admin.pets.store'), $payload);

        $response->assertForbidden();

        $this->assertDatabaseMissing('pets', [
            'name' => 'Test Pet',
        ]);
    }

    public function test_admin_user_can_create_pet(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Category::factory()->create(['name' => 'Dog']);

        $payload = [
            'name' => 'Atlas Prime',
            'species' => 'Dog',
            'breed' => 'Retriever Mix',
            'age_months' => 24,
            'size' => 'Large',
            'gender' => 'Male',
            'description' => 'Calm and trainable companion.',
            'energy_level' => 6,
            'health_status' => 'Excellent',
            'image_url' => 'atlas-prime.jpg',
        ];

        $response = $this->actingAs($admin, 'admin')->post(route('admin.pets.store'), $payload);

        $response->assertRedirect(route('admin.pets.index'));

        $this->assertDatabaseHas('pets', [
            'name' => 'Atlas Prime',
            'species' => 'Dog',
        ]);
    }

    public function test_staff_user_can_access_admin_application_review_queue(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($staff, 'admin')->get(route('admin.applications.index'));

        $response->assertOk();
    }

    public function test_admin_update_pet_endpoint_returns_structured_validation_errors(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pet = \App\Models\Pet::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->putJson(route('admin.pets.update', $pet), [
                'energy_level' => 99,
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Validation failed.',
        ]);
        $response->assertJsonStructure([
            'success',
            'message',
            'errors' => ['energy_level'],
        ]);
    }

    public function test_non_admin_guard_user_cannot_update_pet_endpoint(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $pet = \App\Models\Pet::factory()->create();

        $response = $this->actingAs($user)
            ->putJson(route('admin.pets.update', $pet), [
                'name' => 'Blocked Attempt',
            ]);

        $response->assertUnauthorized();
    }

    public function test_admin_update_status_endpoint_returns_structured_validation_errors(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pet = \App\Models\Pet::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->putJson(route('admin.pets.status', $pet), [
                'status' => 'InvalidStatus',
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Validation failed.',
        ]);
        $response->assertJsonStructure([
            'success',
            'message',
            'errors' => ['status'],
        ]);
    }
}
