<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DemoController extends Controller
{
    /**
     * List demo distributions
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexDistribution(Request $request)
    {
        try {
            $filters = $request->only(['user_id', 'status', 'start_date', 'end_date', 'page', 'per_page']);
            $perPage = $filters['per_page'] ?? 15;

            // $distributions = DemoDistribution::query();
            // if ($filters['user_id'] ?? null) {
            //     $distributions->where('user_id', $filters['user_id']);
            // }
            // if ($filters['status'] ?? null) {
            //     $distributions->where('status', $filters['status']);
            // }
            // if ($filters['start_date'] ?? null && $filters['end_date'] ?? null) {
            //     $distributions->whereBetween('dispatched_date', [$filters['start_date'], $filters['end_date']]);
            // }
            // $distributions = $distributions->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Demo distributions retrieved successfully',
                'data' => [
                    // 'distributions' => $distributions->items(),
                    'pagination' => [
                        // 'total' => $distributions->total(),
                        // 'per_page' => $distributions->perPage(),
                        // 'current_page' => $distributions->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve distributions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get demo distribution details
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showDistribution($id)
    {
        try {
            // $distribution = DemoDistribution::with('items')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Distribution retrieved successfully',
                'data' => [
                    // 'distribution' => $distribution,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Distribution not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Record demo dispatch
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeDistribution(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'dispatched_date' => 'required|date',
                'customer_type' => 'required|in:farmer,retailer',
                'customer_id' => 'required|string',
                'description' => 'nullable|string',
            ]);

            // $distribution = DemoDistribution::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Demo distributed successfully',
                'data' => [
                    // 'distribution' => $distribution,
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
     * List demo executions
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function executionIndex(Request $request)
    {
        try {
            $filters = $request->only(['demo_id', 'stage', 'status', 'page', 'per_page']);
            $perPage = $filters['per_page'] ?? 15;

            // $executions = DemoExecution::query();
            // if ($filters['demo_id'] ?? null) {
            //     $executions->where('demo_id', $filters['demo_id']);
            // }
            // if ($filters['stage'] ?? null) {
            //     $executions->where('stage', $filters['stage']);
            // }
            // if ($filters['status'] ?? null) {
            //     $executions->where('status', $filters['status']);
            // }
            // $executions = $executions->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Demo executions retrieved successfully',
                'data' => [
                    // 'executions' => $executions->items(),
                    'pagination' => [
                        // 'total' => $executions->total(),
                        // 'per_page' => $executions->perPage(),
                        // 'current_page' => $executions->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve executions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Record demo stage execution
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function executionStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'demo_id' => 'required|exists:demo_distributions,id',
                'stage' => 'required|in:stage1,stage2,stage3',
                'executed_date' => 'required|date',
                'feedback' => 'nullable|string',
                'quantity_used' => 'required|integer|min:0',
                'quantity_remaining' => 'required|integer|min:0',
                'observations' => 'nullable|string',
                'status' => 'required|in:successful,partial,failed',
            ]);

            // $execution = DemoExecution::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Demo execution recorded successfully',
                'data' => [
                    // 'execution' => $execution,
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
     * Get demo reconciliation report
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reconciliation(Request $request)
    {
        try {
            $filters = $request->only(['user_id', 'product_id', 'start_date', 'end_date']);

            // Build query with filters
            // $report = DemoDistribution::query();
            // Calculate reconciliation data

            return response()->json([
                'success' => true,
                'message' => 'Reconciliation report retrieved successfully',
                'data' => [
                    // 'total_distributed' => ...,
                    // 'total_executed' => ...,
                    // 'pending' => ...,
                    // 'details' => ...,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reschedule demo visit
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reschedule(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'new_date' => 'required|date',
                'reason' => 'nullable|string',
            ]);

            // $distribution = DemoDistribution::findOrFail($id);
            // $distribution->update(['dispatched_date' => $validated['new_date']]);

            return response()->json([
                'success' => true,
                'message' => 'Demo rescheduled successfully',
                'data' => [
                    // 'distribution' => $distribution,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Rescheduling failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Mark demo as failed
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function failure(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'failure_reason' => 'required|string',
                'failure_date' => 'required|date',
            ]);

            // $distribution = DemoDistribution::findOrFail($id);
            // $distribution->update(['status' => 'failed', 'failure_reason' => $validated['failure_reason']]);

            return response()->json([
                'success' => true,
                'message' => 'Demo marked as failed',
                'data' => [
                    // 'distribution' => $distribution,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Operation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }
}
