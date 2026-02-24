<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // KPI stat data — uses real models when available, falls back to demo values
        $stats = [
            'total_users'        => $this->safeCount('App\Models\User'),
            'active_farmers'     => $this->safeCount('App\Models\Farmer', ['status' => 'active']),
            'todays_activities'  => $this->safeTodayCount('App\Models\Activity'),
            'attendance_percent' => $this->calcAttendancePercent(),
        ];

        // Recent activities — last 10
        $recentActivities = $this->getRecentActivities();

        return view('admin.dashboard', compact('stats', 'recentActivities'));
    }

    /**
     * Safely count a model, returning a fallback if the model does not exist.
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
     * Count today's records for a model (by created_at).
     */
    private function safeTodayCount(string $model): int
    {
        try {
            if (!class_exists($model)) {
                return 0;
            }
            return $model::whereDate('created_at', today())->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * Calculate today's attendance percentage.
     */
    private function calcAttendancePercent(): float
    {
        try {
            if (!class_exists('App\Models\Attendance') || !class_exists('App\Models\User')) {
                return 0.0;
            }
            $totalUsers = \App\Models\User::count();
            if ($totalUsers === 0) {
                return 0.0;
            }
            $presentToday = \App\Models\Attendance::whereDate('check_in', today())->distinct('user_id')->count('user_id');
            return round(($presentToday / $totalUsers) * 100, 1);
        } catch (\Throwable $e) {
            return 0.0;
        }
    }

    /**
     * Get recent activities for the dashboard table.
     */
    private function getRecentActivities(): array
    {
        try {
            if (!class_exists('App\Models\Activity')) {
                return $this->demoActivities();
            }
            $activities = \App\Models\Activity::with('user')
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();

            if ($activities->isEmpty()) {
                return $this->demoActivities();
            }

            return $activities->map(function ($a) {
                return [
                    'id'          => $a->id,
                    'title'       => $a->title ?? $a->activity_type ?? 'Activity #' . $a->id,
                    'type'        => $a->activity_type ?? 'General',
                    'user'        => optional($a->user)->name ?? 'N/A',
                    'status'      => $a->status ?? 'Pending',
                    'created_at'  => $a->created_at->format('d M Y, h:i A'),
                ];
            })->toArray();
        } catch (\Throwable $e) {
            return $this->demoActivities();
        }
    }

    /**
     * Demo/placeholder activities when no real data exists.
     */
    private function demoActivities(): array
    {
        return [
            [
                'id'         => 1,
                'title'      => 'Farmer Registration — Village Khambhat',
                'type'       => 'Farmer Visit',
                'user'       => 'Rajesh Kumar',
                'status'     => 'Completed',
                'created_at' => now()->format('d M Y, h:i A'),
            ],
            [
                'id'         => 2,
                'title'      => 'Demo Distribution — Lot #DL-2026-014',
                'type'       => 'Demo',
                'user'       => 'Sunil Patel',
                'status'     => 'In Progress',
                'created_at' => now()->subHours(2)->format('d M Y, h:i A'),
            ],
            [
                'id'         => 3,
                'title'      => 'Retailer KYC Update — Agro Traders',
                'type'       => 'Retailer Visit',
                'user'       => 'Meena Singh',
                'status'     => 'Completed',
                'created_at' => now()->subHours(5)->format('d M Y, h:i A'),
            ],
            [
                'id'         => 4,
                'title'      => 'Product Awareness Camp — Block C',
                'type'       => 'PSA',
                'user'       => 'Amit Verma',
                'status'     => 'Pending',
                'created_at' => now()->subDay()->format('d M Y, h:i A'),
            ],
            [
                'id'         => 5,
                'title'      => 'Soil Testing Follow-up — Dist. Anand',
                'type'       => 'Farmer Visit',
                'user'       => 'Kavita Sharma',
                'status'     => 'Completed',
                'created_at' => now()->subDay()->subHours(3)->format('d M Y, h:i A'),
            ],
        ];
    }
}
