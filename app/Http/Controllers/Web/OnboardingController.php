<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ZrthHierarchy;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class OnboardingController extends Controller
{
    /**
     * Display a listing of onboarding records.
     * The view expects $applications to be a paginated collection of User models
     * with eager-loaded faOnboardingDetail (aliased as onboardingDetails).
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $applications = User::with('faOnboardingDetail')
                ->whereHas('faOnboardingDetail')
                ->orderByDesc('created_at')
                ->paginate(15);
        } catch (\Throwable $e) {
            // Fallback: if fa_onboarding_details table doesn't exist or other error
            try {
                $applications = User::where('role', 'fa')
                    ->orderByDesc('created_at')
                    ->paginate(15);
            } catch (\Throwable $e2) {
                $applications = new LengthAwarePaginator([], 0, 15);
            }
        }

        return view('onboarding.index', compact('applications'));
    }

    /**
     * Show the form for creating a new onboarding record.
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        $parentData = $this->getParentData();

        return view('onboarding.create', $parentData);
    }

    /**
     * Store a newly created onboarding record.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'user_id'                  => 'required|exists:users,id',
            'pan_number'               => 'nullable|string|max:20',
            'aadhaar_number'           => 'nullable|string|max:20',
            'driving_license_number'   => 'nullable|string|max:50',
            'qualification'            => 'nullable|string|max:255',
            'experience_details'       => 'nullable|string|max:65535',
            'bank_name'               => 'nullable|string|max:255',
            'bank_branch'             => 'nullable|string|max:255',
            'bank_account_number'     => 'nullable|string|max:50',
            'bank_ifsc_code'          => 'nullable|string|max:20',
            'proposed_salary'          => 'nullable|numeric|min:0',
            'emergency_contact_name'   => 'nullable|string|max:255',
            'emergency_contact_phone'  => 'nullable|string|max:15',
            'emergency_contact_relation' => 'nullable|string|max:100',
        ]);

        try {
            $validated['kyc_status'] = 'pending';
            \App\Models\FaOnboardingDetail::create($validated);

            return redirect()->route('onboarding.index')->with('success', 'Onboarding record created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create onboarding record: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified onboarding record.
     */
    public function show(int $id): \Illuminate\Contracts\View\View
    {
        try {
            $onboarding = \App\Models\FaOnboardingDetail::with(['user', 'documents'])->findOrFail($id);
        } catch (\Throwable $e) {
            return abort(404);
        }

        return view('onboarding.show', compact('onboarding'));
    }

    /**
     * Show the form for editing the specified onboarding record.
     */
    public function edit(int $id): \Illuminate\Contracts\View\View
    {
        $onboarding = \App\Models\FaOnboardingDetail::findOrFail($id);
        $parentData = $this->getParentData();

        return view('onboarding.edit', array_merge(['onboarding' => $onboarding], $parentData));
    }

    /**
     * Update the specified onboarding record.
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $onboarding = \App\Models\FaOnboardingDetail::findOrFail($id);

        $validated = $request->validate([
            'pan_number'               => 'nullable|string|max:20',
            'aadhaar_number'           => 'nullable|string|max:20',
            'driving_license_number'   => 'nullable|string|max:50',
            'qualification'            => 'nullable|string|max:255',
            'experience_details'       => 'nullable|string|max:65535',
            'bank_name'               => 'nullable|string|max:255',
            'bank_branch'             => 'nullable|string|max:255',
            'bank_account_number'     => 'nullable|string|max:50',
            'bank_ifsc_code'          => 'nullable|string|max:20',
            'proposed_salary'          => 'nullable|numeric|min:0',
            'approved_salary'          => 'nullable|numeric|min:0',
            'salary_status'            => 'nullable|string|max:50',
            'emergency_contact_name'   => 'nullable|string|max:255',
            'emergency_contact_phone'  => 'nullable|string|max:15',
            'emergency_contact_relation' => 'nullable|string|max:100',
        ]);

        try {
            $onboarding->update($validated);

            return redirect()->route('onboarding.index')->with('success', 'Onboarding record updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update onboarding record: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified onboarding record.
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $onboarding = \App\Models\FaOnboardingDetail::findOrFail($id);
            $onboarding->delete();

            return redirect()->route('onboarding.index')->with('success', 'Onboarding record deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('onboarding.index')->with('error', 'Failed to delete onboarding record: ' . $e->getMessage());
        }
    }

    /**
     * Approve an onboarding record (verify KYC).
     */
    public function approve(int $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $onboarding = \App\Models\FaOnboardingDetail::findOrFail($id);
            $onboarding->update([
                'kyc_status'  => 'verified',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            return redirect()->route('onboarding.show', $id)->with('success', 'Onboarding approved successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('onboarding.show', $id)->with('error', 'Failed to approve: ' . $e->getMessage());
        }
    }

    /**
     * Reject an onboarding record.
     */
    public function reject(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $onboarding = \App\Models\FaOnboardingDetail::findOrFail($id);
            $onboarding->update([
                'kyc_status'           => 'rejected',
                'verified_by'          => auth()->id(),
                'verified_at'          => now(),
                'verification_remarks' => $request->input('verification_remarks', ''),
            ]);

            return redirect()->route('onboarding.show', $id)->with('success', 'Onboarding rejected.');
        } catch (\Throwable $e) {
            return redirect()->route('onboarding.show', $id)->with('error', 'Failed to reject: ' . $e->getMessage());
        }
    }

    /**
     * Display documents for an onboarding record (fa_documents table).
     */
    public function documents(int $id): \Illuminate\Contracts\View\View
    {
        try {
            $onboarding = \App\Models\FaOnboardingDetail::findOrFail($id);
            $documents = \App\Models\FaDocument::where('user_id', $onboarding->user_id)->paginate(15);
        } catch (\Throwable $e) {
            $onboarding = null;
            $documents = new LengthAwarePaginator([], 0, 15);
        }

        return view('onboarding.documents', compact('onboarding', 'documents'));
    }

    /**
     * Load parent data for dropdowns.
     */
    private function getParentData(): array
    {
        try {
            $users = User::where('status', 'active')
                ->where('role', 'fa')
                ->orderBy('name')
                ->get(['id', 'name', 'user_id as employee_code']);
            $zones = ZrthHierarchy::zones()->active()->orderBy('name')->get(['id', 'name']);
        } catch (\Throwable $e) {
            $users = $zones = collect();
        }

        return compact('users', 'zones');
    }
}
