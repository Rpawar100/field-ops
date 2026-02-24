<?php

namespace App\Services;

use App\Models\User;
use App\Models\DemoStageActivity;
use App\Models\DemoFarmerAssignment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Firebase Cloud Messaging Server Key
     */
    protected string $fcmServerKey;

    /**
     * FCM API URL
     */
    protected string $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

    public function __construct()
    {
        $this->fcmServerKey = config('services.firebase.server_key', '');
    }

    /**
     * Send push notification to a user
     *
     * @param User $user
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendToUser(User $user, string $title, string $body, array $data = []): bool
    {
        if (!$user->firebase_token) {
            Log::warning('User has no Firebase token', ['user_id' => $user->id]);
            return false;
        }

        return $this->send(
            $user->firebase_token,
            $title,
            $body,
            $data
        );
    }

    /**
     * Send push notification to multiple users
     *
     * @param Collection|array $users
     * @param string $title
     * @param string $body
     * @param array $data
     * @return array Results
     */
    public function sendToUsers($users, string $title, string $body, array $data = []): array
    {
        $results = ['success' => 0, 'failed' => 0];

        foreach ($users as $user) {
            if ($this->sendToUser($user, $title, $body, $data)) {
                $results['success']++;
            } else {
                $results['failed']++;
            }
        }

        return $results;
    }

    /**
     * Send notification via Firebase Cloud Messaging
     *
     * @param string $token
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    protected function send(string $token, string $title, string $body, array $data = []): bool
    {
        if (empty($this->fcmServerKey)) {
            Log::error('Firebase server key not configured');
            return false;
        }

        $payload = [
            'to' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
                'badge' => 1,
            ],
            'data' => array_merge($data, [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ]),
            'priority' => 'high',
        ];

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->fcmUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: key=' . $this->fcmServerKey,
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $result = json_decode($response, true);
                if (isset($result['success']) && $result['success'] > 0) {
                    Log::info('Push notification sent', [
                        'token' => substr($token, 0, 20) . '...',
                        'title' => $title,
                    ]);
                    return true;
                }
            }

            Log::warning('Push notification failed', [
                'http_code' => $httpCode,
                'response' => $response,
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Push notification exception', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send demo stage reminder
     *
     * @param DemoStageActivity $activity
     * @return bool
     */
    public function sendDemoStageReminder(DemoStageActivity $activity): bool
    {
        $assignment = $activity->farmerAssignment;
        $distribution = $assignment->demoDistribution;
        $user = $distribution->user;
        $farmer = $assignment->farmer;
        $stage = $activity->stageTemplate;

        $title = 'Demo Stage Reminder';
        $body = sprintf(
            '%s - Stage %d (%s) for farmer %s is due on %s',
            $distribution->demoMaster->name,
            $activity->stage_number,
            $stage->name,
            $farmer->name,
            $activity->expected_date->format('d M Y')
        );

        $data = [
            'type' => 'demo_stage_reminder',
            'activity_id' => $activity->id,
            'assignment_id' => $assignment->id,
            'distribution_id' => $distribution->id,
            'farmer_id' => $farmer->id,
        ];

        return $this->sendToUser($user, $title, $body, $data);
    }

    /**
     * Send overdue stage notification
     *
     * @param DemoStageActivity $activity
     * @return bool
     */
    public function sendOverdueNotification(DemoStageActivity $activity): bool
    {
        $assignment = $activity->farmerAssignment;
        $distribution = $assignment->demoDistribution;
        $user = $distribution->user;
        $farmer = $assignment->farmer;

        $title = 'Overdue Demo Stage';
        $body = sprintf(
            'Stage %d for %s (farmer: %s) is overdue. Please complete it as soon as possible.',
            $activity->stage_number,
            $distribution->demoMaster->name,
            $farmer->name
        );

        $data = [
            'type' => 'demo_stage_overdue',
            'activity_id' => $activity->id,
            'farmer_id' => $farmer->id,
        ];

        return $this->sendToUser($user, $title, $body, $data);
    }

    /**
     * Send attendance reminder (daily morning reminder)
     *
     * @param User $user
     * @return bool
     */
    public function sendAttendanceReminder(User $user): bool
    {
        $title = 'Good Morning!';
        $body = 'Don\'t forget to mark your attendance for today.';

        $data = [
            'type' => 'attendance_reminder',
            'action' => 'check_in',
        ];

        return $this->sendToUser($user, $title, $body, $data);
    }

    /**
     * Send activity count warning
     *
     * @param User $user
     * @param int $remaining
     * @return bool
     */
    public function sendActivityCountWarning(User $user, int $remaining): bool
    {
        $title = 'Activity Reminder';
        $body = sprintf(
            'You need %d more %s to complete today\'s attendance.',
            $remaining,
            $remaining === 1 ? 'activity' : 'activities'
        );

        $data = [
            'type' => 'activity_reminder',
            'remaining' => $remaining,
        ];

        return $this->sendToUser($user, $title, $body, $data);
    }

    /**
     * Send beat schedule notification
     *
     * @param User $user
     * @param string $beatName
     * @param int $farmerCount
     * @param int $retailerCount
     * @return bool
     */
    public function sendBeatScheduleNotification(
        User $user,
        string $beatName,
        int $farmerCount,
        int $retailerCount
    ): bool {
        $title = 'Today\'s Beat Schedule';
        $body = sprintf(
            'Today\'s beat: %s. You have %d farmers and %d retailers to visit.',
            $beatName,
            $farmerCount,
            $retailerCount
        );

        $data = [
            'type' => 'beat_schedule',
            'beat_name' => $beatName,
        ];

        return $this->sendToUser($user, $title, $body, $data);
    }

    /**
     * Send notification to manager about team attendance
     *
     * @param User $manager
     * @param int $presentCount
     * @param int $totalCount
     * @return bool
     */
    public function sendTeamAttendanceUpdate(User $manager, int $presentCount, int $totalCount): bool
    {
        $title = 'Team Attendance Update';
        $body = sprintf(
            '%d out of %d team members have checked in today.',
            $presentCount,
            $totalCount
        );

        $data = [
            'type' => 'team_attendance',
            'present' => $presentCount,
            'total' => $totalCount,
        ];

        return $this->sendToUser($manager, $title, $body, $data);
    }
}
