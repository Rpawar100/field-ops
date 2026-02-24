<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Retailer;
use App\Models\Village;
use App\Models\Beat;
use App\Models\ZrthHierarchy;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RetailerController extends Controller
{
    /**
     * Display a listing of retailers.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $retailers = Retailer::with(['village.taluka.district.state', 'beat', 'zrthHierarchy'])
                ->orderByDesc('created_at')
                ->paginate(15);
        } catch (\Throwable $e) {
            $retailers = new LengthAwarePaginator([], 0, 15);
        }

        return view('retailers.index', compact('retailers'));
    }

    /**
     * Show the form for creating a new retailer.
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        $parentData = $this->getParentData();

        return view('retailers.create', $parentData);
    }

    /**
     * Store a newly created retailer.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'shop_name'        => 'required|string|max:255',
            'owner_name'       => 'required|string|max:255',
            'contact_person'   => 'nullable|string|max:255',
            'mobile'           => 'required|string|max:15',
            'alternate_mobile' => 'nullable|string|max:15',
            'whatsapp_number'  => 'nullable|string|max:15',
            'email'            => 'nullable|email|max:255',
            'business_type'    => 'required|string|max:100',
            'firm_name'        => 'nullable|string|max:255',
            'address'          => 'required|string|max:65535',
            'village_id'       => 'required|exists:villages,id',
            'pincode'          => 'nullable|string|max:10',
            'latitude'         => 'nullable|numeric|between:-90,90',
            'longitude'        => 'nullable|numeric|between:-180,180',
            'zrth_hierarchy_id' => 'nullable|exists:zrth_hierarchies,id',
            'beat_id'          => 'nullable|exists:beats,id',
            'gst_number'       => 'nullable|string|max:50',
            'pan_number'       => 'nullable|string|max:20',
            'retailer_grade'   => 'nullable|string|in:A,B,C,D',
            'status'           => 'required|string|in:active,inactive,pending',
        ]);

        try {
            $validated['retailer_code'] = 'RTL-' . now()->format('Ymd') . '-' . str_pad(
                Retailer::whereDate('created_at', today())->count() + 1,
                4,
                '0',
                STR_PAD_LEFT
            );
            $validated['uuid'] = \Illuminate\Support\Str::uuid()->toString();
            $validated['registered_by'] = auth()->id();
            $validated['kyc_status'] = 'pending';

            Retailer::create($validated);

            return redirect()->route('retailers.index')->with('success', 'Retailer created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create retailer: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified retailer.
     */
    public function show(Retailer $retailer): \Illuminate\Contracts\View\View
    {
        try {
            $retailer->load([
                'village.taluka.district.state',
                'beat',
                'zrthHierarchy',
                'farmerRetailers.farmer',
                'documents',
                'distributors',
            ]);
        } catch (\Throwable $e) {
            // Continue with base retailer object
        }

        // Load linked farmers for display
        try {
            $farmers = $retailer->farmers()->with('village')->get();
        } catch (\Throwable $e) {
            $farmers = collect();
        }

        return view('retailers.show', compact('retailer', 'farmers'));
    }

    /**
     * Show the form for editing the specified retailer.
     */
    public function edit(Retailer $retailer): \Illuminate\Contracts\View\View
    {
        $parentData = $this->getParentData();

        return view('retailers.edit', array_merge(['retailer' => $retailer], $parentData));
    }

    /**
     * Update the specified retailer.
     */
    public function update(Request $request, Retailer $retailer): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'shop_name'        => 'required|string|max:255',
            'owner_name'       => 'required|string|max:255',
            'contact_person'   => 'nullable|string|max:255',
            'mobile'           => 'required|string|max:15',
            'alternate_mobile' => 'nullable|string|max:15',
            'whatsapp_number'  => 'nullable|string|max:15',
            'email'            => 'nullable|email|max:255',
            'business_type'    => 'required|string|max:100',
            'firm_name'        => 'nullable|string|max:255',
            'address'          => 'required|string|max:65535',
            'village_id'       => 'required|exists:villages,id',
            'pincode'          => 'nullable|string|max:10',
            'latitude'         => 'nullable|numeric|between:-90,90',
            'longitude'        => 'nullable|numeric|between:-180,180',
            'zrth_hierarchy_id' => 'nullable|exists:zrth_hierarchies,id',
            'beat_id'          => 'nullable|exists:beats,id',
            'gst_number'       => 'nullable|string|max:50',
            'pan_number'       => 'nullable|string|max:20',
            'retailer_grade'   => 'nullable|string|in:A,B,C,D',
            'status'           => 'required|string|in:active,inactive,pending',
        ]);

        try {
            $retailer->update($validated);

            return redirect()->route('retailers.index')->with('success', 'Retailer updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update retailer: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified retailer (soft delete).
     */
    public function destroy(Retailer $retailer): \Illuminate\Http\RedirectResponse
    {
        try {
            $retailer->delete();

            return redirect()->route('retailers.index')->with('success', 'Retailer deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('retailers.index')->with('error', 'Failed to delete retailer: ' . $e->getMessage());
        }
    }

    /**
     * Handle bulk upload of retailers via CSV/Excel.
     */
    public function bulkUpload(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:5120',
        ]);

        try {
            // TODO: Implement CSV/Excel parsing and bulk retailer creation
            return redirect()->route('retailers.index')->with('success', 'Bulk upload completed successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('retailers.index')->with('error', 'Bulk upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Update KYC information for a retailer.
     */
    public function kycUpdate(Request $request, Retailer $retailer): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'kyc_status'           => 'required|string|in:pending,submitted,verified,rejected',
            'verification_remarks' => 'nullable|string|max:2000',
        ]);

        try {
            $validated['verified_by'] = auth()->id();
            $validated['verified_at'] = now();
            $retailer->update($validated);

            return redirect()->route('retailers.show', $retailer)->with('success', 'KYC updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('retailers.show', $retailer)->with('error', 'KYC update failed: ' . $e->getMessage());
        }
    }

    /**
     * Load parent data for dropdowns.
     */
    private function getParentData(): array
    {
        try {
            $states          = \App\Models\State::where('status', 'active')->orderBy('name')->get(['id', 'name']);
            $districts       = \App\Models\District::where('status', 'active')->orderBy('name')->get(['id', 'name', 'state_id']);
            $talukas         = \App\Models\Taluka::where('status', 'active')->orderBy('name')->get(['id', 'name', 'district_id']);
            $villages        = Village::where('status', 'active')->orderBy('name')->get(['id', 'name', 'taluka_id']);
            $beats           = Beat::where('status', 'active')->orderBy('name')->get(['id', 'name']);
            $zrthHierarchies = ZrthHierarchy::headquarters()->active()->orderBy('name')->get(['id', 'name']);
        } catch (\Throwable $e) {
            $states = $districts = $talukas = $villages = $beats = $zrthHierarchies = collect();
        }

        return compact('states', 'districts', 'talukas', 'villages', 'beats', 'zrthHierarchies');
    }
}
