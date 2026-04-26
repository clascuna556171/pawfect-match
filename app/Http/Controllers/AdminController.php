<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Enums\PetStatus;
use App\Models\Application;
use App\Models\Category;
use App\Models\Donation;
use App\Models\Pet;
use App\Models\Testimonial;
use App\Models\User;
use App\Services\ApplicationWorkflowService;
use App\Services\DashboardMetricsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function __construct(
        private readonly DashboardMetricsService $dashboardMetrics,
        private readonly ApplicationWorkflowService $applicationWorkflow
    ) {
    }

    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if (Auth::guard('admin')->attempt($credentials)) {
            $adminUser = Auth::guard('admin')->user();

            if ($adminUser && $adminUser->hasRole([User::ROLE_ADMIN, User::ROLE_STAFF])) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }

            Auth::guard('admin')->logout();
            return back()->withErrors(['email' => 'Unauthorized.']);
        }
        
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('status', 'You have been signed out from the admin portal.');
    }
    
    public function dashboard()
    {
        $dashboardData = $this->dashboardMetrics->buildDashboardData();
        $stats = $dashboardData['stats'];
        $recentPets = $dashboardData['recentPets'];
        $chartData = $dashboardData['chartData'];
        
        return view('admin.dashboard', compact('stats', 'recentPets', 'chartData'));
    }

    public function applicationsIndex(Request $request)
    {
        $status = $request->query('status');
        $allowedStatuses = array_map(
            fn (ApplicationStatus $applicationStatus) => $applicationStatus->value,
            ApplicationStatus::cases()
        );

        if ($status !== null && !in_array($status, $allowedStatuses, true)) {
            $status = null;
        }

        $applications = Application::with(['user', 'pet'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->orderByRaw("CASE
                WHEN status = 'Submitted' THEN 1
                WHEN status = 'Under Review' THEN 2
                WHEN status = 'Approved' THEN 3
                WHEN status = 'Rejected' THEN 4
                WHEN status = 'Withdrawn' THEN 5
                ELSE 6
            END")
            ->latest('submitted_at')
            ->paginate(15)
            ->withQueryString();

        $statusCounts = Application::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusCounts['All'] = Application::count();

        return view('admin.applications.index', compact('applications', 'statusCounts', 'status'));
    }

    public function applicationsShow(Application $application)
    {
        $application->load(['user', 'pet']);

        return view('admin.applications.show', compact('application'));
    }

    public function applicationsUpdateStatus(Request $request, Application $application)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::enum(ApplicationStatus::class)],
            'review_notes' => ['nullable', 'string', 'max:3000'],
        ]);

        $currentStatus = ApplicationStatus::from($application->status);
        $nextStatus = ApplicationStatus::from($validated['status']);
        $this->applicationWorkflow->assertTransitionAllowed($currentStatus, $nextStatus);

        DB::transaction(function () use ($application, $validated, $nextStatus, $currentStatus) {
            $application->status = $nextStatus->value;
            if (array_key_exists('review_notes', $validated)) {
                $application->review_notes = $validated['review_notes'];
            }

            if ($nextStatus !== ApplicationStatus::Submitted && !$application->reviewed_at) {
                $application->reviewed_at = now();
            }

            if ($nextStatus === ApplicationStatus::Submitted) {
                $application->reviewed_at = null;
            }

            $application->save();

            $this->applicationWorkflow->applyPetStatusRules($application, $currentStatus, $nextStatus);
        });

        return redirect()
            ->route('admin.applications.show', $application)
            ->with('success', 'Application status updated successfully.');
    }

    public function donationsIndex(Request $request)
    {
        $status = $request->query('status');

        $donations = Donation::with(['user', 'pet'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest('donated_at')
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        $statusCounts = Donation::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusCounts['All'] = Donation::count();

        $totalRaised = Donation::query()
            ->where('status', 'Confirmed')
            ->sum('amount');

        return view('admin.donations.index', compact('donations', 'statusCounts', 'status', 'totalRaised'));
    }

    public function donationsUpdateStatus(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['Confirmed', 'Pending', 'Refunded', 'Cancelled'])],
        ]);

        $donation->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.donations.index', ['status' => $request->query('status')])
            ->with('success', 'Donation status updated successfully.');
    }
    
    public function testimonialsIndex(Request $request)
    {
        $status = $request->query('status');
        
        $testimonials = Testimonial::query()
            ->when($status === 'approved', fn($q) => $q->where('is_approved', true))
            ->when($status === 'pending', fn($q) => $q->where('is_approved', false))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statusCounts = [
            'All' => Testimonial::count(),
            'Pending' => Testimonial::where('is_approved', false)->count(),
            'Approved' => Testimonial::where('is_approved', true)->count(),
        ];

        return view('admin.testimonials.index', compact('testimonials', 'statusCounts', 'status'));
    }

    public function testimonialsUpdateStatus(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'status' => 'required|in:1,0',
        ]);

        $testimonial->update(['is_approved' => (bool) $request->status]);

        return back()->with('success', 'Testimonial status updated.');
    }

    public function testimonialsDestroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return back()->with('success', 'Testimonial deleted successfully.');
    }
    
    public function index()
    {
        $pets = Pet::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.pets', compact('pets'));
    }
    
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => ['required', Rule::in(Pet::speciesOptions())],
            'breed' => 'required|string|max:255',
            'age_months' => 'required|integer|min:0',
            'size' => ['required', Rule::in(Pet::sizeOptions())],
            'gender' => 'required|in:Male,Female,Unknown',
            'description' => 'required|string',
            'energy_level' => 'required|integer|min:0|max:10',
            'health_status' => 'required|in:Excellent,Good,Fair,Poor,Medical Attention Required',
            'image_url' => 'required|string',
            'temperament' => 'nullable|string',
            'medical_notes' => 'nullable|string',
        ]);

        $category = Category::where('name', $validated['species'])->first();
        $validated['category_id'] = $category ? $category->id : 1;

        if (!empty($validated['temperament'])) {
            $validated['temperament'] = array_map('trim', explode(',', $validated['temperament']));
        }

        $validated['adoption_status'] = PetStatus::Available->value;
        
        Pet::create($validated);
        
        return redirect()->route('admin.pets.index')->with('success', 'Pet added successfully!');
    }
    
    public function show($id)
    {
        return response()->json(Pet::findOrFail($id));
    }
    
    public function update(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);

        $input = $request->only(['name', 'breed', 'species', 'energy_level']);

        if (empty($input)) {
            return response()->json([
                'success' => false,
                'message' => 'No editable fields were provided.',
                'errors' => [
                    'general' => ['No editable fields were provided.'],
                ],
            ], 422);
        }

        $validator = Validator::make($input, [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'breed' => ['sometimes', 'required', 'string', 'max:255'],
            'species' => ['sometimes', 'required', Rule::in(Pet::speciesOptions())],
            'energy_level' => ['sometimes', 'required', 'integer', 'min:0', 'max:10'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        $pet->update($validator->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Pet updated successfully.',
            'pet' => [
                'id' => $pet->id,
                'name' => $pet->name,
                'breed' => $pet->breed,
                'species' => $pet->species,
                'energy_level' => $pet->energy_level,
            ],
        ]);
    }
    
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => ['required', Rule::enum(PetStatus::class)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        $pet = Pet::findOrFail($id);
        $pet->update([
            'adoption_status' => PetStatus::from($validator->validated()['status'])->value,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Pet status updated successfully.',
            'status' => $pet->adoption_status,
        ]);
    }
    
    public function destroy($id)
    {
        Pet::findOrFail($id)->delete();
        return redirect()->route('admin.pets.index')->with('success', 'Pet deleted!');
    }
}