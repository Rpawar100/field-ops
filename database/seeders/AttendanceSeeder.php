<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Seed attendance records for last 30 days for all FA users,
     * with location trails.
     */
    public function run(): void
    {
        if (DB::table('attendances')->exists()) {
            $this->command->info('AttendanceSeeder: Data already exists, skipping.');
            return;
        }

        $now = Carbon::now();

        DB::transaction(function () use ($now) {
            $faUsers = DB::table('users')
                ->where('role', 'fa')
                ->where('status', 'active')
                ->get();

            if ($faUsers->isEmpty()) {
                $this->command->error('AttendanceSeeder: No FA users found.');
                return;
            }

            // Get user ZRTH assignments for location context
            $userHqs = DB::table('user_zrth_assignments')
                ->where('status', 'active')
                ->pluck('zrth_hierarchy_id', 'user_id')
                ->toArray();

            // Base GPS coordinates for variety (Indian locations)
            $baseCoords = [
                ['lat' => 18.15, 'lng' => 74.58],  // Baramati area
                ['lat' => 19.61, 'lng' => 75.95],  // Jalna area
                ['lat' => 27.81, 'lng' => 75.04],  // Sikar area
                ['lat' => 23.01, 'lng' => 77.09],  // Sehore area
                ['lat' => 16.51, 'lng' => 75.29],  // Bagalkot area
                ['lat' => 15.55, 'lng' => 75.38],  // Hubli area
                ['lat' => 14.52, 'lng' => 76.34],  // Davanagere area
                ['lat' => 17.39, 'lng' => 78.49],  // Hyderabad area
                ['lat' => 16.31, 'lng' => 80.44],  // Guntur area
                ['lat' => 22.99, 'lng' => 72.37],  // Ahmedabad area
                ['lat' => 27.99, 'lng' => 76.38],  // Alwar area
            ];

            $attendanceTypes = ['individual_work', 'individual_work', 'individual_work', 'joint_work', 'meeting'];
            $nonWorkTypes    = ['leave', 'week_off'];
            $deviceModels    = ['Samsung A54', 'Redmi Note 12', 'Realme 10', 'OnePlus Nord', 'Samsung M34', 'Vivo Y56'];

            $attendances     = [];
            $locationTrails  = [];

            foreach ($faUsers as $userIdx => $user) {
                $coordIdx = $userIdx % count($baseCoords);
                $baseLat  = $baseCoords[$coordIdx]['lat'];
                $baseLng  = $baseCoords[$coordIdx]['lng'];

                for ($dayOffset = 30; $dayOffset >= 1; $dayOffset--) {
                    $date = Carbon::today()->subDays($dayOffset);

                    // Skip Sundays
                    if ($date->isSunday()) {
                        $attendances[] = [
                            'uuid'             => Str::uuid()->toString(),
                            'user_id'          => $user->id,
                            'attendance_date'  => $date->format('Y-m-d'),
                            'check_in_time'    => null,
                            'check_in_latitude' => null,
                            'check_in_longitude' => null,
                            'check_in_selfie_path' => null,
                            'check_in_address' => null,
                            'check_out_time'   => null,
                            'check_out_latitude' => null,
                            'check_out_longitude' => null,
                            'check_out_selfie_path' => null,
                            'check_out_address' => null,
                            'attendance_type'  => 'week_off',
                            'joint_work_with_user_id' => null,
                            'leave_request_id' => null,
                            'is_valid'         => false,
                            'activity_count'   => 0,
                            'minimum_activity_required' => 0,
                            'total_working_minutes' => null,
                            'distance_covered_km' => null,
                            'remarks'          => 'Weekly off - Sunday',
                            'manager_remarks'  => null,
                            'sync_status'      => 'synced',
                            'synced_at'        => $now,
                            'sync_attempts'    => 1,
                            'sync_error'       => null,
                            'device_id'        => 'DEV-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                            'device_model'     => $deviceModels[$userIdx % count($deviceModels)],
                            'app_version'      => '1.2.0',
                            'approval_status'  => 'not_required',
                            'approved_by'      => null,
                            'approved_at'      => null,
                            'created_at'       => $now,
                            'updated_at'       => $now,
                        ];
                        continue;
                    }

                    // 10% chance of leave on weekdays
                    if ($dayOffset % 10 === 0 && $userIdx % 3 === 0) {
                        $attendances[] = [
                            'uuid'             => Str::uuid()->toString(),
                            'user_id'          => $user->id,
                            'attendance_date'  => $date->format('Y-m-d'),
                            'check_in_time'    => null,
                            'check_in_latitude' => null,
                            'check_in_longitude' => null,
                            'check_in_selfie_path' => null,
                            'check_in_address' => null,
                            'check_out_time'   => null,
                            'check_out_latitude' => null,
                            'check_out_longitude' => null,
                            'check_out_selfie_path' => null,
                            'check_out_address' => null,
                            'attendance_type'  => 'leave',
                            'joint_work_with_user_id' => null,
                            'leave_request_id' => null,
                            'is_valid'         => false,
                            'activity_count'   => 0,
                            'minimum_activity_required' => 0,
                            'total_working_minutes' => null,
                            'distance_covered_km' => null,
                            'remarks'          => 'Leave taken',
                            'manager_remarks'  => null,
                            'sync_status'      => 'synced',
                            'synced_at'        => $now,
                            'sync_attempts'    => 1,
                            'sync_error'       => null,
                            'device_id'        => 'DEV-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                            'device_model'     => $deviceModels[$userIdx % count($deviceModels)],
                            'app_version'      => '1.2.0',
                            'approval_status'  => 'not_required',
                            'approved_by'      => null,
                            'approved_at'      => null,
                            'created_at'       => $now,
                            'updated_at'       => $now,
                        ];
                        continue;
                    }

                    // Normal working day
                    $checkInHour   = 8 + ($dayOffset % 2);
                    $checkInMin    = 15 + ($userIdx * 3) % 45;
                    $checkOutHour  = 17 + ($dayOffset % 2);
                    $checkOutMin   = ($userIdx * 7 + $dayOffset * 3) % 45;
                    $workMinutes   = ($checkOutHour - $checkInHour) * 60 + ($checkOutMin - $checkInMin);

                    $checkInTime  = $date->copy()->setTime($checkInHour, $checkInMin, 0);
                    $checkOutTime = $date->copy()->setTime($checkOutHour, $checkOutMin, 0);

                    $latIn  = $baseLat + fmod(($dayOffset + 1) * 0.001, 0.05);
                    $lngIn  = $baseLng + fmod(($dayOffset + 1) * 0.002, 0.05);
                    $latOut = $latIn + 0.005;
                    $lngOut = $lngIn + 0.005;

                    $attType = $attendanceTypes[($dayOffset + $userIdx) % count($attendanceTypes)];
                    $jointWith = null;
                    if ($attType === 'joint_work') {
                        // Pick another FA
                        $otherFAs = $faUsers->where('id', '!=', $user->id)->values();
                        if ($otherFAs->count() > 0) {
                            $jointWith = $otherFAs[$dayOffset % $otherFAs->count()]->id;
                        }
                    }

                    $activityCount = 3 + ($dayOffset % 5);
                    $distKm = round(8 + ($dayOffset % 15) + ($userIdx * 0.5), 2);

                    $attendances[] = [
                        'uuid'             => Str::uuid()->toString(),
                        'user_id'          => $user->id,
                        'attendance_date'  => $date->format('Y-m-d'),
                        'check_in_time'    => $checkInTime->format('Y-m-d H:i:s'),
                        'check_in_latitude' => round($latIn, 8),
                        'check_in_longitude' => round($lngIn, 8),
                        'check_in_selfie_path' => null,
                        'check_in_address' => 'Field Office Area, ' . $baseCoords[$coordIdx]['lat'],
                        'check_out_time'   => $checkOutTime->format('Y-m-d H:i:s'),
                        'check_out_latitude' => round($latOut, 8),
                        'check_out_longitude' => round($lngOut, 8),
                        'check_out_selfie_path' => null,
                        'check_out_address' => 'Village Market Area',
                        'attendance_type'  => $attType,
                        'joint_work_with_user_id' => $jointWith,
                        'leave_request_id' => null,
                        'is_valid'         => ($activityCount >= 3),
                        'activity_count'   => $activityCount,
                        'minimum_activity_required' => 3,
                        'total_working_minutes' => max($workMinutes, 0),
                        'distance_covered_km' => $distKm,
                        'remarks'          => null,
                        'manager_remarks'  => null,
                        'sync_status'      => 'synced',
                        'synced_at'        => $now,
                        'sync_attempts'    => 1,
                        'sync_error'       => null,
                        'device_id'        => 'DEV-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                        'device_model'     => $deviceModels[$userIdx % count($deviceModels)],
                        'app_version'      => '1.2.0',
                        'approval_status'  => 'not_required',
                        'approved_by'      => null,
                        'approved_at'      => null,
                        'created_at'       => $now,
                        'updated_at'       => $now,
                    ];
                }
            }

            // Batch insert attendances
            foreach (array_chunk($attendances, 50) as $chunk) {
                DB::table('attendances')->insert($chunk);
            }

            // ─── Location Trails (for working day attendances) ───
            $workingAttendances = DB::table('attendances')
                ->whereNotIn('attendance_type', ['leave', 'week_off', 'holiday'])
                ->whereNotNull('check_in_time')
                ->limit(50) // Only first 50 for trails to keep it reasonable
                ->get();

            foreach ($workingAttendances as $att) {
                $baseLat = (float) $att->check_in_latitude;
                $baseLng = (float) $att->check_in_longitude;

                // 3-5 trail points per attendance
                $trailCount = 3 + (intval($att->id) % 3);
                for ($t = 0; $t < $trailCount; $t++) {
                    $locationTrails[] = [
                        'attendance_id' => $att->id,
                        'latitude'      => round($baseLat + ($t * 0.003), 8),
                        'longitude'     => round($baseLng + ($t * 0.004), 8),
                        'accuracy'      => round(5 + ($t * 2.5), 2),
                        'speed'         => round(2 + ($t * 1.5), 2),
                        'address'       => 'Trail point ' . ($t + 1) . ' - Field area',
                        'recorded_at'   => Carbon::parse($att->check_in_time)->addHours($t * 2)->format('Y-m-d H:i:s'),
                        'sync_status'   => 'synced',
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ];
                }
            }

            foreach (array_chunk($locationTrails, 50) as $chunk) {
                DB::table('attendance_location_trails')->insert($chunk);
            }
        });

        $attCount   = DB::table('attendances')->count();
        $trailCount = DB::table('attendance_location_trails')->count();
        $this->command->info("AttendanceSeeder: {$attCount} attendance records, {$trailCount} location trails created.");
    }
}
