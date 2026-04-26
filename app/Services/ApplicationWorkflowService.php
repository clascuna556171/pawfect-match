<?php

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Enums\PetStatus;
use App\Models\Application;
use Illuminate\Validation\ValidationException;

class ApplicationWorkflowService
{
    public function assertTransitionAllowed(ApplicationStatus $currentStatus, ApplicationStatus $nextStatus): void
    {
        if ($currentStatus === $nextStatus) {
            return;
        }

        $allowedTransitions = ApplicationStatus::transitionMap();
        $allowed = $allowedTransitions[$currentStatus->value] ?? [];

        if (!in_array($nextStatus->value, $allowed, true)) {
            throw ValidationException::withMessages([
                'status' => "Invalid status transition from {$currentStatus->value} to {$nextStatus->value}.",
            ]);
        }
    }

    public function applyPetStatusRules(Application $application, ApplicationStatus $currentStatus, ApplicationStatus $nextStatus): void
    {
        if (!$application->pet || $currentStatus === $nextStatus) {
            return;
        }

        if (
            $nextStatus === ApplicationStatus::Approved
            && $application->pet->adoption_status === PetStatus::Available->value
        ) {
            $application->pet->update(['adoption_status' => PetStatus::Pending->value]);
            return;
        }

        if (
            in_array($nextStatus, [ApplicationStatus::Rejected, ApplicationStatus::Withdrawn], true)
            && $application->pet->adoption_status === PetStatus::Pending->value
        ) {
            $hasOtherOpenApplications = Application::query()
                ->where('pet_id', $application->pet_id)
                ->where('id', '!=', $application->id)
                ->whereIn('status', ApplicationStatus::openStatuses())
                ->exists();

            if (!$hasOtherOpenApplications) {
                $application->pet->update(['adoption_status' => PetStatus::Available->value]);
            }
        }
    }
}
