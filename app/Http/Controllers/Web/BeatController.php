<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Beat;
use App\Models\Village;
use App\Models\ZrthHierarchy;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BeatController extends Controller
{
    /**
     * Display a listing of beats.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $items = Beat::with(['zrthHierarchy', 'village'])
                ->withCount(['farmers', 'retailers'])
                ->orderBy('name')
                ->paginate(15);
        } catch (\Throwable $e) {
            $items = new LengthAwarePaginator([], 0, 15);
        }

        $beats = $items;
        return view('masters.beats.index', compact('beats'));
    }

    /**
     * Show the form for creating a new beat.
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        $parentData = $this->getParentData();

        return view('masters.beats.create', $parentData);
    }

    /**
     * Store a newly created beat.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'zrth_hierarchy_id'      => 'required|exists:zrth_hierarchies,id',
            'village_id'             => 'nullable|exists:villages,id',
            'name'                   => 'required|string|max:255',
            'beat_code'              => 'required|string|max:50|unique:beats,beat_code',
            'description'            => 'nullable|string',
            'beat_day'               => 'nullable|string|in:mon,tue,wed,thu,fri,sat,sun',
            'visit_frequency_days'   => 'nullable|integer|min:1',
            'estimated_distance_km'  => 'nullable|numeric|min:0',
            'estimated_time_minutes' => 'nullable|integer|min:0',
            'coverage_village_ids'   => 'nullable|json',
            'status'                 => 'required|string|in:active,inactive',
        ]);

        try {
            Beat::create($validated);

            return redirect()->route('beats.index')->with('success', 'Beat created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create beat: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified beat.
     */
    public function show(Beat $beat): \Illuminate\Contracts\View\View
    {
        try {
            $beat->load(['zrthHierarchy.parent.parent.parent', 'village', 'beatFarmers.farmer', 'beatRetailers.retailer', 'userAssignments.user']);
        } catch (\Throwable $e) {
            // Continue with base beat object
        }

        return view('masters.beats.show', compact('beat'));
    }

    /**
     * Show the form for editing the specified beat.
     */
    public function edit(Beat $beat): \Illuminate\Contracts\View\View
    {
        $parentData = $this->getParentData();

        return view('masters.beats.edit', array_merge(['beat' => $beat], $parentData));
    }

    /**
     * Update the specified beat.
     */
    public function update(Request $request, Beat $beat): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'zrth_hierarchy_id'      => 'required|exists:zrth_hierarchies,id',
            'village_id'             => 'nullable|exists:villages,id',
            'name'                   => 'required|string|max:255',
            'beat_code'              => 'required|string|max:50|unique:beats,beat_code,' . $beat->id,
            'description'            => 'nullable|string',
            'beat_day'               => 'nullable|string|in:mon,tue,wed,thu,fri,sat,sun',
            'visit_frequency_days'   => 'nullable|integer|min:1',
            'estimated_distance_km'  => 'nullable|numeric|min:0',
            'estimated_time_minutes' => 'nullable|integer|min:0',
            'coverage_village_ids'   => 'nullable|json',
            'status'                 => 'required|string|in:active,inactive',
        ]);

        try {
            $beat->update($validated);

            return redirect()->route('beats.index')->with('success', 'Beat updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update beat: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified beat (soft delete).
     */
    public function destroy(Beat $beat): \Illuminate\Http\RedirectResponse
    {
        try {
            $beat->delete();

            return redirect()->route('beats.index')->with('success', 'Beat deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('beats.index')->with('error', 'Failed to delete beat: ' . $e->getMessage());
        }
    }

    /**
     * Load parent data for dropdowns.
     */
    private function getParentData(): array
    {
        try {
            $zrthHierarchies = ZrthHierarchy::headquarters()->active()->orderBy('name')->get(['id', 'name']);
            $villages        = Village::where('status', 'active')->orderBy('name')->get(['id', 'name']);
        } catch (\Throwable $e) {
            $zrthHierarchies = $villages = collect();
        }

        return compact('zrthHierarchies', 'villages');
    }
}
