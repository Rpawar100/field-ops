<?php

namespace App\Services;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AttendanceService
{
    /**
     * Check in a user
     *
     * @param User $user
     * @param float $latitude
     * @param float $longitude
     * @param string|null $address
     * @return Attendance
     */
    public function checkIn(
        User $user,
        float $latitude,
        float $longitude,
        ?string $address = null
    ): Attendance {
        $today = now()->toDateString();

        // Check for existing attendance
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($attendance) {
            // Update existing record
            $attendance->update([
                'check_in_time' => now(),
                'check_in_latitude' => $latitude,
                'check_in_longitude' => $longitude,
                'check_in_address' => $address,
            ]);
        } else {
            // Create new attendance record
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'status' => Attendance::STATUS_PENDING,
                'check_in_time' => now(),
                'check_in_latitude' => $latitude,
                'check_in_longitude' => $longitude,
                'check_in_address' => $address,
                'activity_count' => 0,
            ]);
        }

        return $attendance;
    }

    /**
     * Check out a user
     *
     * @param Attendance $attendance
     * @param float $latitude
     * @param float $longitude
     * @param string|null $address
     * @return Attendance
     */
    public function checkOut(
        Attendance $attendance,
        float $latitude,
        float $longitude,
        ?string $address = null
    ): Attendance {
        // Update activity count before check-out
        $attendance->updateActivityCount();

        $attendance->update([
            'check_out_time' => now(),
            'check_out_latitude' => $latitude,
            'check_out_longitude' => $longitude,
            'check_out_address' => $address,
        ]);

        // Validate attendance status
        $this->validateAttendance($attendance);

        return $attendance->fresh();
    }

    /**
     * Validate attendance based on activity count
     *
     * @param Attendance $attendance
     * @return void
     */
    public function validateAttendance(Attendance $attendance): void
    {
        $attendance->updateActivityCount();

        if ($attendance->isValid()) {
            $attendance->update(['status' => Attendance::STATUS_VALID]);
        } else {
            // If checked out with insufficient activities, mark as invalid
            if ($attendance->hasCheckedOut()) {
                $attendance->update(['status' => Attendance::STATUS_INVALID]);
            }
        }
    }

    /**
     * Get attendance summary for a user within date range
     *
     * @param User $user
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getSummary(User $user, string $startDate, string $endDate): array
    {
        $attendances = Attendance::where('user_id', $user->id)
            ->betweenDates($startDate, $endDate)
            ->get();

        $totalDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
        $workingDays = $this->getWorkingDays($startDate, $endDate);

        $validDays = $attendances->where('status', Attendance::STATUS_VALID)->count();
        $invalidDays = $attendances->where('status', Attendance::STATUS_INVALID)->count();
        $pendingDays = $attendances->where('status', Attendance::STATUS_PENDING)->count();
        $absentDays = $workingDays - $attendances->count();

        $totalActivities = $attendances->sum('activity_count');
        $avgActivitiesPerDay = $attendances->count() > 0
            ? round($totalActivities / $attendances->count(), 1)
            : 0;

        $totalHours = $attendances
            ->filter(fn($a) => $a->getWorkingHours() !== null)
            ->sum(fn($a) => $a->getWorkingHours());

        $avgHoursPerDay = $attendances->filter(fn($a) => $a->hasCheckedOut())->count() > 0
            ? round($totalHours / $attendances->filter(fn($a) => $a->hasCheckedOut())->count(), 1)
            : 0;

        return [
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_days' => $totalDays,
                'working_days' => $workingDays,
            ],
            'attendance' => [
                'present' => $attendances->count(),
                'valid' => $validDays,
                'invalid' => $invalidDays,
                'pending' => $pendingDays,
                'absent' => max(0, $absentDays),
            ],
            'activities' => [
                'total' => $totalActivities,
                'average_per_day' => $avgActivitiesPerDay,
            ],
            'working_hours' => [
                'total' => round($totalHours, 1),
                'average_per_day' => $avgHoursPerDay,
            ],
            'attendance_percentage' => $workingDays > 0
                ? round(($validDays / $workingDays) * 100, 1)
                : 0,
        ];
    }

    /**
     * Get working days count (excluding Sundays)
     *
     * @param string $startDate
     * @param string $endDate
     * @return int
     */
    protected function getWorkingDays(string $startDate, string $endDate): int
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $workingDays = 0;

        while ($start->lte($end)) {
            // Count all days except Sunday (0)
            if ($start->dayOfWeek !== Carbon::SUNDAY) {
                $workingDays++;
            }
            $start->addDay();
        }

        return $workingDays;
    }

    /**
     * Get team attendance summary for managers
     *
     * @param User $manager
     * @param string $date
     * @return array
     */
    public function getTeamAttendanceSummary(User $manager, string $date): array
    {
        $subordinateIds = $manager->getAllSubordinateIds();

        $attendances = Attendance::whereIn('user_id', $subordinateIds)
            ->forDate($date)
            ->with('user:id,name,employee_code,role')
            ->get();

        $presentUsers = $attendances->pluck('user_id')->toArray();
        $absentUsers = array_diff($subordinateIds, $presentUsers);

        return [
            'date' => $date,
            'total_team' => count($subordinateIds),
            'summary' => [
                'present' => count($presentUsers),
                'checked_in' => $attendances->filter(fn($a) => $a->hasCheckedIn())->count(),
                'checked_out' => $attendances->filter(fn($a) => $a->hasCheckedOut())->count(),
                'valid' => $attendances->filter(fn($a) => $a->isValid())->count(),
                'absent' => count($absentUsers),
            ],
            'present_users' => $attendances->map(fn($a) => [
                'user_id' => $a->user_id,
                'user_name' => $a->user->name,
                'employee_code' => $a->user->employee_code,
                'check_in_time' => $a->check_in_time?->format('H:i'),
                'check_out_time' => $a->check_out_time?->format('H:i'),
                'activity_count' => $a->activity_count,
                'status' => $a->status,
            ]),
            'absent_user_ids' => $absentUsers,
        ];
    }

    /**
     * Mark overdue pending attendances as invalid
     * (Run daily via scheduler)
     *
     * @return int Number of updated records
     */
    public function markOverdueAsInvalid(): int
    {
        $yesterday = now()->subDay()->toDateString();

        return Attendance::where('status', Attendance::STATUS_PENDING)
            ->whereDate('date', '<', $yesterday)
            ->update(['status' => Attendance::STATUS_INVALID]);
    }

    /**
     * Calculate attendance percentage for a user
     *
     * @param User $user
     * @param int $days Number of days to look back
     * @return float
     */
    public function getAttendancePercentage(User $user, int $days = 30): float
    {
        $startDate = now()->subDays($days)->toDateString();
        $endDate = now()->toDateString();

        $workingDays = $this->getWorkingDays($startDate, $endDate);

        $validDays = Attendance::where('user_id', $user->id)
            ->betweenDates($startDate, $endDate)
            ->valid()
            ->count();

        return $workingDays > 0 ? round(($validDays / $workingDays) * 100, 1) : 0;
    }
}
