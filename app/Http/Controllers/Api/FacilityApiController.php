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

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric',
            'half_day_rate' => 'nullable|numeric',
            'full_day_rate' => 'nullable|numeric',
            'capacity' => 'nullable|integer',
        ]);

        $facility = Facility::create($request->all());
        return response()->json($facility, 201);
    }

    // Only admin can update
    public function update(Request $request, Facility $facility)
    {
        if ($request->user()->role != 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric',
            'half_day_rate' => 'nullable|numeric',
            'full_day_rate' => 'nullable|numeric',
            'capacity' => 'nullable|integer',
        ]);

        $facility->update($request->all());
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
