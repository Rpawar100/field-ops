<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ZrthHierarchy;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TerritoryController extends Controller
{
    /**
     * Display a listing of territories.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $items = ZrthHierarchy::territories()
                ->with('parent.parent') // parent = region, grandparent = zone
                ->withCount([
                    'children as headquarters_count' => function ($q) {
                        $q->where('level', ZrthHierarchy::LEVEL_HQ);
                    },
                    'beats',
                ])
                ->orderBy('name')
                ->paginate(15);
        } catch (\Throwable $e) {
            $items = new LengthAwarePaginator([], 0, 15);
        }

        $territories = $items;
        return view('masters.territories.index', compact('territories'));
    }

    /**
     * Show the form for creating a new territory.
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        $parentData = $this->getParentData();

        return view('masters.territories.create', $parentData);
    }

    /**
     * Store a newly created territory.
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
            $validated['level'] = ZrthHierarchy::LEVEL_TERRITORY;
            ZrthHierarchy::create($validated);

            return redirect()->route('territories.index')->with('success', 'Territory created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create territory: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified territory.
     */
    public function show(int $id): \Illuminate\Contracts\View\View
    {
        $territory = ZrthHierarchy::territories()->findOrFail($id);

        try {
            $territory->load([
                'parent.parent',
                'children' => function ($q) {
                    $q->where('level', ZrthHierarchy::LEVEL_HQ);
                },
                'beats',
            ]);
        } catch (\Throwable $e) {
            // Continue with base territory object
        }

        return view('masters.territories.show', compact('territory'));
    }

    /**
     * Show the form for editing the specified territory.
     */
    public function edit(int $id): \Illuminate\Contracts\View\View
    {
        $territory = ZrthHierarchy::territories()->findOrFail($id);
        $parentData = $this->getParentData();

        return view('masters.territories.edit', array_merge(['territory' => $territory], $parentData));
    }

    /**
     * Update the specified territory.
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $territory = ZrthHierarchy::territories()->findOrFail($id);

        $validated = $request->validate([
            'parent_id'   => 'required|exists:zrth_hierarchies,id',
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:zrth_hierarchies,code,' . $territory->id,
            'description' => 'nullable|string',
            'status'      => 'required|string|in:active,inactive',
        ]);

        try {
            $territory->update($validated);

            return redirect()->route('territories.index')->with('success', 'Territory updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update territory: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified territory (soft delete).
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        $territory = ZrthHierarchy::territories()->findOrFail($id);

        try {
            $territory->delete();

            return redirect()->route('territories.index')->with('success', 'Territory deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('territories.index')->with('error', 'Failed to delete territory: ' . $e->getMessage());
        }
    }

    /**
     * Load parent data for dropdowns — zones and regions.
     */
    private function getParentData(): array
    {
        try {
            $zones   = ZrthHierarchy::zones()->active()->orderBy('name')->get(['id', 'name']);
            $regions = ZrthHierarchy::regions()->active()->orderBy('name')->get(['id', 'name', 'parent_id']);
        } catch (\Throwable $e) {
            $zones = $regions = collect();
        }

        return compact('zones', 'regions');
    }
}
