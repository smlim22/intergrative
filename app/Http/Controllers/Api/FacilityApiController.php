<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityApiController extends Controller
{
    // All can view 
    public function index()
    {
        return response()->json(Facility::all(), 200);
    }

    // All can view
    public function show(Facility $facility)
    {
        return response()->json($facility, 200);
    }

    // Only admin can create
    public function store(Request $request)
    {
        if ($request->user()->role != 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'capacity' => 'nullable|integer|min:1',
            'hourly_rate' => 'nullable|numeric|min:0',
            'half_day_rate' => 'nullable|numeric|min:0',
            'full_day_rate' => 'nullable|numeric|min:0',
            'per_use_rate' => 'nullable|numeric|min:0',
        ]);

        // Ensure at least one rate is provided
        if (!$validated['hourly_rate'] && !$validated['half_day_rate'] && 
            !$validated['full_day_rate'] && !$validated['per_use_rate']) {
            return response()->json([
                'error' => 'At least one pricing rate must be provided'
            ], 422);
        }

        $facility = Facility::create($validated);
        return response()->json($facility, 201);
    }

    // Only admin can update
    public function update(Request $request, Facility $facility)
    {
        if ($request->user()->role != 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'capacity' => 'nullable|integer|min:1',
            'hourly_rate' => 'nullable|numeric|min:0',
            'half_day_rate' => 'nullable|numeric|min:0',
            'full_day_rate' => 'nullable|numeric|min:0',
            'per_use_rate' => 'nullable|numeric|min:0',
        ]);

        // Ensure at least one rate is provided
        if (!$validated['hourly_rate'] && !$validated['half_day_rate'] && 
            !$validated['full_day_rate'] && !$validated['per_use_rate']) {
            return response()->json([
                'error' => 'At least one pricing rate must be provided'
            ], 422);
        }

        $facility->update($validated);
        return response()->json($facility, 200);
    }

    // Only admin can delete
    public function destroy(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        Facility::destroy($id);
        return response()->json(null, 204);
    }
}