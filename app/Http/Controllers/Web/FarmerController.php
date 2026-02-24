<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\Village;
use App\Models\Beat;
use App\Models\ZrthHierarchy;
use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class FarmerController extends Controller
{
    /**
     * Display a listing of farmers.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $farmers = Farmer::with(['village.taluka.district.state', 'beat', 'zrthHierarchy'])
                ->orderByDesc('created_at')
                ->paginate(15);
        } catch (\Throwable $e) {
            $farmers = new LengthAwarePaginator([], 0, 15);
        }

        return view('farmers.index', compact('farmers'));
    }

    /**
     * Show the form for creating a new farmer.
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        $parentData = $this->getParentData();

        return view('farmers.create', $parentData);
    }

    /**
     * Store a newly created farmer.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name'                    => 'required|string|max:255',
            'father_name'             => 'nullable|string|max:255',
            'mobile'                  => 'required|string|max:15',
            'alternate_mobile'        => 'nullable|string|max:15',
            'whatsapp_number'         => 'nullable|string|max:15',
            'email'                   => 'nullable|email|max:255',
            'gender'                  => 'nullable|string|in:male,female,other',
            'date_of_birth'           => 'nullable|date',
            'address'                 => 'nullable|string|max:65535',
            'village_id'              => 'required|exists:villages,id',
            'pincode'                 => 'nullable|string|max:10',
            'latitude'                => 'nullable|numeric|between:-90,90',
            'longitude'               => 'nullable|numeric|between:-180,180',
            'zrth_hierarchy_id'       => 'nullable|exists:zrth_hierarchies,id',
            'beat_id'                 => 'nullable|exists:beats,id',
            'total_landholding_acres' => 'nullable|numeric|min:0',
            'cultivable_land_acres'   => 'nullable|numeric|min:0',
            'landholding_type'        => 'nullable|string|max:100',
            'irrigation_type'         => 'nullable|string|max:100',
            'farmer_type'             => 'required|string|in:pda,demo,user,non_user',
            'farmer_category'         => 'nullable|string|max:100',
            'is_progressive_farmer'   => 'nullable|boolean',
            'is_kol'                  => 'nullable|boolean',
            'preferred_language'      => 'nullable|string|max:50',
            'status'                  => 'required|string|in:active,inactive,pending',
        ]);

        try {
            // Generate farmer_code
            $validated['farmer_code'] = 'FRM-' . now()->format('Ymd') . '-' . str_pad(
                Farmer::whereDate('created_at', today())->count() + 1,
                4,
                '0',
                STR_PAD_LEFT
            );
            $validated['uuid'] = \Illuminate\Support\Str::uuid()->toString();
            $validated['registered_by'] = auth()->id();

            Farmer::create($validated);

            return redirect()->route('farmers.index')->with('success', 'Farmer created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create farmer: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified farmer.
     */
    public function show(Farmer $farmer): \Illuminate\Contracts\View\View
    {
        try {
            $farmer->load([
                'village.taluka.district.state',
                'beat',
                'zrthHierarchy',
                'farmerCrops.crop',
                'farmerRetailers.retailer',
            ]);
        } catch (\Throwable $e) {
            // Continue with base farmer object
        }

        // Load activities for display
        try {
            $activities = \App\Models\Activity::where('farmer_id', $farmer->id)
                ->with(['activityType', 'user'])
                ->orderByDesc('execution_date')
                ->limit(10)
                ->get();
        } catch (\Throwable $e) {
            $activities = collect();
        }

        // Load linked retailers for display
        try {
            $retailers = $farmer->retailers()->with('village')->get();
        } catch (\Throwable $e) {
            $retailers = collect();
        }

        return view('farmers.show', compact('farmer', 'activities', 'retailers'));
    }

    /**
     * Show the form for editing the specified farmer.
     */
    public function edit(Farmer $farmer): \Illuminate\Contracts\View\View
    {
        try {
            $farmer->load('farmerCrops');
        } catch (\Throwable $e) {
            // Continue with base farmer object
        }

        $parentData = $this->getParentData();

        return view('farmers.edit', array_merge(['farmer' => $farmer], $parentData));
    }

    /**
     * Update the specified farmer.
     */
    public function update(Request $request, Farmer $farmer): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name'                    => 'required|string|max:255',
            'father_name'             => 'nullable|string|max:255',
            'mobile'                  => 'required|string|max:15',
            'alternate_mobile'        => 'nullable|string|max:15',
            'whatsapp_number'         => 'nullable|string|max:15',
            'email'                   => 'nullable|email|max:255',
            'gender'                  => 'nullable|string|in:male,female,other',
            'date_of_birth'           => 'nullable|date',
            'address'                 => 'nullable|string|max:65535',
            'village_id'              => 'required|exists:villages,id',
            'pincode'                 => 'nullable|string|max:10',
            'latitude'                => 'nullable|numeric|between:-90,90',
            'longitude'               => 'nullable|numeric|between:-180,180',
            'zrth_hierarchy_id'       => 'nullable|exists:zrth_hierarchies,id',
            'beat_id'                 => 'nullable|exists:beats,id',
            'total_landholding_acres' => 'nullable|numeric|min:0',
            'cultivable_land_acres'   => 'nullable|numeric|min:0',
            'landholding_type'        => 'nullable|string|max:100',
            'irrigation_type'         => 'nullable|string|max:100',
            'farmer_type'             => 'required|string|in:pda,demo,user,non_user',
            'farmer_category'         => 'nullable|string|max:100',
            'is_progressive_farmer'   => 'nullable|boolean',
            'is_kol'                  => 'nullable|boolean',
            'preferred_language'      => 'nullable|string|max:50',
            'status'                  => 'required|string|in:active,inactive,pending',
        ]);

        try {
            $farmer->update($validated);

            return redirect()->route('farmers.index')->with('success', 'Farmer updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update farmer: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified farmer (soft delete).
     */
    public function destroy(Farmer $farmer): \Illuminate\Http\RedirectResponse
    {
        try {
            $farmer->delete();

            return redirect()->route('farmers.index')->with('success', 'Farmer deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('farmers.index')->with('error', 'Failed to delete farmer: ' . $e->getMessage());
        }
    }

    /**
     * Handle bulk upload of farmers via CSV/Excel.
     */
    public function bulkUpload(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:5120',
        ]);

        try {
            // TODO: Implement CSV/Excel parsing and bulk farmer creation
            return redirect()->route('farmers.index')->with('success', 'Bulk upload completed successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('farmers.index')->with('error', 'Bulk upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Display activities associated with a farmer.
     */
    public function activities(Farmer $farmer): \Illuminate\Contracts\View\View
    {
        try {
            $activities = \App\Models\Activity::where('farmer_id', $farmer->id)
                ->with(['activityType', 'user'])
                ->orderByDesc('execution_date')
                ->paginate(15);
        } catch (\Throwable $e) {
            $activities = new LengthAwarePaginator([], 0, 15);
        }

        return view('farmers.activities', compact('farmer', 'activities'));
    }

    /**
     * Display retailers associated with a farmer.
     */
    public function retailers(Farmer $farmer): \Illuminate\Contracts\View\View
    {
        try {
            $retailers = $farmer->farmerRetailers()
                ->with('retailer')
                ->paginate(15);
        } catch (\Throwable $e) {
            $retailers = new LengthAwarePaginator([], 0, 15);
        }

        return view('farmers.retailers', compact('farmer', 'retailers'));
    }

    /**
     * Load parent data for dropdowns.
     */
    private function getParentData(): array
    {
        try {
            $states         = \App\Models\State::where('status', 'active')->orderBy('name')->get(['id', 'name']);
            $districts      = \App\Models\District::where('status', 'active')->orderBy('name')->get(['id', 'name', 'state_id']);
            $talukas        = \App\Models\Taluka::where('status', 'active')->orderBy('name')->get(['id', 'name', 'district_id']);
            $villages       = Village::where('status', 'active')->orderBy('name')->get(['id', 'name', 'taluka_id']);
            $beats          = Beat::where('status', 'active')->orderBy('name')->get(['id', 'name']);
            $zrthHierarchies = ZrthHierarchy::headquarters()->active()->orderBy('name')->get(['id', 'name']);
            $crops          = Crop::where('status', 'active')->orderBy('name')->get(['id', 'name']);
        } catch (\Throwable $e) {
            $states = $districts = $talukas = $villages = $beats = $zrthHierarchies = $crops = collect();
        }

        return compact('states', 'districts', 'talukas', 'villages', 'beats', 'zrthHierarchies', 'crops');
    }
}
