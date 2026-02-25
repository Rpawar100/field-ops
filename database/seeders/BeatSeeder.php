<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BeatSeeder extends Seeder
{
    /**
     * Seed 20 beats across HQs, with beat_user_assignments and beat_schedules.
     */
    public function run(): void
    {
        if (DB::table('beats')->where('beat_code', 'BEAT-001')->exists()) {
            $this->command->info('BeatSeeder: Data already exists, skipping.');
            return;
        }

        $now = Carbon::now();

        DB::transaction(function () use ($now) {
            // Get all HQs
            $hqs = DB::table('zrth_hierarchies')
                ->where('level', 'hq')
                ->where('status', 'active')
                ->get();

            // Get all villages (we need them for beat assignment)
            $villages = DB::table('villages')->where('status', 'active')->get();

            // Get all FA users
            $faUsers = DB::table('users')
                ->where('role', 'fa')
                ->where('status', 'active')
                ->get();

            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

            // Get village-to-HQ mapping from sdtv_zrth_mappings
            $villageHqMap = DB::table('sdtv_zrth_mappings')
                ->pluck('zrth_hierarchy_id', 'village_id')
                ->toArray();

            // Build HQ → villages index
            $hqVillages = [];
            foreach ($villageHqMap as $vId => $hqId) {
                $hqVillages[$hqId][] = $vId;
            }

            // Beat definitions (20 beats across HQs)
            $beatDefs = [
                ['code' => 'BEAT-001', 'name' => 'Baramati Market Beat',      'hq_code' => 'HQ-BRMTI', 'day' => 'monday',    'dist_km' => 12.5, 'time_min' => 120],
                ['code' => 'BEAT-002', 'name' => 'Katewadi Village Beat',     'hq_code' => 'HQ-BRMTI', 'day' => 'wednesday', 'dist_km' => 8.0,  'time_min' => 90],
                ['code' => 'BEAT-003', 'name' => 'Indapur East Beat',         'hq_code' => 'HQ-INDPR', 'day' => 'tuesday',   'dist_km' => 15.0, 'time_min' => 150],
                ['code' => 'BEAT-004', 'name' => 'Bhigwan Lake Beat',         'hq_code' => 'HQ-INDPR', 'day' => 'friday',    'dist_km' => 18.0, 'time_min' => 180],
                ['code' => 'BEAT-005', 'name' => 'Jalna City Beat',           'hq_code' => 'HQ-JALNA', 'day' => 'monday',    'dist_km' => 10.0, 'time_min' => 100],
                ['code' => 'BEAT-006', 'name' => 'Ambad Rural Beat',          'hq_code' => 'HQ-JALNA', 'day' => 'thursday',  'dist_km' => 20.0, 'time_min' => 200],
                ['code' => 'BEAT-007', 'name' => 'Parbhani Central Beat',     'hq_code' => 'HQ-PARBH', 'day' => 'tuesday',   'dist_km' => 14.0, 'time_min' => 140],
                ['code' => 'BEAT-008', 'name' => 'Gangakhed Beat',            'hq_code' => 'HQ-PARBH', 'day' => 'friday',    'dist_km' => 22.0, 'time_min' => 210],
                ['code' => 'BEAT-009', 'name' => 'Sikar Town Beat',           'hq_code' => 'HQ-SIKAR', 'day' => 'monday',    'dist_km' => 9.0,  'time_min' => 80],
                ['code' => 'BEAT-010', 'name' => 'Laxmangarh Beat',           'hq_code' => 'HQ-SIKAR', 'day' => 'wednesday', 'dist_km' => 16.0, 'time_min' => 160],
                ['code' => 'BEAT-011', 'name' => 'Alwar Market Beat',         'hq_code' => 'HQ-ALWAR', 'day' => 'tuesday',   'dist_km' => 11.0, 'time_min' => 110],
                ['code' => 'BEAT-012', 'name' => 'Neemrana Beat',             'hq_code' => 'HQ-ALWAR', 'day' => 'saturday',  'dist_km' => 25.0, 'time_min' => 230],
                ['code' => 'BEAT-013', 'name' => 'Sehore Rural Beat',         'hq_code' => 'HQ-SEHRE', 'day' => 'wednesday', 'dist_km' => 13.0, 'time_min' => 130],
                ['code' => 'BEAT-014', 'name' => 'Ashta Mandi Beat',          'hq_code' => 'HQ-SEHRE', 'day' => 'friday',    'dist_km' => 17.0, 'time_min' => 170],
                ['code' => 'BEAT-015', 'name' => 'Bagalkot North Beat',       'hq_code' => 'HQ-BAGAL', 'day' => 'monday',    'dist_km' => 14.0, 'time_min' => 140],
                ['code' => 'BEAT-016', 'name' => 'Jamkhandi Beat',            'hq_code' => 'HQ-BAGAL', 'day' => 'thursday',  'dist_km' => 19.0, 'time_min' => 190],
                ['code' => 'BEAT-017', 'name' => 'Dharwad Market Beat',       'hq_code' => 'HQ-DHARW', 'day' => 'tuesday',   'dist_km' => 10.0, 'time_min' => 100],
                ['code' => 'BEAT-018', 'name' => 'Hubli Rural Beat',          'hq_code' => 'HQ-DHARW', 'day' => 'saturday',  'dist_km' => 22.0, 'time_min' => 220],
                ['code' => 'BEAT-019', 'name' => 'Rangareddy Beat',           'hq_code' => 'HQ-RNGRD', 'day' => 'wednesday', 'dist_km' => 15.0, 'time_min' => 150],
                ['code' => 'BEAT-020', 'name' => 'Guntur East Beat',          'hq_code' => 'HQ-GNTUR', 'day' => 'thursday',  'dist_km' => 18.0, 'time_min' => 180],
            ];

            // Build HQ code→id map
            $hqIdMap = $hqs->pluck('id', 'code')->toArray();

            // Build FA user_zrth_assignment map (user → hq)
            $faHqAssignments = DB::table('user_zrth_assignments')
                ->whereIn('user_id', $faUsers->pluck('id'))
                ->where('status', 'active')
                ->pluck('zrth_hierarchy_id', 'user_id')
                ->toArray();

            // Build HQ → FA users map
            $hqFaMap = [];
            foreach ($faHqAssignments as $userId => $hqId) {
                $hqFaMap[$hqId][] = $userId;
            }

            $beatIds = [];
            foreach ($beatDefs as $beatDef) {
                $hqId = $hqIdMap[$beatDef['hq_code']] ?? null;
                if (!$hqId) continue;

                // Pick first village mapped to this HQ
                $villageId = isset($hqVillages[$hqId]) ? $hqVillages[$hqId][0] : null;
                $coverageIds = isset($hqVillages[$hqId]) ? json_encode($hqVillages[$hqId]) : null;

                $beatId = DB::table('beats')->insertGetId([
                    'beat_code'             => $beatDef['code'],
                    'name'                  => $beatDef['name'],
                    'description'           => 'Auto-seeded beat for ' . $beatDef['name'],
                    'zrth_hierarchy_id'     => $hqId,
                    'village_id'            => $villageId,
                    'coverage_village_ids'  => $coverageIds,
                    'beat_day'              => $beatDef['day'],
                    'visit_frequency_days'  => 7,
                    'total_farmers'         => 0,
                    'total_retailers'       => 0,
                    'estimated_distance_km' => $beatDef['dist_km'],
                    'estimated_time_minutes' => $beatDef['time_min'],
                    'status'                => 'active',
                    'created_at'            => $now,
                    'updated_at'            => $now,
                ]);

                $beatIds[$beatDef['code']] = $beatId;

                // Assign FAs to beats (primary assignment)
                $assignedFAs = $hqFaMap[$hqId] ?? [];
                foreach ($assignedFAs as $idx => $faUserId) {
                    DB::table('beat_user_assignments')->insert([
                        'beat_id'       => $beatId,
                        'user_id'       => $faUserId,
                        'is_primary'    => ($idx === 0),
                        'assigned_from' => '2025-01-01',
                        'assigned_to'   => null,
                        'status'        => 'active',
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ]);
                }
            }

            // ─── Beat Schedules for current month ─────────────────
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth   = Carbon::now()->endOfMonth();

            $dayMap = [
                'monday'    => Carbon::MONDAY,
                'tuesday'   => Carbon::TUESDAY,
                'wednesday' => Carbon::WEDNESDAY,
                'thursday'  => Carbon::THURSDAY,
                'friday'    => Carbon::FRIDAY,
                'saturday'  => Carbon::SATURDAY,
                'sunday'    => Carbon::SUNDAY,
            ];

            $schedules = [];
            foreach ($beatDefs as $beatDef) {
                $beatId = $beatIds[$beatDef['code']] ?? null;
                if (!$beatId) continue;

                $hqId = $hqIdMap[$beatDef['hq_code']] ?? null;
                $assignedFAs = $hqFaMap[$hqId ?? 0] ?? [];
                $primaryFA = $assignedFAs[0] ?? null;
                if (!$primaryFA) continue;

                $targetDay = $dayMap[$beatDef['day']];
                $current = $startOfMonth->copy();

                // Move to first occurrence of beat_day in this month
                while ($current->dayOfWeek !== $targetDay) {
                    $current->addDay();
                }

                while ($current->lte($endOfMonth)) {
                    $isPast = $current->lt(Carbon::today());
                    $schedules[] = [
                        'beat_id'        => $beatId,
                        'user_id'        => $primaryFA,
                        'scheduled_date' => $current->format('Y-m-d'),
                        'status'         => $isPast ? 'completed' : 'planned',
                        'attendance_id'  => null,
                        'remarks'        => null,
                        'created_at'     => $now,
                        'updated_at'     => $now,
                    ];
                    $current->addWeek();
                }
            }

            // Batch insert schedules
            foreach (array_chunk($schedules, 50) as $chunk) {
                DB::table('beat_schedules')->insert($chunk);
            }
        });

        $this->command->info('BeatSeeder: 20 beats created with user assignments and monthly schedules.');
    }
}
