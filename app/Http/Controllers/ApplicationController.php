<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::with('pet')
            ->where('user_id', auth()->id())
            ->latest('submitted_at')
            ->paginate(10);

        return view('applications.index', compact('applications'));
    }

    public function show(Application $application)
    {
        abort_unless($application->user_id === auth()->id(), 403);

        $application->load('pet');

        return view('applications.show', compact('application'));
    }

    public function create(Request $request)
    {
        $petId = $request->integer('pet_id');

        if (!$petId) {
            if ($request->ajax() || $request->wantsJson() || $request->boolean('partial')) {
                return response()->json([
                    'message' => 'Please select a pet before starting an application.',
                ], 422);
            }

            return redirect()->route('pets.index')->with('error', 'Please select a pet before starting an application.');
        }

        $pet = Pet::where('adoption_status', 'Available')->findOrFail($petId);

        if ($request->ajax() || $request->boolean('partial')) {
            return view('applications.partials.modal-form', compact('pet'));
        }

        return view('applications.create', compact('pet'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id' => ['required', 'exists:pets,id'],
            'home_type' => ['required', 'string', 'max:255'],
            'household_members' => ['required', 'integer', 'min:1', 'max:30'],
            'has_other_pets' => ['required', 'boolean'],
            'other_pets_details' => ['nullable', 'string', 'max:2000', Rule::requiredIf(fn () => $request->boolean('has_other_pets'))],
            'yard_available' => ['required', 'boolean'],
            'experience_with_pets' => ['required', 'string', 'min:30', 'max:3000'],
            'employment_sustainability' => ['required', 'string', 'min:20', 'max:3000'],
            'reason_for_adoption' => ['required', 'string', 'min:30', 'max:3000'],
            'references' => ['nullable', 'string', 'max:2000'],
            'additional_information' => ['nullable', 'string', 'max:3000'],
        ]);

        $pet = Pet::where('id', $validated['pet_id'])
            ->where('adoption_status', 'Available')
            ->firstOrFail();

        $alreadyApplied = Application::where('user_id', auth()->id())
            ->where('pet_id', $pet->id)
            ->exists();

        if ($alreadyApplied) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'You already submitted an application for this pet.',
                    'errors' => [
                        'pet_id' => ['You already submitted an application for this pet.'],
                    ],
                ], 422);
            }

            return redirect()
                ->route('pets.show', $pet)
                ->with('error', 'You already submitted an application for this pet.');
        }

        try {
            $application = Application::create([
                'user_id' => auth()->id(),
                'pet_id' => $pet->id,
                'status' => ApplicationStatus::Submitted->value,
                'home_type' => $validated['home_type'],
                'household_members' => $validated['household_members'],
                'has_other_pets' => $validated['has_other_pets'],
                'other_pets_details' => $validated['other_pets_details'] ?? null,
                'yard_available' => $validated['yard_available'],
                'experience_with_pets' => $validated['experience_with_pets'],
                'employment_sustainability' => $validated['employment_sustainability'],
                'reason_for_adoption' => $validated['reason_for_adoption'],
                'references' => $validated['references'] ?? null,
                'additional_information' => $validated['additional_information'] ?? null,
                'submitted_at' => now(),
            ]);
        } catch (QueryException $exception) {
            \Log::error('Application submission failed (QueryException)', [
                'user_id' => auth()->id(),
                'pet_id' => $pet->id,
                'error' => $exception->getMessage(),
                'sql_state' => $exception->errorInfo[0] ?? null,
            ]);

            if ($this->isDuplicateApplicationConstraintViolation($exception)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'message' => 'You already submitted an application for this pet.',
                        'errors' => [
                            'pet_id' => ['You already submitted an application for this pet.'],
                        ],
                    ], 422);
                }

                return redirect()
                    ->route('pets.show', $pet)
                    ->with('error', 'You already submitted an application for this pet.');
            }

            throw $exception;
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your application has been submitted successfully. We will review it soon.',
                'redirect_url' => route('applications.show', $application),
            ]);
        }

        return redirect()
            ->route('pets.show', $pet)
            ->with('success', 'Your application has been submitted successfully. We will review it soon.');
    }

    private function isDuplicateApplicationConstraintViolation(QueryException $exception): bool
    {
        $sqlState = $exception->errorInfo[0] ?? null;
        $driverCode = (string) ($exception->errorInfo[1] ?? '');
        $message = strtolower($exception->getMessage());

        return $sqlState === '23000'
            || str_contains($message, 'unique constraint')
            || str_contains($message, 'duplicate entry')
            || $driverCode === '1062'
            || $driverCode === '19';
    }
}
