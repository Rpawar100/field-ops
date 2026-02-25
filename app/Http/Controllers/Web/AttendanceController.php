<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AttendanceController extends Controller
{
    /**
     * Display a listing of attendance records.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $attendances = Attendance::with('user')
                ->orderByDesc('attendance_date')
                ->paginate(15);
        } catch (\Throwable $e) {
            $attendances = new LengthAwarePaginator([], 0, 15);
        }

        return view('attendance.index', compact('attendances'));
    }

    /**
     * Display attendance in calendar view.
     */
    public function calendar(): \Illuminate\Contracts\View\View
    {
        try {
            $attendances = Attendance::with('user')
                ->whereMonth('attendance_date', now()->month)
                ->whereYear('attendance_date', now()->year)
                ->get();

            // Build FullCalendar events array
            $colorMap = [
                'individual_work' => '#198754',
                'joint_work'      => '#198754',
                'meeting'         => '#0dcaf0',
                'work_from_home'  => '#198754',
                'leave'           => '#ffc107',
                'half_day'        => '#0dcaf0',
                'holiday'         => '#6c757d',
                'week_off'        => '#6c757d',
            ];

            $events = $attendances->map(function ($att) use ($colorMap) {
                $type = $att->attendance_type ?? 'absent';
                return [
                    'title' => ($att->user->name ?? 'Unknown') . ' - ' . ucfirst(str_replace('_', ' ', $type)),
                    'start' => $att->attendance_date ? $att->attendance_date->format('Y-m-d') : null,
                    'color' => $colorMap[$type] ?? '#dc3545',
                    'url'   => route('attendance.index', ['attendance_date' => $att->attendance_date?->format('Y-m-d')]),
                ];
            })->filter(fn($e) => $e['start'] !== null)->values();
        } catch (\Throwable $e) {
            $attendances = collect();
            $events = collect();
        }

        return view('attendance.calendar', compact('attendances', 'events'));
    }

    /**
     * Display attendance on a map (GPS-based check-in locations).
     */
    public function map(): \Illuminate\Contracts\View\View
    {
        try {
            $filterDate = request('attendance_date', today()->format('Y-m-d'));
            $attendances = Attendance::with('user')
                ->whereDate('attendance_date', $filterDate)
                ->whereNotNull('check_in_latitude')
                ->whereNotNull('check_in_longitude')
                ->get();

            // Build locations array for Leaflet map
            $locations = $attendances->map(function ($att) {
                return [
                    'check_in_latitude'  => (float) $att->check_in_latitude,
                    'check_in_longitude' => (float) $att->check_in_longitude,
                    'user_name'          => $att->user->name ?? 'Unknown',
                    'check_in_time'      => $att->check_in_time ? $att->check_in_time->format('h:i A') : '-',
                    'attendance_type'    => ucfirst(str_replace('_', ' ', $att->attendance_type ?? 'unknown')),
                ];
            })->values();
        } catch (\Throwable $e) {
            $attendances = collect();
            $locations = collect();
        }

        return view('attendance.map', compact('attendances', 'locations'));
    }

    /**
     * Display attendance report with summary statistics.
     */
    public function report(): \Illuminate\Contracts\View\View
    {
        try {
            $totalUsers = User::where('status', 'active')->count();
            $todayPresent = Attendance::whereDate('attendance_date', today())
                ->distinct('user_id')
                ->count('user_id');
            $monthlyAttendance = Attendance::whereMonth('attendance_date', now()->month)
                ->whereYear('attendance_date', now()->year)
                ->get()
                ->groupBy('user_id');

            $report = [
                'total_users'    => $totalUsers,
                'today_present'  => $todayPresent,
                'today_absent'   => max(0, $totalUsers - $todayPresent),
                'monthly_data'   => $monthlyAttendance,
                'attendance_pct' => $totalUsers > 0 ? round(($todayPresent / $totalUsers) * 100, 1) : 0,
            ];
        } catch (\Throwable $e) {
            $report = [
                'total_users'    => 0,
                'today_present'  => 0,
                'today_absent'   => 0,
                'monthly_data'   => collect(),
                'attendance_pct' => 0,
            ];
        }

        return view('attendance.report', compact('report'));
    }

    /**
     * Display attendance records for a specific user.
     */
    public function userAttendance(User $user): \Illuminate\Contracts\View\View
    {
        try {
            $attendances = Attendance::where('user_id', $user->id)
                ->orderByDesc('attendance_date')
                ->paginate(15);
        } catch (\Throwable $e) {
            $attendances = new LengthAwarePaginator([], 0, 15);
        }

        return view('attendance.user', compact('user', 'attendances'));
    }

    /**
     * Regularize an attendance record (correct discrepancies).
     */
    public function regularize(Request $request, Attendance $attendance): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'check_in_time'  => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'attendance_type' => 'nullable|string|max:50',
            'remarks'         => 'nullable|string|max:500',
            'manager_remarks' => 'nullable|string|max:500',
        ]);

        try {
            $attendance->update($validated);

            return redirect()->route('attendance.index')->with('success', 'Attendance regularized successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('attendance.index')->with('error', 'Failed to regularize attendance: ' . $e->getMessage());
        }
    }
}
