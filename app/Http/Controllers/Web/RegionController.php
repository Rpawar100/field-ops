<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ZrthHierarchy;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RegionController extends Controller
{
    /**
     * Display a listing of regions.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $items = ZrthHierarchy::regions()
                ->with('parent') // parent = zone
                ->withCount(['children as territories_count' => function ($q) {
                    $q->where('level', ZrthHierarchy::LEVEL_TERRITORY);
                }])
                ->orderBy('name')
                ->paginate(15);
        } catch (\Throwable $e) {
            $items = new LengthAwarePaginator([], 0, 15);
        }

        $regions = $items;
        return view('masters.regions.index', compact('regions'));
    }

    /**
     * Show the form for creating a new region.
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        $parentData = $this->getParentData();

        return view('masters.regions.create', $parentData);
    }

    /**
     * Store a newly created region.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'parent_id'   => 'required|exists:zrth_hierarchies,id',
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:zrth_hierarchies,code',
            'description' => 'nullable|string',
            'status'      => 'required|string|in:active,inactive',
        ]);

        try {
            $validated['level'] = ZrthHierarchy::LEVEL_REGION;
            ZrthHierarchy::create($validated);

            return redirect()->route('regions.index')->with('success', 'Region created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create region: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified region.
     */
    public function show(int $id): \Illuminate\Contracts\View\View
    {
        $region = ZrthHierarchy::regions()->findOrFail($id);

        try {
            $region->load(['parent', 'children' => function ($q) {
                $q->where('level', ZrthHierarchy::LEVEL_TERRITORY);
            }]);
        } catch (\Throwable $e) {
            // Continue with base region object
        }

        return view('masters.regions.show', compact('region'));
    }

    /**
     * Show the form for editing the specified region.
     */
    public function edit(int $id): \Illuminate\Contracts\View\View
    {
        $region = ZrthHierarchy::regions()->findOrFail($id);
        $parentData = $this->getParentData();

        return view('masters.regions.edit', array_merge(['region' => $region], $parentData));
    }

    /**
     * Update the specified region.
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $region = ZrthHierarchy::regions()->findOrFail($id);

        $validated = $request->validate([
            'parent_id'   => 'required|exists:zrth_hierarchies,id',
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:zrth_hierarchies,code,' . $region->id,
            'description' => 'nullable|string',
            'status'      => 'required|string|in:active,inactive',
        ]);

        try {
            $region->update($validated);

            return redirect()->route('regions.index')->with('success', 'Region updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update region: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified region (soft delete).
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        $region = ZrthHierarchy::regions()->findOrFail($id);

        try {
            $region->delete();

            return redirect()->route('regions.index')->with('success', 'Region deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('regions.index')->with('error', 'Failed to delete region: ' . $e->getMessage());
        }
    }

    /**
     * Load parent data for dropdowns — zones are parents of regions.
     */
    private function getParentData(): array
    {
        try {
            $zones = ZrthHierarchy::zones()->active()->orderBy('name')->get(['id', 'name']);
        } catch (\Throwable $e) {
            $zones = collect();
        }

        return compact('zones');
    }
}
