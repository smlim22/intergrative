<?php
// filepath: app/Http/Controllers/FacilityController.php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Factories\FacilityDisplayStrategyFactory;

class FacilityController extends Controller
{
    use AuthorizesRequests;
    
    // ===== WEB METHODS (Your existing methods) =====
    
    public function index(Request $request)
    {
        $query = Facility::query();

        if ($request->filled('search')) {
            $query->searchByName($request->search);
        }

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('status', 'active');
            } elseif ($request->status === 'disabled') {
                $query->where('status', 'disabled');
            }
        }

        $facilities = $query->get();
        $categories = Facility::select('category')->distinct()->pluck('category');

        return view('facilities.index', compact('facilities', 'categories'));
    }

    public function create()
    {
        $facilityStrategies = FacilityDisplayStrategyFactory::getAllStrategies();
        $existingCategories = Facility::select('category')->distinct()->orderBy('category')->pluck('category');
    
        return view('facilities.create', compact('facilityStrategies', 'existingCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'hourly_rate' => 'nullable|numeric|min:0',
            'half_day_rate' => 'nullable|numeric|min:0',
            'full_day_rate' => 'nullable|numeric|min:0',
            'per_use_rate' => 'nullable|numeric|min:0',
            'capacity' => 'nullable|integer|min:1',
        ]);

          if (empty($validated['hourly_rate']) && empty($validated['half_day_rate']) && 
            empty($validated['full_day_rate']) && empty($validated['per_use_rate'])) {
            return back()->withErrors([
                'pricing' => 'Please enter at least one pricing rate (hourly, half-day, full-day, or per-use).'
            ])->withInput();
        }

        // Use Strategy Pattern for category-specific validation
        $strategy = FacilityDisplayStrategyFactory::createFromCategory($validated['category']);
        $categoryErrors = $strategy->validateFacilityData($validated);
        
        if (!empty($categoryErrors)) {
            return back()->withErrors($categoryErrors)->withInput();
        }

        // Convert empty strings to null
        foreach (['hourly_rate', 'half_day_rate', 'full_day_rate', 'per_use_rate', 'capacity'] as $field) {
            $validated[$field] = $validated[$field] ?: null;
        }

        $validated['status'] = 'active';
        Facility::create($validated);
        
        return redirect()->route('facilities.index')
                        ->with('success', 'Facility added successfully.');
    }

    public function edit(Facility $facility)
    {
        $facilityStrategies = FacilityDisplayStrategyFactory::getAllStrategies();
        $existingCategories = Facility::select('category')->distinct()->orderBy('category')->pluck('category');
    
        // Decode HTML entities in categories
        $existingCategories = $existingCategories->map(function ($category) {
            return html_entity_decode($category);
        });
    
        return view('facilities.edit', compact('facility', 'facilityStrategies', 'existingCategories'));
    }

    public function update(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'hourly_rate' => 'nullable|numeric|min:0',
            'half_day_rate' => 'nullable|numeric|min:0',
            'full_day_rate' => 'nullable|numeric|min:0',
            'per_use_rate' => 'nullable|numeric|min:0',
            'capacity' => 'nullable|integer|min:1',
        ]);

          if (empty($validated['hourly_rate']) && empty($validated['half_day_rate']) && 
            empty($validated['full_day_rate']) && empty($validated['per_use_rate'])) {
            return back()->withErrors([
                'pricing' => 'Please enter at least one pricing rate (hourly, half-day, full-day, or per-use).'
            ])->withInput();
        }

        // Use Strategy Pattern for validation
        $strategy = FacilityDisplayStrategyFactory::createFromCategory($validated['category']);
        $categoryErrors = $strategy->validateFacilityData($validated);
        
        if (!empty($categoryErrors)) {
            return back()->withErrors($categoryErrors)->withInput();
        }

        // Convert empty strings to null
        foreach (['hourly_rate', 'half_day_rate', 'full_day_rate', 'per_use_rate', 'capacity'] as $field) {
            $validated[$field] = $validated[$field] ?: null;
        }

        $facility->update($validated);
        
        return redirect()->route('facilities.index')
                        ->with('success', 'Facility updated successfully.');
    }

    public function disable(Facility $facility)
    {
        $facility->update(['status' => 'disabled']);
        
        return redirect()->route('facilities.index')
                        ->with('success', 'Facility "' . $facility->name . '" has been disabled successfully!');
    }

    public function enable(Facility $facility)
    {
        $facility->update(['status' => 'active']);
        
        return redirect()->route('facilities.index')
                        ->with('success', 'Facility "' . $facility->name . '" has been enabled successfully!');
    }

    // ===== RESTful API METHODS =====

    /**
     * API: Get all facilities with filters
     * GET /api/facilities
     */
    public function apiIndex(Request $request)
    {
        try {
            $query = Facility::query();

            // Apply search filter
            if ($request->filled('search')) {
                $query->searchByName($request->search);
            }

            // Apply category filter
            if ($request->filled('category')) {
                $query->byCategory($request->category);
            }

            // Apply status filter (default to active only for API)
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            } else {
                $query->active(); // Only active facilities by default
            }

            // Pagination support
            $perPage = $request->input('per_page', 15);
            $facilities = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Facilities retrieved successfully',
                'data' => $facilities->items(),
                'pagination' => [
                    'current_page' => $facilities->currentPage(),
                    'last_page' => $facilities->lastPage(),
                    'per_page' => $facilities->perPage(),
                    'total' => $facilities->total(),
                    'from' => $facilities->firstItem(),
                    'to' => $facilities->lastItem(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving facilities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get single facility by ID
     * GET /api/facilities/{id}
     */
    public function apiShow(Facility $facility)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Facility retrieved successfully',
                'data' => [
                    'id' => $facility->id,
                    'name' => $facility->name,
                    'category' => $facility->category,
                    'description' => $facility->description,
                    'capacity' => $facility->capacity,
                    'hourly_rate' => $facility->hourly_rate,
                    'half_day_rate' => $facility->half_day_rate,
                    'full_day_rate' => $facility->full_day_rate,
                    'per_use_rate' => $facility->per_use_rate,
                    'status' => $facility->status,
                    'formatted_pricing' => $facility->getFormattedPricing(),
                    'facility_type' => $facility->getFacilityType(),
                    'capacity_display' => $facility->getCapacityDisplay(),
                    'is_active' => $facility->isActive(),
                    'created_at' => $facility->created_at,
                    'updated_at' => $facility->updated_at
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving facility',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Create new facility
     * POST /api/facilities
     */
    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'hourly_rate' => 'nullable|numeric|min:0',
                'half_day_rate' => 'nullable|numeric|min:0',
                'full_day_rate' => 'nullable|numeric|min:0',
                'per_use_rate' => 'nullable|numeric|min:0',
                'capacity' => 'nullable|integer|min:1',
            ]);

            // Use Strategy Pattern for validation
            $strategy = FacilityDisplayStrategyFactory::createFromCategory($validated['category']);
            $categoryErrors = $strategy->validateFacilityData($validated);
            
            if (!empty($categoryErrors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $categoryErrors
                ], 422);
            }

            // Custom validation: At least one rate must be provided
            if (empty($validated['hourly_rate']) && empty($validated['half_day_rate']) && 
                empty($validated['full_day_rate']) && empty($validated['per_use_rate'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'At least one pricing rate must be provided',
                    'errors' => ['pricing' => ['At least one pricing rate is required']]
                ], 422);
            }

            // Convert empty strings to null
            foreach (['hourly_rate', 'half_day_rate', 'full_day_rate', 'per_use_rate', 'capacity'] as $field) {
                $validated[$field] = $validated[$field] ?: null;
            }

            $validated['status'] = 'active';
            $facility = Facility::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Facility created successfully',
                'data' => $facility
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating facility',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Update facility
     * PUT /api/facilities/{id}
     */
    public function apiUpdate(Request $request, Facility $facility)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'hourly_rate' => 'nullable|numeric|min:0',
                'half_day_rate' => 'nullable|numeric|min:0',
                'full_day_rate' => 'nullable|numeric|min:0',
                'per_use_rate' => 'nullable|numeric|min:0',
                'capacity' => 'nullable|integer|min:1',
            ]);

            // Use Strategy Pattern for validation
            $strategy = FacilityDisplayStrategyFactory::createFromCategory($validated['category']);
            $categoryErrors = $strategy->validateFacilityData($validated);
            
            if (!empty($categoryErrors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $categoryErrors
                ], 422);
            }

            // Custom validation
            if (empty($validated['hourly_rate']) && empty($validated['half_day_rate']) && 
                empty($validated['full_day_rate']) && empty($validated['per_use_rate'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'At least one pricing rate must be provided',
                    'errors' => ['pricing' => ['At least one pricing rate is required']]
                ], 422);
            }

            // Convert empty strings to null
            foreach (['hourly_rate', 'half_day_rate', 'full_day_rate', 'per_use_rate', 'capacity'] as $field) {
                $validated[$field] = $validated[$field] ?: null;
            }

            $facility->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Facility updated successfully',
                'data' => $facility->fresh()
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating facility',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Disable facility
     * PATCH /api/facilities/{id}/disable
     */
    public function apiDisable(Facility $facility)
    {
        try {
            $facility->update(['status' => 'disabled']);

            return response()->json([
                'success' => true,
                'message' => "Facility '{$facility->name}' has been disabled successfully",
                'data' => $facility->fresh()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error disabling facility',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Enable facility
     * PATCH /api/facilities/{id}/enable
     */
    public function apiEnable(Facility $facility)
    {
        try {
            $facility->update(['status' => 'active']);

            return response()->json([
                'success' => true,
                'message' => "Facility '{$facility->name}' has been enabled successfully",
                'data' => $facility->fresh()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error enabling facility',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get facilities by category
     * GET /api/facilities/category/{category}
     */
    public function apiByCategory($category)
    {
        try {
            $facilities = Facility::byCategory($category)
                                 ->active()
                                 ->get();

            return response()->json([
                'success' => true,
                'message' => "Facilities in category '{$category}' retrieved successfully",
                'category' => $category,
                'data' => $facilities,
                'total' => $facilities->count()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving facilities by category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Search facilities
     * GET /api/facilities/search
     */
    public function apiSearch(Request $request)
    {
        try {
            $searchTerm = $request->input('q');
            
            if (empty($searchTerm)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search term (q) is required'
                ], 400);
            }

            $facilities = Facility::where('name', 'like', '%' . $searchTerm . '%')
                                 ->orWhere('description', 'like', '%' . $searchTerm . '%')
                                 ->orWhere('category', 'like', '%' . $searchTerm . '%')
                                 ->active()
                                 ->get();

            return response()->json([
                'success' => true,
                'message' => 'Search completed successfully',
                'search_term' => $searchTerm,
                'data' => $facilities,
                'total' => $facilities->count()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching facilities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get all categories
     * GET /api/facilities/categories
     */
    public function apiCategories()
    {
        try {
            $categories = Facility::active()
                                 ->select('category')
                                 ->distinct()
                                 ->pluck('category');

            return response()->json([
                'success' => true,
                'message' => 'Categories retrieved successfully',
                'data' => $categories,
                'total' => $categories->count()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get facility statistics
     * GET /api/facilities/stats
     */
    public function apiStats()
    {
        try {
            $stats = [
                'total_facilities' => Facility::count(),
                'active_facilities' => Facility::active()->count(),
                'disabled_facilities' => Facility::where('status', 'disabled')->count(),
                'categories_count' => Facility::distinct('category')->count(),
                'facilities_by_category' => Facility::selectRaw('category, COUNT(*) as count')
                                                  ->groupBy('category')
                                                  ->get()
                                                  ->pluck('count', 'category'),
                'facilities_by_status' => Facility::selectRaw('status, COUNT(*) as count')
                                                 ->groupBy('status')
                                                 ->get()
                                                 ->pluck('count', 'status')
            ];

            return response()->json([
                'success' => true,
                'message' => 'Statistics retrieved successfully',
                'data' => $stats
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function apiCategoriesDropdown()
    {
        try {
            $categories = Facility::select('category')
                             ->distinct()
                             ->orderBy('category')
                             ->pluck('category');

            return response()->json([
                'success' => true,
                'message' => 'Categories for dropdown retrieved successfully',
                'data' => $categories->values()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}