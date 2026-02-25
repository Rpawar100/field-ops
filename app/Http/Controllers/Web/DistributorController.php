<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\Village;
use App\Models\ZrthHierarchy;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DistributorController extends Controller
{
    /**
     * Display a listing of distributors.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $items = Distributor::with(['village', 'zrthHierarchy'])
                ->orderBy('name')
                ->paginate(15);
        } catch (\Throwable $e) {
            $items = new LengthAwarePaginator([], 0, 15);
        }

        $distributors = $items;
        return view('masters.distributors.index', compact('distributors'));
    }

    /**
     * Show the form for creating a new distributor.
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        $parentData = $this->getParentData();

        return view('masters.distributors.create', $parentData);
    }

    /**
     * Store a newly created distributor.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'distributor_code'   => 'required|string|max:50|unique:distributors,distributor_code',
            'name'               => 'required|string|max:255',
            'contact_person'     => 'nullable|string|max:255',
            'mobile'             => 'required|string|max:15',
            'email'              => 'nullable|email|max:255',
            'type'               => 'required|string|in:super_stockist,distributor,sub_distributor',
            'address'            => 'nullable|string|max:65535',
            'village_id'         => 'nullable|exists:villages,id',
            'pincode'            => 'nullable|string|max:10',
            'latitude'           => 'nullable|numeric|between:-90,90',
            'longitude'          => 'nullable|numeric|between:-180,180',
            'gst_number'         => 'nullable|string|max:50',
            'pan_number'         => 'nullable|string|max:20',
            'zrth_hierarchy_id'  => 'required|exists:zrth_hierarchies,id',
            'credit_limit'       => 'nullable|numeric|min:0',
            'credit_days'        => 'nullable|integer|min:0',
            'status'             => 'required|string|in:active,inactive',
        ]);

        try {
            Distributor::create($validated);

            return redirect()->route('distributors.index')->with('success', 'Distributor created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create distributor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified distributor.
     */
    public function show(Distributor $distributor): \Illuminate\Contracts\View\View
    {
        try {
            $distributor->load(['village', 'zrthHierarchy']);
        } catch (\Throwable $e) {
            // Continue with base distributor object
        }

        return view('masters.distributors.show', compact('distributor'));
    }

    /**
     * Show the form for editing the specified distributor.
     */
    public function edit(Distributor $distributor): \Illuminate\Contracts\View\View
    {
        $parentData = $this->getParentData();

        return view('masters.distributors.edit', array_merge(['distributor' => $distributor], $parentData));
    }

    /**
     * Update the specified distributor.
     */
    public function update(Request $request, Distributor $distributor): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'distributor_code'   => 'required|string|max:50|unique:distributors,distributor_code,' . $distributor->id,
            'name'               => 'required|string|max:255',
            'contact_person'     => 'nullable|string|max:255',
            'mobile'             => 'required|string|max:15',
            'email'              => 'nullable|email|max:255',
            'type'               => 'required|string|in:super_stockist,distributor,sub_distributor',
            'address'            => 'nullable|string|max:65535',
            'village_id'         => 'nullable|exists:villages,id',
            'pincode'            => 'nullable|string|max:10',
            'latitude'           => 'nullable|numeric|between:-90,90',
            'longitude'          => 'nullable|numeric|between:-180,180',
            'gst_number'         => 'nullable|string|max:50',
            'pan_number'         => 'nullable|string|max:20',
            'zrth_hierarchy_id'  => 'required|exists:zrth_hierarchies,id',
            'credit_limit'       => 'nullable|numeric|min:0',
            'credit_days'        => 'nullable|integer|min:0',
            'status'             => 'required|string|in:active,inactive',
        ]);

        try {
            $distributor->update($validated);

            return redirect()->route('distributors.index')->with('success', 'Distributor updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update distributor: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified distributor (soft delete).
     */
    public function destroy(Distributor $distributor): \Illuminate\Http\RedirectResponse
    {
        try {
            $distributor->delete();

            return redirect()->route('distributors.index')->with('success', 'Distributor deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('distributors.index')->with('error', 'Failed to delete distributor: ' . $e->getMessage());
        }
    }

    /**
     * Load parent data for dropdowns.
     */
    private function getParentData(): array
    {
        try {
            $villages        = Village::where('status', 'active')->orderBy('name')->get(['id', 'name']);
            $zrthHierarchies = ZrthHierarchy::active()->orderBy('name')->get(['id', 'name', 'level']);
        } catch (\Throwable $e) {
            $villages = $zrthHierarchies = collect();
        }

        return compact('villages', 'zrthHierarchies');
    }
}
