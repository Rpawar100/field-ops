<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Attendance;
use App\Services\NotificationService;
use Carbon\Carbon;

class SendAttendanceReminders extends Command
{
    protected $signature = 'attendance:send-reminders';
    protected $description = 'Send morning attendance reminders to field staff';

    public function handle(NotificationService $notificationService): int
    {
        $this->info('Sending attendance reminders...');

        // Get active field staff who haven't checked in today
        $today = Carbon::today()->toDateString();

        $usersWithoutCheckIn = User::active()
            ->whereIn('role', ['fa', 'tsl'])
            ->where('app_access_enabled', true)
            ->whereNotNull('firebase_token')
            ->whereDoesntHave('attendances', function ($query) use ($today) {
                $query->forDate($today);
            })
            ->get();

        $sent = 0;
        foreach ($usersWithoutCheckIn as $user) {
            if ($notificationService->sendAttendanceReminder($user)) {
                $sent++;
            }
        }

        $this->info("Reminders sent: {$sent}");

        return Command::SUCCESS;
    }
}
