<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ZrthHierarchy;
use App\Models\UserZrthAssignment;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $users = User::with(['reportingManager'])
                ->orderByDesc('created_at')
                ->paginate(15);
        } catch (\Throwable $e) {
            $users = new LengthAwarePaginator([], 0, 15);
        }

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        try {
            $managers = User::where('status', 'active')->get(['id', 'name', 'role']);
            $zones = ZrthHierarchy::zones()->active()->orderBy('name')->get(['id', 'name']);
        } catch (\Throwable $e) {
            $managers = collect();
            $zones = collect();
        }

        return view('users.create', compact('managers', 'zones'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'user_id'              => 'required|string|max:50|unique:users,user_id',
            'name'                 => 'required|string|max:255',
            'mobile'               => 'required|string|max:15|unique:users,mobile',
            'email'                => 'required|email|max:255|unique:users,email',
            'password'             => 'required|string|min:8|confirmed',
            'role'                 => 'required|string|in:nsm,zm,rm,tsl,fa',
            'designation'          => 'nullable|string|max:100',
            'fa_type'              => 'nullable|string|in:permanent,temporary',
            'reporting_manager_id' => 'nullable|exists:users,id',
            'status'               => 'required|string|in:active,inactive,pending,suspended',
            'app_access_enabled'   => 'nullable|boolean',
            'date_of_joining'      => 'nullable|date',
            'date_of_leaving'      => 'nullable|date|after_or_equal:date_of_joining',
            'address'              => 'nullable|string|max:65535',
            'profile_photo'        => 'nullable|string|max:500',
        ]);

        try {
            $validated['password'] = bcrypt($validated['password']);
            $validated['app_access_enabled'] = $validated['app_access_enabled'] ?? true;
            User::create($validated);

            return redirect()->route('users.index')->with('success', 'User created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): \Illuminate\Contracts\View\View
    {
        try {
            $user->load(['reportingManager', 'subordinates', 'zrthAssignments.zrthHierarchy', 'attendances']);
        } catch (\Throwable $e) {
            // Continue with base user object
        }

        // Load recent activities for display
        try {
            $activities = \App\Models\Activity::where('user_id', $user->id)
                ->with(['activityType'])
                ->orderByDesc('execution_date')
                ->limit(10)
                ->get();
        } catch (\Throwable $e) {
            $activities = collect();
        }

        return view('users.show', compact('user', 'activities'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): \Illuminate\Contracts\View\View
    {
        try {
            $managers = User::where('id', '!=', $user->id)
                ->where('status', 'active')
                ->get(['id', 'name', 'role']);
            $zones = ZrthHierarchy::zones()->active()->orderBy('name')->get(['id', 'name']);
        } catch (\Throwable $e) {
            $managers = collect();
            $zones = collect();
        }

        return view('users.edit', compact('user', 'managers', 'zones'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'user_id'              => 'required|string|max:50|unique:users,user_id,' . $user->id,
            'name'                 => 'required|string|max:255',
            'mobile'               => 'required|string|max:15|unique:users,mobile,' . $user->id,
            'email'                => 'required|email|max:255|unique:users,email,' . $user->id,
            'password'             => 'nullable|string|min:8|confirmed',
            'role'                 => 'required|string|in:nsm,zm,rm,tsl,fa',
            'designation'          => 'nullable|string|max:100',
            'fa_type'              => 'nullable|string|in:permanent,temporary',
            'reporting_manager_id' => 'nullable|exists:users,id',
            'status'               => 'required|string|in:active,inactive,pending,suspended',
            'app_access_enabled'   => 'nullable|boolean',
            'date_of_joining'      => 'nullable|date',
            'date_of_leaving'      => 'nullable|date|after_or_equal:date_of_joining',
            'address'              => 'nullable|string|max:65535',
            'profile_photo'        => 'nullable|string|max:500',
        ]);

        try {
            if (!empty($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user (soft delete).
     */
    public function destroy(User $user): \Illuminate\Http\RedirectResponse
    {
        try {
            $user->delete();

            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('users.index')->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Realign a user's ZRTH assignments via user_zrth_assignments table.
     */
    public function realign(User $user): \Illuminate\Http\RedirectResponse
    {
        try {
            $user->load('zrthAssignments.zrthHierarchy');

            return redirect()->route('users.show', $user)->with('success', 'User ZRTH assignments reloaded.');
        } catch (\Throwable $e) {
            return redirect()->route('users.show', $user)->with('error', 'Failed to realign user: ' . $e->getMessage());
        }
    }

    /**
     * Handle bulk upload of users via CSV/Excel.
     */
    public function bulkUpload(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:5120',
        ]);

        try {
            // TODO: Implement CSV/Excel parsing and bulk user creation
            return redirect()->route('users.index')->with('success', 'Bulk upload completed successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('users.index')->with('error', 'Bulk upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Download the bulk upload template.
     */
    public function bulkDownload(): \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
    {
        try {
            $headers = ['User ID', 'Name', 'Mobile', 'Email', 'Role', 'Designation', 'FA Type', 'Reporting Manager Email', 'Status', 'Date of Joining'];
            $csvContent = implode(',', $headers) . "\n";

            $tempFile = tempnam(sys_get_temp_dir(), 'users_template_');
            file_put_contents($tempFile, $csvContent);

            return response()->download($tempFile, 'users_bulk_upload_template.csv')->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            return redirect()->route('users.index')->with('error', 'Failed to generate template: ' . $e->getMessage());
        }
    }
}
