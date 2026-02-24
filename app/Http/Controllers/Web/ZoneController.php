<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ZrthHierarchy;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ZoneController extends Controller
{
    /**
     * Display a listing of zones.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $items = ZrthHierarchy::zones()
                ->withCount(['children as regions_count' => function ($q) {
                    $q->where('level', ZrthHierarchy::LEVEL_REGION);
                }])
                ->orderBy('name')
                ->paginate(15);
        } catch (\Throwable $e) {
            $items = new LengthAwarePaginator([], 0, 15);
        }

        $zones = $items;
        return view('masters.zones.index', compact('zones'));
    }

    /**
     * Show the form for creating a new zone.
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        return view('masters.zones.create');
    }

    /**
     * Store a newly created zone.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:zrth_hierarchies,code',
            'description' => 'nullable|string',
            'status'      => 'required|string|in:active,inactive',
        ]);

        try {
            $validated['level'] = ZrthHierarchy::LEVEL_ZONE;
            $validated['parent_id'] = null;
            ZrthHierarchy::create($validated);

            return redirect()->route('zones.index')->with('success', 'Zone created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create zone: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified zone.
     */
    public function show(int $id): \Illuminate\Contracts\View\View
    {
        $zone = ZrthHierarchy::zones()->findOrFail($id);

        try {
            $zone->load(['children' => function ($q) {
                $q->where('level', ZrthHierarchy::LEVEL_REGION);
            }]);
        } catch (\Throwable $e) {
            // Continue with base zone object
        }

        return view('masters.zones.show', compact('zone'));
    }

    /**
     * Show the form for editing the specified zone.
     */
    public function edit(int $id): \Illuminate\Contracts\View\View
    {
        $zone = ZrthHierarchy::zones()->findOrFail($id);

        return view('masters.zones.edit', compact('zone'));
    }

    /**
     * Update the specified zone.
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $zone = ZrthHierarchy::zones()->findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:zrth_hierarchies,code,' . $zone->id,
            'description' => 'nullable|string',
            'status'      => 'required|string|in:active,inactive',
        ]);

        try {
            $zone->update($validated);

            return redirect()->route('zones.index')->with('success', 'Zone updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update zone: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified zone (soft delete).
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        $zone = ZrthHierarchy::zones()->findOrFail($id);

        try {
            $zone->delete();

            return redirect()->route('zones.index')->with('success', 'Zone deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('zones.index')->with('error', 'Failed to delete zone: ' . $e->getMessage());
        }
    }
}
