<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminApplicationReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_application_review_queue_and_detail(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $application = Application::factory()->create();

        $queueResponse = $this->actingAs($admin, 'admin')->get(route('admin.applications.index'));
        $queueResponse->assertOk();
        $queueResponse->assertSeeText('Application Review Queue');

        $detailResponse = $this->actingAs($admin, 'admin')->get(route('admin.applications.show', $application));
        $detailResponse->assertOk();
        $detailResponse->assertSeeText('Application #' . $application->id);
    }

    public function test_admin_can_update_application_status_with_valid_transition(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pet = Pet::factory()->create(['adoption_status' => 'Available']);
        $application = Application::factory()->create([
            'pet_id' => $pet->id,
            'status' => 'Submitted',
        ]);

        $response = $this->actingAs($admin, 'admin')->patch(route('admin.applications.updateStatus', $application), [
            'status' => 'Approved',
            'review_notes' => 'Applicant meets all criteria and references checked out.',
        ]);

        $response->assertRedirect(route('admin.applications.show', $application));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'Approved',
        ]);

        $this->assertDatabaseHas('pets', [
            'id' => $pet->id,
            'adoption_status' => 'Pending',
        ]);
    }

    public function test_invalid_status_transition_is_blocked(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $application = Application::factory()->create(['status' => 'Approved']);

        $response = $this->actingAs($admin, 'admin')->from(route('admin.applications.show', $application))
            ->patch(route('admin.applications.updateStatus', $application), [
                'status' => 'Submitted',
                'review_notes' => 'Attempting invalid transition.',
            ]);

        $response->assertRedirect(route('admin.applications.show', $application));
        $response->assertSessionHasErrors('status');

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'Approved',
        ]);
    }

    public function test_application_lifecycle_updates_pet_status_across_review_flow(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pet = Pet::factory()->create([
            'adoption_status' => 'Available',
        ]);

        $application = Application::factory()->create([
            'pet_id' => $pet->id,
            'status' => 'Submitted',
        ]);

        $this->actingAs($admin, 'admin')->patch(route('admin.applications.updateStatus', $application), [
            'status' => 'Under Review',
            'review_notes' => 'Initial screening complete.',
        ])->assertRedirect(route('admin.applications.show', $application));

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'Under Review',
        ]);

        $this->assertDatabaseHas('pets', [
            'id' => $pet->id,
            'adoption_status' => 'Available',
        ]);

        $this->actingAs($admin, 'admin')->patch(route('admin.applications.updateStatus', $application), [
            'status' => 'Approved',
            'review_notes' => 'Approved after full review.',
        ])->assertRedirect(route('admin.applications.show', $application));

        $this->assertDatabaseHas('pets', [
            'id' => $pet->id,
            'adoption_status' => 'Pending',
        ]);

        $this->actingAs($admin, 'admin')->patch(route('admin.applications.updateStatus', $application), [
            'status' => 'Withdrawn',
            'review_notes' => 'Applicant withdrew.',
        ])->assertRedirect(route('admin.applications.show', $application));

        $this->assertDatabaseHas('pets', [
            'id' => $pet->id,
            'adoption_status' => 'Available',
        ]);
    }
}
