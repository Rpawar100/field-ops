<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ZrthHierarchy;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class HeadquartersController extends Controller
{
    /**
     * Display a listing of headquarters.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $items = ZrthHierarchy::headquarters()
                ->with('parent.parent.parent') // parent=territory, grandparent=region, great-grandparent=zone
                ->orderBy('name')
                ->paginate(15);
        } catch (\Throwable $e) {
            $items = new LengthAwarePaginator([], 0, 15);
        }

        $headquarters = $items;
        return view('masters.headquarters.index', compact('headquarters'));
    }

    /**
     * Show the form for creating a new headquarters.
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        $parentData = $this->getParentData();

        return view('masters.headquarters.create', $parentData);
    }

    /**
     * Store a newly created headquarters.
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
            $validated['level'] = ZrthHierarchy::LEVEL_HQ;
            ZrthHierarchy::create($validated);

            return redirect()->route('headquarters.index')->with('success', 'Headquarters created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create headquarters: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified headquarters.
     */
    public function show(int $id): \Illuminate\Contracts\View\View
    {
        $headquarters = ZrthHierarchy::headquarters()->findOrFail($id);

        try {
            $headquarters->load(['parent.parent.parent', 'beats', 'users']);
        } catch (\Throwable $e) {
            // Continue with base headquarters object
        }

        return view('masters.headquarters.show', compact('headquarters'));
    }

    /**
     * Show the form for editing the specified headquarters.
     */
    public function edit(int $id): \Illuminate\Contracts\View\View
    {
        $headquarters = ZrthHierarchy::headquarters()->findOrFail($id);
        $parentData = $this->getParentData();

        return view('masters.headquarters.edit', array_merge(['headquarters' => $headquarters], $parentData));
    }

    /**
     * Update the specified headquarters.
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $headquarters = ZrthHierarchy::headquarters()->findOrFail($id);

        $validated = $request->validate([
            'parent_id'   => 'required|exists:zrth_hierarchies,id',
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:zrth_hierarchies,code,' . $headquarters->id,
            'description' => 'nullable|string',
            'status'      => 'required|string|in:active,inactive',
        ]);

        try {
            $headquarters->update($validated);

            return redirect()->route('headquarters.index')->with('success', 'Headquarters updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update headquarters: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified headquarters (soft delete).
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        $headquarters = ZrthHierarchy::headquarters()->findOrFail($id);

        try {
            $headquarters->delete();

            return redirect()->route('headquarters.index')->with('success', 'Headquarters deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('headquarters.index')->with('error', 'Failed to delete headquarters: ' . $e->getMessage());
        }
    }

    /**
     * Load parent data for dropdowns — zones, regions, territories.
     */
    private function getParentData(): array
    {
        try {
            $zones       = ZrthHierarchy::zones()->active()->orderBy('name')->get(['id', 'name']);
            $regions     = ZrthHierarchy::regions()->active()->orderBy('name')->get(['id', 'name', 'parent_id']);
            $territories = ZrthHierarchy::territories()->active()->orderBy('name')->get(['id', 'name', 'parent_id']);
        } catch (\Throwable $e) {
            $zones = $regions = $territories = collect();
        }

        return compact('zones', 'regions', 'territories');
    }
}
