<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DemoSchedulerService;

class SendDemoReminders extends Command
{
    protected $signature = 'demo:send-reminders {--days=3 : Days ahead to check}';
    protected $description = 'Send demo stage reminders and overdue notifications';

    public function handle(DemoSchedulerService $schedulerService): int
    {
        $this->info('Sending demo stage reminders...');

        $results = $schedulerService->sendDailyReminders();

        $this->info("Upcoming reminders sent: {$results['upcoming_reminders']}");
        $this->info("Overdue notifications sent: {$results['overdue_notifications']}");

        return Command::SUCCESS;
    }
}
