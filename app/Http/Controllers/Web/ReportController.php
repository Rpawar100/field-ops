<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Attendance;
use App\Models\DemoDistribution;
use App\Models\DemoFarmerAssignment;
use App\Models\Farmer;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display the reports overview page.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $totalActivities = Activity::count();
            $totalAttendances = Attendance::count();
            $totalDistributions = DemoDistribution::count();
            $totalFarmers = Farmer::count();
            $totalBeats = \App\Models\Beat::count();

            // Monthly stats
            $monthlyActivities = Activity::whereMonth('execution_date', now()->month)
                ->whereYear('execution_date', now()->year)
                ->count();
            $totalUsers = User::where('status', 'active')->count();
            $monthlyPresentDays = Attendance::whereMonth('attendance_date', now()->month)
                ->whereYear('attendance_date', now()->year)
                ->distinct('user_id')
                ->count('user_id');
            $daysInMonth = now()->daysInMonth;
            $daysSoFar = min(now()->day, $daysInMonth);
            $monthlyAttendanceAvg = ($totalUsers > 0 && $daysSoFar > 0)
                ? round(($monthlyPresentDays / $totalUsers) * 100, 1) . '%'
                : '0%';

            $monthlyDemoAssignments = DemoFarmerAssignment::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            $activeBeatCount = \App\Models\Beat::where('status', 'active')->count();
            $monthlyBeatCoverage = $totalBeats > 0
                ? round(($activeBeatCount / $totalBeats) * 100, 1) . '%'
                : '0%';

            $stats = [
                'total_activities'        => $totalActivities,
                'total_users'             => User::count(),
                'total_farmers'           => $totalFarmers,
                'total_distributions'     => $totalDistributions,
                'total_attendances'       => $totalAttendances,
                'total_demo_assignments'  => DemoFarmerAssignment::count(),
                'total_beats'             => $totalBeats,
                'monthly_activities'      => $monthlyActivities,
                'monthly_attendance_avg'  => $monthlyAttendanceAvg,
                'monthly_demo_assignments' => $monthlyDemoAssignments,
                'monthly_beat_coverage'   => $monthlyBeatCoverage,
            ];
        } catch (\Throwable $e) {
            $stats = [
                'total_activities'        => 0,
                'total_users'             => 0,
                'total_farmers'           => 0,
                'total_distributions'     => 0,
                'total_attendances'       => 0,
                'total_demo_assignments'  => 0,
                'total_beats'             => 0,
                'monthly_activities'      => 0,
                'monthly_attendance_avg'  => '0%',
                'monthly_demo_assignments' => 0,
                'monthly_beat_coverage'   => '0%',
            ];
        }

        return view('reports.index', compact('stats'));
    }

    /**
     * Display activities report.
     */
    public function activities(): \Illuminate\Contracts\View\View
    {
        try {
            $activities = Activity::with(['user', 'activityType', 'farmer', 'retailer', 'beat'])
                ->orderByDesc('execution_date')
                ->paginate(25);

            $activityStats = [
                'total'      => Activity::count(),
                'today'      => Activity::whereDate('execution_date', today())->count(),
                'this_week'  => Activity::whereBetween('execution_date', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ])->count(),
                'this_month' => Activity::whereMonth('execution_date', now()->month)
                    ->whereYear('execution_date', now()->year)
                    ->count(),
            ];

            $activityTypes = \App\Models\ActivityType::orderBy('name')->get(['id', 'name']);
            $users = User::where('status', 'active')->orderBy('name')->get(['id', 'name']);
        } catch (\Throwable $e) {
            $activities = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 25);
            $activityStats = ['total' => 0, 'today' => 0, 'this_week' => 0, 'this_month' => 0];
            $activityTypes = collect();
            $users = collect();
        }

        return view('reports.activities', compact('activities', 'activityStats', 'activityTypes', 'users'));
    }

    /**
     * Display attendance report.
     */
    public function attendance(): \Illuminate\Contracts\View\View
    {
        try {
            $totalUsers = User::where('status', 'active')->count();
            $todayPresent = Attendance::whereDate('attendance_date', today())
                ->distinct('user_id')
                ->count('user_id');

            $attendances = Attendance::with('user')
                ->orderByDesc('attendance_date')
                ->paginate(25);

            $summary = [
                'present'    => $todayPresent,
                'absent'     => max(0, $totalUsers - $todayPresent),
                'on_leave'   => Attendance::whereDate('attendance_date', today())
                    ->whereIn('attendance_type', ['leave', 'sick_leave'])
                    ->distinct('user_id')
                    ->count('user_id'),
                'percentage' => $totalUsers > 0 ? round(($todayPresent / $totalUsers) * 100, 1) . '%' : '0%',
            ];

            $users = User::where('status', 'active')->orderBy('name')->get(['id', 'name']);

            $attendanceTypes = ['present', 'field_work', 'office', 'leave', 'sick_leave', 'absent', 'half_day', 'holiday'];
        } catch (\Throwable $e) {
            $attendances = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 25);
            $summary = [
                'present'    => 0,
                'absent'     => 0,
                'on_leave'   => 0,
                'percentage' => '0%',
            ];
            $users = collect();
            $attendanceTypes = [];
        }

        return view('reports.attendance', compact('attendances', 'summary', 'users', 'attendanceTypes'));
    }

    /**
     * Display demo report.
     */
    public function demo(): \Illuminate\Contracts\View\View
    {
        try {
            $assignments = DemoFarmerAssignment::with(['demoMaster.crop', 'farmer'])
                ->orderByDesc('created_at')
                ->paginate(25);

            $crops = \App\Models\Crop::orderBy('name')->get(['id', 'name']);
        } catch (\Throwable $e) {
            $assignments = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 25);
            $crops = collect();
        }

        return view('reports.demo', compact('assignments', 'crops'));
    }

    /**
     * Display coverage report (beat/territory coverage analysis).
     */
    public function coverage(): \Illuminate\Contracts\View\View
    {
        try {
            $territories = \App\Models\ZrthHierarchy::orderBy('name')->get(['id', 'name', 'level']);

            // Build flat coverage data array for the view's fallback @forelse($coverage) path
            $coverage = [];
            $zrthNodes = \App\Models\ZrthHierarchy::orderBy('level')->orderBy('name')->get();
            foreach ($zrthNodes as $node) {
                $beatCount = \App\Models\Beat::where('zrth_hierarchy_id', $node->id)->count();
                $plannedVisits = Activity::where('zrth_hierarchy_id', $node->id)->count();
                $completedVisits = Activity::where('zrth_hierarchy_id', $node->id)
                    ->where('status', 'completed')
                    ->count();
                $coveragePct = $plannedVisits > 0 ? round(($completedVisits / $plannedVisits) * 100, 1) : 0;

                $coverage[] = [
                    'name'              => $node->name,
                    'level'             => $node->level ?? 'hq',
                    'total_beats'       => $beatCount,
                    'planned_visits'    => $plannedVisits,
                    'completed_visits'  => $completedVisits,
                    'coverage_percent'  => $coveragePct,
                ];
            }

            // coverageData is set to an empty array so the @forelse($coverageData) loop is skipped
            // and the fallback @forelse($coverage) is used instead
            $coverageData = [];
        } catch (\Throwable $e) {
            $coverageData = [];
            $coverage = [];
            $territories = collect();
        }

        return view('reports.coverage', compact('coverageData', 'coverage', 'territories'));
    }

    /**
     * Export reports data (CSV download).
     */
    public function export(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
    {
        try {
            $type = $request->get('type', 'activities');
            $csvContent = '';

            switch ($type) {
                case 'activities':
                    $csvContent = "ID,Activity Type,User,Farmer,Execution Date,Status\n";
                    $records = Activity::with(['user', 'activityType', 'farmer'])->limit(1000)->get();
                    foreach ($records as $record) {
                        $csvContent .= implode(',', [
                            $record->id,
                            '"' . str_replace('"', '""', optional($record->activityType)->name ?? '') . '"',
                            '"' . (optional($record->user)->name ?? 'N/A') . '"',
                            '"' . (optional($record->farmer)->name ?? 'N/A') . '"',
                            $record->execution_date ?? '',
                            $record->status ?? '',
                        ]) . "\n";
                    }
                    break;

                case 'attendance':
                    $csvContent = "ID,User,Attendance Date,Check In Time,Check Out Time,Type,Remarks\n";
                    $records = Attendance::with('user')->limit(1000)->get();
                    foreach ($records as $record) {
                        $csvContent .= implode(',', [
                            $record->id,
                            '"' . (optional($record->user)->name ?? 'N/A') . '"',
                            $record->attendance_date ?? '',
                            $record->check_in_time ?? '',
                            $record->check_out_time ?? '',
                            '"' . ($record->attendance_type ?? '') . '"',
                            '"' . str_replace('"', '""', $record->remarks ?? '') . '"',
                        ]) . "\n";
                    }
                    break;

                default:
                    return redirect()->route('reports.index')->with('error', 'Invalid export type.');
            }

            $tempFile = tempnam(sys_get_temp_dir(), 'report_export_');
            file_put_contents($tempFile, $csvContent);

            return response()->download($tempFile, "report_{$type}_" . now()->format('Ymd_His') . '.csv')
                ->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            return redirect()->route('reports.index')->with('error', 'Export failed: ' . $e->getMessage());
        }
    }
}
