<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Taluka;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TalukaController extends Controller
{
    /**
     * Display a listing of talukas.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $items = Taluka::with('district.state')
                ->withCount('villages')
                ->orderBy('name')
                ->paginate(15);
        } catch (\Throwable $e) {
            $items = new LengthAwarePaginator([], 0, 15);
        }

        $talukas = $items;
        return view('masters.talukas.index', compact('talukas'));
    }

    /**
     * Show the form for creating a new taluka.
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        $parentData = $this->getParentData();

        return view('masters.talukas.create', $parentData);
    }

    /**
     * Store a newly created taluka.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:talukas,code',
            'status'      => 'required|string|in:active,inactive',
        ]);

        try {
            Taluka::create($validated);

            return redirect()->route('talukas.index')->with('success', 'Taluka created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create taluka: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified taluka.
     */
    public function show(Taluka $taluka): \Illuminate\Contracts\View\View
    {
        try {
            $taluka->load(['district.state', 'villages']);
        } catch (\Throwable $e) {
            // Continue with base taluka object
        }

        return view('masters.talukas.show', compact('taluka'));
    }

    /**
     * Show the form for editing the specified taluka.
     */
    public function edit(Taluka $taluka): \Illuminate\Contracts\View\View
    {
        $parentData = $this->getParentData();

        return view('masters.talukas.edit', array_merge(['taluka' => $taluka], $parentData));
    }

    /**
     * Update the specified taluka.
     */
    public function update(Request $request, Taluka $taluka): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:talukas,code,' . $taluka->id,
            'status'      => 'required|string|in:active,inactive',
        ]);

        try {
            $taluka->update($validated);

            return redirect()->route('talukas.index')->with('success', 'Taluka updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update taluka: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified taluka (soft delete).
     */
    public function destroy(Taluka $taluka): \Illuminate\Http\RedirectResponse
    {
        try {
            $taluka->delete();

            return redirect()->route('talukas.index')->with('success', 'Taluka deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('talukas.index')->with('error', 'Failed to delete taluka: ' . $e->getMessage());
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
        } catch (\Throwable $e) {
            $states = $districts = collect();
        }

        return compact('states', 'districts');
    }
}
