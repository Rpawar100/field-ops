<?php

namespace App\Services;

use App\Models\DemoMaster;
use App\Models\DemoDistribution;
use App\Models\DemoFarmerAssignment;
use App\Models\DemoStageActivity;
use App\Models\DemoStageTemplate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DemoSchedulerService
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Create stage activities for a farmer assignment
     *
     * @param DemoFarmerAssignment $assignment
     * @return void
     */
    public function createStageActivities(DemoFarmerAssignment $assignment): void
    {
        $demoMaster = $assignment->getDemoMaster();
        $sowingDate = $assignment->sowing_date ?? $assignment->demoDistribution->start_date;

        foreach ($demoMaster->stageTemplates as $template) {
            $expectedDate = $this->calculateStageDate($sowingDate, $template);

            DemoStageActivity::create([
                'demo_farmer_assignment_id' => $assignment->id,
                'demo_stage_template_id' => $template->id,
                'stage_number' => $template->stage_order,
                'expected_date' => $expectedDate,
                'status' => DemoStageActivity::STATUS_PENDING,
            ]);
        }
    }

    /**
     * Calculate expected date for a stage
     *
     * @param Carbon $sowingDate
     * @param DemoStageTemplate $template
     * @return Carbon
     */
    public function calculateStageDate(Carbon $sowingDate, DemoStageTemplate $template): Carbon
    {
        return $sowingDate->copy()->addDays($template->days_from_start);
    }

    /**
     * Recalculate stage dates when sowing date changes
     *
     * @param DemoFarmerAssignment $assignment
     * @param Carbon $newSowingDate
     * @return void
     */
    public function recalculateStageDates(DemoFarmerAssignment $assignment, Carbon $newSowingDate): void
    {
        $demoMaster = $assignment->getDemoMaster();

        foreach ($assignment->stageActivities as $activity) {
            if ($activity->isPending()) {
                $template = $activity->stageTemplate;
                $newExpectedDate = $this->calculateStageDate($newSowingDate, $template);

                $activity->update(['expected_date' => $newExpectedDate]);
            }
        }

        $assignment->update(['sowing_date' => $newSowingDate]);
    }

    /**
     * Get upcoming stage activities for reminders
     *
     * @param int $daysAhead
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUpcomingStages(int $daysAhead = 2): \Illuminate\Database\Eloquent\Collection
    {
        return DemoStageActivity::pending()
            ->dueWithinDays($daysAhead)
            ->with([
                'stageTemplate',
                'farmerAssignment.farmer',
                'farmerAssignment.demoDistribution.user',
                'farmerAssignment.demoDistribution.demoMaster',
            ])
            ->get();
    }

    /**
     * Get overdue stage activities
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOverdueStages(): \Illuminate\Database\Eloquent\Collection
    {
        return DemoStageActivity::pending()
            ->whereDate('expected_date', '<', now()->toDateString())
            ->with([
                'stageTemplate',
                'farmerAssignment.farmer',
                'farmerAssignment.demoDistribution.user',
                'farmerAssignment.demoDistribution.demoMaster',
            ])
            ->get();
    }

    /**
     * Send reminders for upcoming stages
     * (Run daily via scheduler)
     *
     * @return array
     */
    public function sendDailyReminders(): array
    {
        $results = [
            'upcoming_reminders' => 0,
            'overdue_notifications' => 0,
        ];

        // Send reminders for upcoming stages
        $upcomingStages = $this->getUpcomingStages(2);

        foreach ($upcomingStages as $activity) {
            if ($activity->isReminderDue()) {
                try {
                    $this->notificationService->sendDemoStageReminder($activity);
                    $results['upcoming_reminders']++;
                } catch (\Exception $e) {
                    Log::error('Failed to send demo reminder', [
                        'activity_id' => $activity->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        // Send notifications for overdue stages
        $overdueStages = $this->getOverdueStages();

        foreach ($overdueStages as $activity) {
            try {
                // Mark as overdue
                $activity->markAsOverdue();

                // Send notification
                $this->notificationService->sendOverdueNotification($activity);
                $results['overdue_notifications']++;
            } catch (\Exception $e) {
                Log::error('Failed to send overdue notification', [
                    'activity_id' => $activity->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('Demo reminders sent', $results);

        return $results;
    }

    /**
     * Get stage schedule for a distribution
     *
     * @param DemoDistribution $distribution
     * @return array
     */
    public function getDistributionSchedule(DemoDistribution $distribution): array
    {
        $schedule = [];

        foreach ($distribution->farmerAssignments as $assignment) {
            $farmerSchedule = [
                'farmer_id' => $assignment->farmer_id,
                'farmer_name' => $assignment->farmer->name,
                'sowing_date' => $assignment->sowing_date?->toDateString(),
                'stages' => [],
            ];

            foreach ($assignment->stageActivities as $activity) {
                $farmerSchedule['stages'][] = [
                    'stage_number' => $activity->stage_number,
                    'stage_name' => $activity->stageTemplate->name,
                    'expected_date' => $activity->expected_date->toDateString(),
                    'actual_date' => $activity->actual_date?->toDateString(),
                    'status' => $activity->status,
                    'is_overdue' => $activity->isOverdue(),
                    'days_until' => $activity->getDaysUntilExpected(),
                ];
            }

            $schedule[] = $farmerSchedule;
        }

        return $schedule;
    }

    /**
     * Get demo progress summary
     *
     * @param DemoDistribution $distribution
     * @return array
     */
    public function getProgressSummary(DemoDistribution $distribution): array
    {
        $totalStages = 0;
        $completedStages = 0;
        $pendingStages = 0;
        $overdueStages = 0;

        foreach ($distribution->farmerAssignments as $assignment) {
            foreach ($assignment->stageActivities as $activity) {
                $totalStages++;

                switch ($activity->status) {
                    case DemoStageActivity::STATUS_COMPLETED:
                        $completedStages++;
                        break;
                    case DemoStageActivity::STATUS_OVERDUE:
                        $overdueStages++;
                        break;
                    case DemoStageActivity::STATUS_PENDING:
                        if ($activity->isOverdue()) {
                            $overdueStages++;
                        } else {
                            $pendingStages++;
                        }
                        break;
                }
            }
        }

        return [
            'total_stages' => $totalStages,
            'completed' => $completedStages,
            'pending' => $pendingStages,
            'overdue' => $overdueStages,
            'completion_percentage' => $totalStages > 0
                ? round(($completedStages / $totalStages) * 100, 1)
                : 0,
            'farmer_count' => $distribution->farmerAssignments->count(),
            'active_farmers' => $distribution->farmerAssignments
                ->where('status', DemoFarmerAssignment::STATUS_ACTIVE)
                ->count(),
        ];
    }

    /**
     * Check and update distribution status
     *
     * @param DemoDistribution $distribution
     * @return void
     */
    public function updateDistributionStatus(DemoDistribution $distribution): void
    {
        $progress = $this->getProgressSummary($distribution);

        // If all stages completed, mark distribution as completed
        if ($progress['completion_percentage'] >= 100) {
            $distribution->complete();

            // Mark all assignments as completed
            foreach ($distribution->farmerAssignments as $assignment) {
                if ($assignment->status !== DemoFarmerAssignment::STATUS_DROPPED) {
                    $assignment->complete();
                }
            }
        }
    }

    /**
     * Get today's demo activities for a user
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTodaysDemoActivities(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return DemoStageActivity::pending()
            ->dueToday()
            ->whereHas('farmerAssignment.demoDistribution', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with([
                'stageTemplate',
                'farmerAssignment.farmer',
                'farmerAssignment.demoDistribution.demoMaster',
            ])
            ->orderBy('expected_date')
            ->get();
    }

    /**
     * Get demo calendar for a user
     *
     * @param int $userId
     * @param string $month Format: Y-m
     * @return array
     */
    public function getDemoCalendar(int $userId, string $month): array
    {
        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $activities = DemoStageActivity::whereHas('farmerAssignment.demoDistribution', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->whereBetween('expected_date', [$startDate, $endDate])
            ->with([
                'stageTemplate',
                'farmerAssignment.farmer',
                'farmerAssignment.demoDistribution.demoMaster',
            ])
            ->get();

        $calendar = [];

        foreach ($activities as $activity) {
            $dateKey = $activity->expected_date->toDateString();

            if (!isset($calendar[$dateKey])) {
                $calendar[$dateKey] = [];
            }

            $calendar[$dateKey][] = [
                'id' => $activity->id,
                'stage_number' => $activity->stage_number,
                'stage_name' => $activity->stageTemplate->name,
                'farmer_name' => $activity->farmerAssignment->farmer->name,
                'demo_name' => $activity->farmerAssignment->demoDistribution->demoMaster->name,
                'status' => $activity->status,
            ];
        }

        return $calendar;
    }
}
