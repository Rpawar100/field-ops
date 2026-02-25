<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ActivityController extends Controller
{
    /**
     * List activities for user with filters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['type', 'status', 'start_date', 'end_date', 'user_id', 'page', 'per_page']);
            $perPage = $filters['per_page'] ?? 15;

            // $activities = Activity::query();
            // if ($filters['type'] ?? null) {
            //     $activities->where('activity_type', $filters['type']);
            // }
            // if ($filters['status'] ?? null) {
            //     $activities->where('status', $filters['status']);
            // }
            // if ($filters['start_date'] ?? null && $filters['end_date'] ?? null) {
            //     $activities->whereBetween('activity_date', [$filters['start_date'], $filters['end_date']]);
            // }
            // if ($filters['user_id'] ?? null) {
            //     $activities->where('user_id', $filters['user_id']);
            // }
            // $activities = $activities->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Activities retrieved successfully',
                'data' => [
                    // 'activities' => $activities->items(),
                    'pagination' => [
                        // 'total' => $activities->total(),
                        // 'per_page' => $activities->perPage(),
                        // 'current_page' => $activities->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve activities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get activity details
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // $activity = Activity::with(['photos', 'attributes'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Activity retrieved successfully',
                'data' => [
                    // 'activity' => $activity,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Activity not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create new activity
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'activity_type' => 'required|string|max:255',
                'user_id' => 'required|exists:users,id',
                'beat_id' => 'required|exists:beats,id',
                'customer_id' => 'nullable|string',
                'activity_date' => 'required|date',
                'scheduled_for' => 'required|in:farmer,retailer,distributor',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'description' => 'nullable|string',
                'status' => 'required|in:planned,executed,pending,cancelled',
            ]);

            // $activity = Activity::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Activity created successfully',
                'data' => [
                    // 'activity' => $activity,
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Update activity details
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'activity_type' => 'sometimes|string|max:255',
                'activity_date' => 'sometimes|date',
                'description' => 'nullable|string',
            ]);

            // $activity = Activity::findOrFail($id);
            // $activity->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Activity updated successfully',
                'data' => [
                    // 'activity' => $activity,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Mark activity as executed with photos and attributes
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'executed_at' => 'required|date_time',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'photos' => 'nullable|array',
                'photos.*' => 'file|image|max:5120',
                'attributes' => 'nullable|array',
            ]);

            // $activity = Activity::findOrFail($id);
            // $activity->update(['status' => 'executed', 'executed_at' => $validated['executed_at']]);

            return response()->json([
                'success' => true,
                'message' => 'Activity executed successfully',
                'data' => [
                    // 'activity' => $activity,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Execution failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Get activities for a specific date
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByDate(Request $request)
    {
        try {
            $date = $request->get('date');
            $user_id = $request->get('user_id');

            // $activities = Activity::where('activity_date', $date);
            // if ($user_id) {
            //     $activities->where('user_id', $user_id);
            // }
            // $activities = $activities->get();

            return response()->json([
                'success' => true,
                'message' => 'Activities retrieved successfully',
                'data' => [
                    // 'activities' => $activities,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve activities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get activities in an ATP
     *
     * @param string $atp_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByATP($atp_id)
    {
        try {
            // $activities = Activity::whereHasMorph('plannable', ['AdvanceTourPlan'])
            //     ->where('plannable_id', $atp_id)
            //     ->get();

            return response()->json([
                'success' => true,
                'message' => 'Activities retrieved successfully',
                'data' => [
                    // 'activities' => $activities,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve activities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload activity photo
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadPhoto(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'photo' => 'required|file|image|max:5120',
                'caption' => 'nullable|string',
            ]);

            // $activity = Activity::findOrFail($id);
            // Store photo

            return response()->json([
                'success' => true,
                'message' => 'Photo uploaded successfully',
                'data' => [
                    // 'photo_url' => $url,
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Get photos for activity
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPhotos($id)
    {
        try {
            // $activity = Activity::findOrFail($id);
            // $photos = $activity->photos()->get();

            return response()->json([
                'success' => true,
                'message' => 'Photos retrieved successfully',
                'data' => [
                    // 'photos' => $photos,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve photos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
