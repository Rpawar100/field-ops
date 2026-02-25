<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HrSeeder extends Seeder
{
    /**
     * Seed leave_types, holidays, and leave_requests.
     */
    public function run(): void
    {
        if (DB::table('leave_types')->where('code', 'CL')->exists()) {
            $this->command->info('HrSeeder: Data already exists, skipping.');
            return;
        }

        $now = Carbon::now();

        DB::transaction(function () use ($now) {

            // ─── LEAVE TYPES (5) ─────────────────────────────────
            $leaveTypes = [
                ['code' => 'CL',  'name' => 'Casual Leave',    'description' => 'For personal/casual reasons',          'quota' => 12, 'is_paid' => true,  'requires_approval' => true],
                ['code' => 'SL',  'name' => 'Sick Leave',      'description' => 'For illness/medical reasons',          'quota' => 10, 'is_paid' => true,  'requires_approval' => true],
                ['code' => 'EL',  'name' => 'Earned Leave',    'description' => 'Earned/Privilege leave accrued monthly','quota' => 15, 'is_paid' => true,  'requires_approval' => true],
                ['code' => 'CO',  'name' => 'Comp Off',        'description' => 'Compensatory off for extra work',      'quota' => 6,  'is_paid' => true,  'requires_approval' => true],
                ['code' => 'WFH', 'name' => 'Work From Home',  'description' => 'Work from home day',                   'quota' => 12, 'is_paid' => true,  'requires_approval' => false],
            ];

            $ltIds = [];
            foreach ($leaveTypes as $lt) {
                $id = DB::table('leave_types')->insertGetId([
                    'code'              => $lt['code'],
                    'name'              => $lt['name'],
                    'description'       => $lt['description'],
                    'annual_quota'      => $lt['quota'],
                    'is_paid'           => $lt['is_paid'],
                    'requires_approval' => $lt['requires_approval'],
                    'is_active'         => true,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]);
                $ltIds[$lt['code']] = $id;
            }

            // ─── HOLIDAYS (5) ────────────────────────────────────
            $holidays = [
                ['name' => 'Republic Day',      'date' => '2026-01-26', 'type' => 'national',  'optional' => false],
                ['name' => 'Holi',              'date' => '2026-03-17', 'type' => 'national',  'optional' => false],
                ['name' => 'Independence Day',  'date' => '2026-08-15', 'type' => 'national',  'optional' => false],
                ['name' => 'Diwali',            'date' => '2026-11-08', 'type' => 'national',  'optional' => false],
                ['name' => 'Christmas',         'date' => '2026-12-25', 'type' => 'company',   'optional' => true],
            ];

            foreach ($holidays as $h) {
                DB::table('holidays')->insert([
                    'name'              => $h['name'],
                    'holiday_date'      => $h['date'],
                    'type'              => $h['type'],
                    'zrth_hierarchy_id' => null,
                    'is_optional'       => $h['optional'],
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]);
            }

            // ─── LEAVE REQUESTS (10) ─────────────────────────────
            $faUsers = DB::table('users')
                ->where('role', 'fa')
                ->where('status', 'active')
                ->pluck('id')
                ->toArray();

            $tslUser = DB::table('users')->where('role', 'tsl')->first();
            $approverId = $tslUser ? $tslUser->id : null;

            $leaveStatuses = ['pending', 'approved', 'approved', 'rejected', 'approved', 'pending', 'approved', 'cancelled', 'approved', 'approved'];
            $leaveTypeCodes = ['CL', 'SL', 'EL', 'CL', 'CO', 'SL', 'CL', 'EL', 'WFH', 'CL'];

            for ($i = 0; $i < 10; $i++) {
                $userId = $faUsers[$i % count($faUsers)];
                $status = $leaveStatuses[$i];
                $ltCode = $leaveTypeCodes[$i];
                $ltId   = $ltIds[$ltCode];

                $startDate = Carbon::today()->subDays(30 - ($i * 3));
                $totalDays = 1 + ($i % 3);
                $endDate   = $startDate->copy()->addDays($totalDays - 1);

                $reasons = [
                    'Personal work at home',
                    'Fever and cold',
                    'Family function',
                    'Doctor appointment',
                    'Worked on Saturday last week',
                    'High fever with body ache',
                    'Village festival',
                    'Planned vacation',
                    'Report writing and data analysis',
                    'Personal emergency',
                ];

                DB::table('leave_requests')->insert([
                    'user_id'          => $userId,
                    'leave_type_id'    => $ltId,
                    'start_date'       => $startDate->format('Y-m-d'),
                    'end_date'         => $endDate->format('Y-m-d'),
                    'total_days'       => $totalDays,
                    'reason'           => $reasons[$i],
                    'status'           => $status,
                    'approved_by'      => ($status === 'approved' || $status === 'rejected') ? $approverId : null,
                    'approved_at'      => ($status === 'approved' || $status === 'rejected') ? $now : null,
                    'approval_remarks' => ($status === 'rejected') ? 'Insufficient leave balance' : (($status === 'approved') ? 'Approved' : null),
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ]);
            }
        });

        $this->command->info('HrSeeder: 5 leave types, 5 holidays, 10 leave requests created.');
    }
}
