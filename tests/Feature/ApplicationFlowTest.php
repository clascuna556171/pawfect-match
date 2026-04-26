<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_submit_adoption_application(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create(['adoption_status' => 'Available']);

        $payload = [
            'pet_id' => $pet->id,
            'home_type' => 'House',
            'household_members' => 3,
            'has_other_pets' => '1',
            'other_pets_details' => 'We currently have one calm senior cat that is well socialized and friendly around dogs.',
            'yard_available' => '1',
            'experience_with_pets' => 'I have cared for both dogs and cats for over ten years, including training, grooming, and daily exercise routines.',
            'reason_for_adoption' => 'Our family is ready to adopt and provide a stable, loving home with enough time, patience, and resources for long-term care.',
            'references' => 'Our veterinarian is City Vet Clinic, and we can also provide references from previous rescues.',
            'additional_information' => 'We work hybrid schedules so someone is almost always home during the day.',
        ];

        $response = $this->actingAs($user)->post(route('applications.store'), $payload);

        $response->assertRedirect(route('pets.show', $pet));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('applications', [
            'user_id' => $user->id,
            'pet_id' => $pet->id,
            'status' => 'Submitted',
            'home_type' => 'House',
            'household_members' => 3,
            'has_other_pets' => true,
            'yard_available' => true,
        ]);
    }

    public function test_duplicate_application_for_same_pet_is_blocked(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create(['adoption_status' => 'Available']);

        Application::factory()->create([
            'user_id' => $user->id,
            'pet_id' => $pet->id,
            'status' => 'Submitted',
        ]);

        $payload = [
            'pet_id' => $pet->id,
            'home_type' => 'Apartment',
            'household_members' => 2,
            'has_other_pets' => '0',
            'yard_available' => '0',
            'experience_with_pets' => 'I have several years of experience caring for small pets and following structured feeding and exercise schedules.',
            'reason_for_adoption' => 'I am ready to commit to adoption and provide a consistent routine and a safe environment for this pet.',
        ];

        $response = $this->actingAs($user)->post(route('applications.store'), $payload);

        $response->assertRedirect(route('pets.show', $pet));
        $response->assertSessionHas('error');

        $this->assertSame(1, Application::where('user_id', $user->id)->where('pet_id', $pet->id)->count());
    }

    public function test_user_can_view_only_their_applications(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $pet = Pet::factory()->create([
            'adoption_status' => 'Available',
            'name' => 'Owner Pet Name',
        ]);

        $ownerApplication = Application::factory()->create([
            'user_id' => $owner->id,
            'pet_id' => $pet->id,
            'status' => 'Under Review',
        ]);

        $otherApplication = Application::factory()->create([
            'user_id' => $otherUser->id,
            'pet_id' => Pet::factory()->create([
                'adoption_status' => 'Available',
                'name' => 'Other User Pet Name',
            ])->id,
            'status' => 'Submitted',
        ]);

        $indexResponse = $this->actingAs($owner)->get(route('applications.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSeeText('Owner Pet Name');
        $indexResponse->assertDontSeeText('Other User Pet Name');

        $showOwnResponse = $this->actingAs($owner)->get(route('applications.show', $ownerApplication));
        $showOwnResponse->assertOk();

        $showOtherResponse = $this->actingAs($owner)->get(route('applications.show', $otherApplication));
        $showOtherResponse->assertForbidden();
    }

    public function test_authenticated_user_can_fetch_modal_application_form_partial(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create(['adoption_status' => 'Available']);

        $response = $this->actingAs($user)
            ->get(route('applications.create', ['pet_id' => $pet->id, 'partial' => 1]));

        $response->assertOk();
        $response->assertSee('modal-application-form', false);
    }

    public function test_ajax_application_submission_returns_structured_validation_errors(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create(['adoption_status' => 'Available']);

        $payload = [
            'pet_id' => $pet->id,
            'home_type' => '',
            'household_members' => 0,
            'has_other_pets' => '0',
            'yard_available' => '0',
            'experience_with_pets' => 'too short',
            'reason_for_adoption' => 'short',
        ];

        $response = $this->actingAs($user)
            ->postJson(route('applications.store'), $payload);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'home_type',
                'household_members',
                'experience_with_pets',
                'reason_for_adoption',
            ],
        ]);
    }

    public function test_ajax_application_submission_returns_success_payload(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create(['adoption_status' => 'Available']);

        $payload = [
            'pet_id' => $pet->id,
            'home_type' => 'House',
            'household_members' => 4,
            'has_other_pets' => '0',
            'yard_available' => '1',
            'experience_with_pets' => 'I have cared for pets for years and can provide structured daily care routines and training support.',
            'reason_for_adoption' => 'Our household is stable and prepared to provide a long-term, loving environment and proper veterinary care.',
            'references' => 'Vet reference available upon request.',
            'additional_information' => 'Flexible work schedule allows regular supervision.',
        ];

        $response = $this->actingAs($user)
            ->postJson(route('applications.store'), $payload);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'message',
            'redirect_url',
        ]);
    }
}
