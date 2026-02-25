<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\User;
use App\Models\Beat;
use App\Models\Farmer;
use App\Models\Retailer;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $activities = Activity::with(['user', 'activityType', 'farmer', 'retailer', 'beat'])
                ->orderByDesc('execution_date')
                ->paginate(15);
        } catch (\Throwable $e) {
            $activities = new LengthAwarePaginator([], 0, 15);
        }

        return view('activities.index', compact('activities'));
    }

    /**
     * Show the form for creating a new activity.
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        try {
            $activityTypes = ActivityType::where('status', 'active')->orderBy('name')->get(['id', 'name', 'category']);
            $users    = User::where('status', 'active')->orderBy('name')->get(['id', 'name']);
            $beats    = Beat::where('status', 'active')->orderBy('name')->get(['id', 'name']);
            $farmers  = Farmer::where('status', 'active')->orderBy('name')->get(['id', 'name', 'farmer_code']);
            $retailers = Retailer::where('status', 'active')->orderBy('shop_name')->get(['id', 'shop_name', 'retailer_code']);
        } catch (\Throwable $e) {
            $activityTypes = $users = $beats = $farmers = $retailers = collect();
        }

        return view('activities.create', compact('activityTypes', 'users', 'beats', 'farmers', 'retailers'));
    }

    /**
     * Store a newly created activity.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'activity_type_id' => 'required|exists:activity_types,id',
            'user_id'          => 'required|exists:users,id',
            'farmer_id'        => 'nullable|exists:farmers,id',
            'retailer_id'      => 'nullable|exists:retailers,id',
            'distributor_id'   => 'nullable|exists:distributors,id',
            'beat_id'          => 'nullable|exists:beats,id',
            'village_id'       => 'nullable|exists:villages,id',
            'execution_date'   => 'required|date',
            'start_time'       => 'nullable|date_format:H:i',
            'remarks'          => 'nullable|string|max:65535',
            'next_action'      => 'nullable|string|max:255',
            'next_visit_date'  => 'nullable|date|after_or_equal:execution_date',
            'status'           => 'required|string|in:draft,submitted,completed,cancelled',
        ]);

        try {
            $validated['uuid'] = \Illuminate\Support\Str::uuid()->toString();
            Activity::create($validated);

            return redirect()->route('activities.index')->with('success', 'Activity created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create activity: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified activity.
     */
    public function show(Activity $activity): \Illuminate\Contracts\View\View
    {
        try {
            $activity->load([
                'user',
                'activityType',
                'farmer',
                'retailer',
                'beat',
                'village',
                'activityPhotos',
                'attendedUsers',
            ]);
        } catch (\Throwable $e) {
            // Continue with base activity object
        }

        return view('activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified activity.
     */
    public function edit(Activity $activity): \Illuminate\Contracts\View\View
    {
        try {
            $activityTypes = ActivityType::where('status', 'active')->orderBy('name')->get(['id', 'name', 'category']);
            $users    = User::where('status', 'active')->orderBy('name')->get(['id', 'name']);
            $beats    = Beat::where('status', 'active')->orderBy('name')->get(['id', 'name']);
            $farmers  = Farmer::where('status', 'active')->orderBy('name')->get(['id', 'name', 'farmer_code']);
            $retailers = Retailer::where('status', 'active')->orderBy('shop_name')->get(['id', 'shop_name', 'retailer_code']);
        } catch (\Throwable $e) {
            $activityTypes = $users = $beats = $farmers = $retailers = collect();
        }

        return view('activities.edit', compact('activity', 'activityTypes', 'users', 'beats', 'farmers', 'retailers'));
    }

    /**
     * Update the specified activity.
     */
    public function update(Request $request, Activity $activity): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'activity_type_id' => 'required|exists:activity_types,id',
            'user_id'          => 'required|exists:users,id',
            'farmer_id'        => 'nullable|exists:farmers,id',
            'retailer_id'      => 'nullable|exists:retailers,id',
            'distributor_id'   => 'nullable|exists:distributors,id',
            'beat_id'          => 'nullable|exists:beats,id',
            'village_id'       => 'nullable|exists:villages,id',
            'execution_date'   => 'required|date',
            'start_time'       => 'nullable|date_format:H:i',
            'remarks'          => 'nullable|string|max:65535',
            'next_action'      => 'nullable|string|max:255',
            'next_visit_date'  => 'nullable|date|after_or_equal:execution_date',
            'status'           => 'required|string|in:draft,submitted,completed,cancelled',
        ]);

        try {
            $activity->update($validated);

            return redirect()->route('activities.index')->with('success', 'Activity updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update activity: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified activity (soft delete).
     */
    public function destroy(Activity $activity): \Illuminate\Http\RedirectResponse
    {
        try {
            $activity->delete();

            return redirect()->route('activities.index')->with('success', 'Activity deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('activities.index')->with('error', 'Failed to delete activity: ' . $e->getMessage());
        }
    }

    /**
     * Show the execute form for an activity (add GPS, photos, completion data).
     */
    public function executeForm(Activity $activity): \Illuminate\Contracts\View\View
    {
        try {
            $activity->load(['user', 'activityType', 'farmer', 'retailer']);
        } catch (\Throwable $e) {
            // Continue with base activity object
        }

        return view('activities.execute', compact('activity'));
    }

    /**
     * Execute an activity (mark as completed with execution data).
     */
    public function execute(Request $request, Activity $activity): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'gps_latitude'  => 'nullable|numeric|between:-90,90',
            'gps_longitude' => 'nullable|numeric|between:-180,180',
            'gps_accuracy'  => 'nullable|numeric|min:0',
            'remarks'       => 'nullable|string|max:65535',
            'end_time'      => 'nullable|date_format:H:i',
        ]);

        try {
            $activity->update(array_merge($validated, [
                'status' => 'completed',
            ]));

            return redirect()->route('activities.show', $activity)->with('success', 'Activity executed successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to execute activity: ' . $e->getMessage());
        }
    }

    /**
     * Display photos associated with an activity (from activity_photos table).
     */
    public function photos(Activity $activity): \Illuminate\Contracts\View\View
    {
        try {
            $photos = $activity->activityPhotos()->get();
        } catch (\Throwable $e) {
            $photos = collect();
        }

        return view('activities.photos', compact('activity', 'photos'));
    }
}
