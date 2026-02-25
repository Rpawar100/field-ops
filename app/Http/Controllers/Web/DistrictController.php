<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DistrictController extends Controller
{
    /**
     * Display a listing of districts.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $items = District::with('state')
                ->withCount('talukas')
                ->orderBy('name')
                ->paginate(15);
        } catch (\Throwable $e) {
            $items = new LengthAwarePaginator([], 0, 15);
        }

        $districts = $items;
        return view('masters.districts.index', compact('districts'));
    }

    /**
     * Show the form for creating a new district.
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        $parentData = $this->getParentData();

        return view('masters.districts.create', $parentData);
    }

    /**
     * Store a newly created district.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'state_id' => 'required|exists:states,id',
            'name'     => 'required|string|max:255',
            'code'     => 'required|string|max:50|unique:districts,code',
            'status'   => 'required|string|in:active,inactive',
        ]);

        try {
            District::create($validated);

            return redirect()->route('districts.index')->with('success', 'District created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create district: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified district.
     */
    public function show(District $district): \Illuminate\Contracts\View\View
    {
        try {
            $district->load(['state', 'talukas']);
        } catch (\Throwable $e) {
            // Continue with base district object
        }

        return view('masters.districts.show', compact('district'));
    }

    /**
     * Show the form for editing the specified district.
     */
    public function edit(District $district): \Illuminate\Contracts\View\View
    {
        $parentData = $this->getParentData();

        return view('masters.districts.edit', array_merge(['district' => $district], $parentData));
    }

    /**
     * Update the specified district.
     */
    public function update(Request $request, District $district): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'state_id' => 'required|exists:states,id',
            'name'     => 'required|string|max:255',
            'code'     => 'required|string|max:50|unique:districts,code,' . $district->id,
            'status'   => 'required|string|in:active,inactive',
        ]);

        try {
            $district->update($validated);

            return redirect()->route('districts.index')->with('success', 'District updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update district: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified district (soft delete).
     */
    public function destroy(District $district): \Illuminate\Http\RedirectResponse
    {
        try {
            $district->delete();

            return redirect()->route('districts.index')->with('success', 'District deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('districts.index')->with('error', 'Failed to delete district: ' . $e->getMessage());
        }
    }

    /**
     * Load parent data for dropdowns.
     */
    private function getParentData(): array
    {
        try {
            $states = State::where('status', 'active')->orderBy('name')->get(['id', 'name']);
        } catch (\Throwable $e) {
            $states = collect();
        }

        return compact('states');
    }
}
