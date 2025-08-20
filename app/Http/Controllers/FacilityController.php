<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FacilityController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $query = Facility::query();

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

        $facilities = $query->get();
        $categories = Facility::select('category')->distinct()->pluck('category');

        return view('facilities.index', compact('facilities', 'categories'));
    }


    public function create()
    {
        return view('facilities.create');
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
        ]);

        // Custom validation: At least one rate must be provided
        if (empty($validated['hourly_rate']) && empty($validated['half_day_rate']) && empty($validated['full_day_rate']) && empty($validated['per_use_rate'])) {
            return back()->withErrors([
                'pricing' => 'At least one pricing rate (hourly, half day, full day, or per use) must be provided.'
            ])->withInput();
        }

        // Convert empty strings to null for proper database storage
        $validated['hourly_rate'] = $validated['hourly_rate'] ?: null;
        $validated['half_day_rate'] = $validated['half_day_rate'] ?: null;
        $validated['full_day_rate'] = $validated['full_day_rate'] ?: null;
        $validated['per_use_rate'] = $validated['per_use_rate'] ?: null;

        Facility::create($validated);
        return redirect()->route('facilities.index')->with('success', 'Facility added successfully.');
    }

    public function edit(Facility $facility)
    {
        return view('facilities.edit', compact('facility'));
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
        ]);

        // Custom validation: At least one rate must be provided
        if (empty($validated['hourly_rate']) && empty($validated['half_day_rate']) && empty($validated['full_day_rate']) && empty($validated['per_use_rate'])) {
            return back()->withErrors([
                'pricing' => 'At least one pricing rate (hourly, half day, full day, or per use) must be provided.'
            ])->withInput();
        }

        // Convert empty strings to null for proper database storage
        $validated['hourly_rate'] = $validated['hourly_rate'] ?: null;
        $validated['half_day_rate'] = $validated['half_day_rate'] ?: null;
        $validated['full_day_rate'] = $validated['full_day_rate'] ?: null;
        $validated['per_use_rate'] = $validated['per_use_rate'] ?: null;

        $facility->update($validated);
        return redirect()->route('facilities.index')->with('success', 'Facility updated successfully.');
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();
        return redirect()->route('facilities.index')->with('success', 'Facility deleted successfully.');
    }
}
