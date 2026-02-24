<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Beat;
use App\Services\NotificationService;

class SendBeatScheduleNotifications extends Command
{
    protected $signature = 'beat:send-schedule';
    protected $description = 'Send today\'s beat schedule notifications to field staff';

    public function handle(NotificationService $notificationService): int
    {
        $this->info('Sending beat schedule notifications...');

        $todayBeats = Beat::forToday()
            ->active()
            ->with(['user', 'farmers', 'retailers'])
            ->get();

        $sent = 0;
        foreach ($todayBeats as $beat) {
            if ($beat->user && $beat->user->firebase_token) {
                $success = $notificationService->sendBeatScheduleNotification(
                    $beat->user,
                    $beat->name,
                    $beat->farmers->count(),
                    $beat->retailers->count()
                );

                if ($success) {
                    $sent++;
                }
            }
        }

        $this->info("Notifications sent: {$sent}");

        return Command::SUCCESS;
    }
}
