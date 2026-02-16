<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AttendanceController extends Controller
{
    /**
     * Mark check-in with GPS and selfie
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkIn(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'selfie' => 'required|file|image|max:5120',
                'check_in_time' => 'required|date_time',
                'address' => 'nullable|string',
            ]);

            // Process check-in
            // $attendance = Attendance::updateOrCreate(
            //     ['user_id' => $validated['user_id'], 'date' => today()],
            //     ['check_in_time' => $validated['check_in_time'], ...]
            // );

            return response()->json([
                'success' => true,
                'message' => 'Check-in successful',
                'data' => [
                    // 'attendance' => $attendance,
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Check-in failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Mark check-out
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkOut(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'check_out_time' => 'required|date_time',
                'address' => 'nullable|string',
            ]);

            // Process check-out
            // $attendance = Attendance::where('user_id', $validated['user_id'])
            //     ->whereDate('date', today())
            //     ->update(['check_out_time' => $validated['check_out_time']]);

            return response()->json([
                'success' => true,
                'message' => 'Check-out successful',
                'data' => [
                    // 'attendance' => $attendance,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Check-out failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Get user's today's attendance
     *
     * @param string $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getToday($user_id)
    {
        try {
            // $attendance = Attendance::where('user_id', $user_id)
            //     ->whereDate('date', today())
            //     ->first();

            return response()->json([
                'success' => true,
                'message' => 'Attendance retrieved successfully',
                'data' => [
                    // 'attendance' => $attendance,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve attendance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's monthly attendance
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMonth(Request $request)
    {
        try {
            $user_id = $request->get('user_id');
            $month = $request->get('month', date('m'));
            $year = $request->get('year', date('Y'));

            // $attendance = Attendance::where('user_id', $user_id)
            //     ->whereMonth('date', $month)
            //     ->whereYear('date', $year)
            //     ->paginate(31);

            return response()->json([
                'success' => true,
                'message' => 'Monthly attendance retrieved successfully',
                'data' => [
                    // 'attendance' => $attendance->items(),
                    'summary' => [
                        // 'present_days' => ...,
                        // 'absent_days' => ...,
                        // 'leave_days' => ...,
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve attendance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get attendance history with filters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHistory(Request $request)
    {
        try {
            $filters = $request->only(['user_id', 'status', 'start_date', 'end_date', 'page', 'per_page']);
            $perPage = $filters['per_page'] ?? 15;

            // $attendance = Attendance::query();
            // if ($filters['user_id'] ?? null) {
            //     $attendance->where('user_id', $filters['user_id']);
            // }
            // if ($filters['status'] ?? null) {
            //     $attendance->where('status', $filters['status']);
            // }
            // if ($filters['start_date'] ?? null && $filters['end_date'] ?? null) {
            //     $attendance->whereBetween('date', [$filters['start_date'], $filters['end_date']]);
            // }
            // $attendance = $attendance->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Attendance history retrieved successfully',
                'data' => [
                    // 'attendance' => $attendance->items(),
                    'pagination' => [
                        // 'total' => $attendance->total(),
                        // 'per_page' => $attendance->perPage(),
                        // 'current_page' => $attendance->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve attendance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get attendance dashboard for manager
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboard(Request $request)
    {
        try {
            $manager_id = $request->get('manager_id');
            $date = $request->get('date', today());

            // $teamAttendance = Attendance::whereHas('user', function($q) use ($manager_id) {
            //     $q->where('reporting_to', $manager_id);
            // })->whereDate('date', $date)->get();

            return response()->json([
                'success' => true,
                'message' => 'Dashboard data retrieved successfully',
                'data' => [
                    // 'present' => ...,
                    // 'absent' => ...,
                    // 'leave' => ...,
                    // 'details' => $teamAttendance,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin regularize absent/missed entries
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function regularize(Request $request)
    {
        try {
            $validated = $request->validate([
                'attendance_id' => 'required|exists:attendances,id',
                'status' => 'required|in:present,absent,leave,work_from_home',
                'remarks' => 'nullable|string',
            ]);

            // $attendance = Attendance::findOrFail($validated['attendance_id']);
            // $attendance->update(['status' => $validated['status'], 'remarks' => $validated['remarks'] ?? null]);

            return response()->json([
                'success' => true,
                'message' => 'Attendance regularized successfully',
                'data' => [
                    // 'attendance' => $attendance,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Regularization failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Get downline attendance for manager
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeamAttendance(Request $request)
    {
        try {
            $manager_id = $request->get('manager_id');
            $date = $request->get('date', today());
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 15);

            // $team = User::where('reporting_to', $manager_id)
            //     ->with(['attendance' => function($q) use ($date) {
            //         $q->whereDate('date', $date);
            //     }])
            //     ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Team attendance retrieved successfully',
                'data' => [
                    // 'team' => $team->items(),
                    'pagination' => [
                        // 'total' => $team->total(),
                        // 'current_page' => $team->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve team attendance',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
