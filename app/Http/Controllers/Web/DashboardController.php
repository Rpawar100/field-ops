<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard with KPI stats and recent activities.
     */
    public function index(): View
    {
        $stats = [
            'total_users'        => $this->safeCount('App\Models\User'),
            'active_users'       => $this->safeCount('App\Models\User', ['status' => 'active']),
            'active_farmers'     => $this->safeCount('App\Models\Farmer', ['status' => 'active']),
            'active_retailers'   => $this->safeCount('App\Models\Retailer', ['status' => 'active']),
            'todays_activities'  => $this->safeTodayActivityCount(),
            'attendance_percent' => $this->calcAttendancePercent(),
        ];

        $recentActivities = $this->getRecentActivities();

        return view('dashboard.index', compact('stats', 'recentActivities'));
    }

    /**
     * Display the user profile page.
     */
    public function profile(): View
    {
        return view('dashboard.profile');
    }

    /**
     * Display the settings page.
     */
    public function settings(): View
    {
        return view('dashboard.settings');
    }

    /**
     * Safely count a model, returning a fallback if the model/table does not exist.
     */
    private function safeCount(string $model, array $where = []): int
    {
        try {
            if (!class_exists($model)) {
                return 0;
            }
            $query = $model::query();
            foreach ($where as $col => $val) {
                $query->where($col, $val);
            }
            return $query->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * Count today's activities by execution_date (actual schema column).
     */
    private function safeTodayActivityCount(): int
    {
        try {
            if (!class_exists('App\Models\Activity')) {
                return 0;
            }
            return \App\Models\Activity::whereDate('execution_date', today())->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * Calculate today's attendance percentage using attendance_date column.
     */
    private function calcAttendancePercent(): float
    {
        try {
            if (!class_exists('App\Models\Attendance') || !class_exists('App\Models\User')) {
                return 0.0;
            }
            $totalUsers = \App\Models\User::where('status', 'active')->count();
            if ($totalUsers === 0) {
                return 0.0;
            }
            $presentToday = \App\Models\Attendance::whereDate('attendance_date', today())
                ->distinct('user_id')
                ->count('user_id');

            return round(($presentToday / $totalUsers) * 100, 1);
        } catch (\Throwable $e) {
            return 0.0;
        }
    }

    /**
     * Get recent activities for the dashboard table.
     * Returns an Eloquent Collection of Activity models (or a collection of stdClass for demo data).
     */
    private function getRecentActivities()
    {
        try {
            if (!class_exists('App\Models\Activity')) {
                return $this->demoActivities();
            }
            $activities = \App\Models\Activity::with(['user', 'activityType'])
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();

            if ($activities->isEmpty()) {
                return $this->demoActivities();
            }

            return $activities;
        } catch (\Throwable $e) {
            return $this->demoActivities();
        }
    }

    /**
     * Demo/placeholder activities when no real data exists.
     * Returns a collection of stdClass objects matching Activity model shape.
     */
    private function demoActivities()
    {
        $items = [
            (object)[
                'id'             => 1,
                'execution_date' => now()->format('Y-m-d'),
                'activityType'   => (object)['name' => 'Farmer Visit'],
                'user'           => (object)['name' => 'Rajesh Kumar'],
                'status'         => 'completed',
            ],
            (object)[
                'id'             => 2,
                'execution_date' => now()->subHours(2)->format('Y-m-d'),
                'activityType'   => (object)['name' => 'Demo'],
                'user'           => (object)['name' => 'Sunil Patel'],
                'status'         => 'submitted',
            ],
            (object)[
                'id'             => 3,
                'execution_date' => now()->subHours(5)->format('Y-m-d'),
                'activityType'   => (object)['name' => 'Retailer Visit'],
                'user'           => (object)['name' => 'Meena Singh'],
                'status'         => 'completed',
            ],
            (object)[
                'id'             => 4,
                'execution_date' => now()->subDay()->format('Y-m-d'),
                'activityType'   => (object)['name' => 'PSA'],
                'user'           => (object)['name' => 'Amit Verma'],
                'status'         => 'draft',
            ],
            (object)[
                'id'             => 5,
                'execution_date' => now()->subDay()->subHours(3)->format('Y-m-d'),
                'activityType'   => (object)['name' => 'Farmer Visit'],
                'user'           => (object)['name' => 'Kavita Sharma'],
                'status'         => 'completed',
            ],
        ];

        return collect($items);
    }
}
