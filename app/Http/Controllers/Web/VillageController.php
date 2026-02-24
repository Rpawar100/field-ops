<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Village;
use App\Models\Taluka;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class VillageController extends Controller
{
    /**
     * Display a listing of villages.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $items = Village::with('taluka.district.state')
                ->withCount(['farmers', 'retailers'])
                ->orderBy('name')
                ->paginate(15);
        } catch (\Throwable $e) {
            $items = new LengthAwarePaginator([], 0, 15);
        }

        $villages = $items;
        return view('masters.villages.index', compact('villages'));
    }

    /**
     * Show the form for creating a new village.
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        $parentData = $this->getParentData();

        return view('masters.villages.create', $parentData);
    }

    /**
     * Store a newly created village.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'taluka_id' => 'required|exists:talukas,id',
            'name'      => 'required|string|max:255',
            'code'      => 'required|string|max:50|unique:villages,code',
            'pincode'   => 'nullable|string|max:10',
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status'    => 'required|string|in:active,inactive',
        ]);

        try {
            Village::create($validated);

            return redirect()->route('villages.index')->with('success', 'Village created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create village: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified village.
     */
    public function show(Village $village): \Illuminate\Contracts\View\View
    {
        try {
            $village->load(['taluka.district.state', 'farmers', 'retailers']);
        } catch (\Throwable $e) {
            // Continue with base village object
        }

        return view('masters.villages.show', compact('village'));
    }

    /**
     * Show the form for editing the specified village.
     */
    public function edit(Village $village): \Illuminate\Contracts\View\View
    {
        $parentData = $this->getParentData();

        return view('masters.villages.edit', array_merge(['village' => $village], $parentData));
    }

    /**
     * Update the specified village.
     */
    public function update(Request $request, Village $village): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'taluka_id' => 'required|exists:talukas,id',
            'name'      => 'required|string|max:255',
            'code'      => 'required|string|max:50|unique:villages,code,' . $village->id,
            'pincode'   => 'nullable|string|max:10',
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status'    => 'required|string|in:active,inactive',
        ]);

        try {
            $village->update($validated);

            return redirect()->route('villages.index')->with('success', 'Village updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update village: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified village (soft delete).
     */
    public function destroy(Village $village): \Illuminate\Http\RedirectResponse
    {
        try {
            $village->delete();

            return redirect()->route('villages.index')->with('success', 'Village deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('villages.index')->with('error', 'Failed to delete village: ' . $e->getMessage());
        }
    }

    /**
     * Load parent data for dropdowns.
     */
    private function getParentData(): array
    {
        try {
            $states    = State::where('status', 'active')->orderBy('name')->get(['id', 'name']);
            $districts = District::where('status', 'active')->orderBy('name')->get(['id', 'name', 'state_id']);
            $talukas   = Taluka::where('status', 'active')->orderBy('name')->get(['id', 'name', 'district_id']);
        } catch (\Throwable $e) {
            $states = $districts = $talukas = collect();
        }

        return compact('states', 'districts', 'talukas');
    }
}
