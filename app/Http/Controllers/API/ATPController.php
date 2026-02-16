<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ATPController extends Controller
{
    /**
     * List ATPs for user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['user_id', 'status', 'start_date', 'end_date', 'page', 'per_page']);
            $perPage = $filters['per_page'] ?? 15;

            // $atps = AdvanceTourPlan::query();
            // if ($filters['user_id'] ?? null) {
            //     $atps->where('user_id', $filters['user_id']);
            // }
            // if ($filters['status'] ?? null) {
            //     $atps->where('status', $filters['status']);
            // }
            // if ($filters['start_date'] ?? null && $filters['end_date'] ?? null) {
            //     $atps->whereBetween('atp_date', [$filters['start_date'], $filters['end_date']]);
            // }
            // $atps = $atps->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'ATPs retrieved successfully',
                'data' => [
                    // 'atps' => $atps->items(),
                    'pagination' => [
                        // 'total' => $atps->total(),
                        // 'per_page' => $atps->perPage(),
                        // 'current_page' => $atps->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve ATPs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get ATP with beats and planned activities
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // $atp = AdvanceTourPlan::with(['beats', 'activities'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'ATP retrieved successfully',
                'data' => [
                    // 'atp' => $atp,
                    // 'beats_count' => $atp->beats()->count(),
                    // 'activities_count' => $atp->activities()->count(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ATP not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create new ATP for user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'atp_date' => 'required|date',
                'territory_id' => 'required|exists:territories,id',
                'description' => 'nullable|string',
                'status' => 'required|in:planned,executing,executed,cancelled',
            ]);

            // $atp = AdvanceTourPlan::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'ATP created successfully',
                'data' => [
                    // 'atp' => $atp,
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
     * Update ATP details
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'atp_date' => 'sometimes|date',
                'description' => 'nullable|string',
                'status' => 'sometimes|in:planned,executing,executed,cancelled',
            ]);

            // $atp = AdvanceTourPlan::findOrFail($id);
            // $atp->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'ATP updated successfully',
                'data' => [
                    // 'atp' => $atp,
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
     * Add beat to ATP
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addBeat(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'beat_id' => 'required|exists:beats,id',
                'sequence' => 'required|integer|min:1',
            ]);

            // $atp = AdvanceTourPlan::findOrFail($id);
            // $atp->beats()->attach($validated['beat_id'], ['sequence' => $validated['sequence']]);

            return response()->json([
                'success' => true,
                'message' => 'Beat added to ATP successfully'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Adding beat failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Remove beat from ATP
     *
     * @param string $id
     * @param string $beat_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeBeat($id, $beat_id)
    {
        try {
            // $atp = AdvanceTourPlan::findOrFail($id);
            // $atp->beats()->detach($beat_id);

            return response()->json([
                'success' => true,
                'message' => 'Beat removed from ATP successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Removing beat failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark ATP as executed
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
                'remarks' => 'nullable|string',
            ]);

            // $atp = AdvanceTourPlan::findOrFail($id);
            // $atp->update(['status' => 'executed', 'executed_at' => $validated['executed_at']]);

            return response()->json([
                'success' => true,
                'message' => 'ATP executed successfully',
                'data' => [
                    // 'atp' => $atp,
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
     * Get users planned ATPs
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlanned(Request $request)
    {
        try {
            $user_id = $request->get('user_id');
            $from_date = $request->get('from_date', today());
            $to_date = $request->get('to_date', today()->addDays(30));

            // $atps = AdvanceTourPlan::where('user_id', $user_id)
            //     ->where('status', 'planned')
            //     ->whereBetween('atp_date', [$from_date, $to_date])
            //     ->with('beats')
            //     ->get();

            return response()->json([
                'success' => true,
                'message' => 'Planned ATPs retrieved successfully',
                'data' => [
                    // 'atps' => $atps,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve planned ATPs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Auto-populate beats based on territory
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autoFillBeats(Request $request)
    {
        try {
            $territory_id = $request->get('territory_id');

            // $beats = Beat::where('territory_id', $territory_id)
            //     ->where('status', 'active')
            //     ->get(['id', 'name', 'code']);

            return response()->json([
                'success' => true,
                'message' => 'Beats retrieved successfully',
                'data' => [
                    // 'beats' => $beats,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve beats',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
