<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserZrthSeeder extends Seeder
{
    /**
     * Assign existing users (ZM, RM, TSL, FA) to ZRTH nodes
     * and create 10 additional FA users with proper reporting chains.
     */
    public function run(): void
    {
        if (DB::table('user_zrth_assignments')->exists()) {
            $this->command->info('UserZrthSeeder: Data already exists, skipping.');
            return;
        }

        $now = Carbon::now();

        DB::transaction(function () use ($now) {
            // Look up existing users by role
            $admin = DB::table('users')->where('role', 'nsm')->first();
            $zm    = DB::table('users')->where('role', 'zm')->first();
            $rm    = DB::table('users')->where('role', 'rm')->first();
            $tsl   = DB::table('users')->where('role', 'tsl')->first();
            $fa    = DB::table('users')->where('role', 'fa')->first();

            if (!$admin || !$zm || !$rm || !$tsl || !$fa) {
                $this->command->error('UserZrthSeeder: Required users not found. Ensure 5 base users exist.');
                return;
            }

            // Look up ZRTH nodes
            $zoneNorth    = DB::table('zrth_hierarchies')->where('code', 'ZN-NORTH')->first();
            $regionMHGJ   = DB::table('zrth_hierarchies')->where('code', 'RG-MHGJ')->first();
            $terrPune     = DB::table('zrth_hierarchies')->where('code', 'TR-PUNE')->first();
            $hqBaramati   = DB::table('zrth_hierarchies')->where('code', 'HQ-BRMTI')->first();
            $hqIndapur    = DB::table('zrth_hierarchies')->where('code', 'HQ-INDPR')->first();

            if (!$zoneNorth || !$regionMHGJ || !$terrPune || !$hqBaramati) {
                $this->command->error('UserZrthSeeder: ZRTH hierarchy not found. Run ZrthHierarchySeeder first.');
                return;
            }

            // ─── Update reporting managers on existing users ───────
            DB::table('users')->where('id', $zm->id)->update([
                'reporting_manager_id' => $admin->id,
                'status' => 'active',
                'updated_at' => $now,
            ]);
            DB::table('users')->where('id', $rm->id)->update([
                'reporting_manager_id' => $zm->id,
                'status' => 'active',
                'updated_at' => $now,
            ]);
            DB::table('users')->where('id', $tsl->id)->update([
                'reporting_manager_id' => $rm->id,
                'status' => 'active',
                'updated_at' => $now,
            ]);
            DB::table('users')->where('id', $fa->id)->update([
                'reporting_manager_id' => $tsl->id,
                'status' => 'active',
                'updated_at' => $now,
            ]);

            // ─── Assign existing users to ZRTH ────────────────────
            $assignments = [
                ['user_id' => $zm->id,  'zrth_hierarchy_id' => $zoneNorth->id],
                ['user_id' => $rm->id,  'zrth_hierarchy_id' => $regionMHGJ->id],
                ['user_id' => $tsl->id, 'zrth_hierarchy_id' => $terrPune->id],
                ['user_id' => $fa->id,  'zrth_hierarchy_id' => $hqBaramati->id],
            ];

            foreach ($assignments as $a) {
                DB::table('user_zrth_assignments')->insert([
                    'user_id'           => $a['user_id'],
                    'zrth_hierarchy_id' => $a['zrth_hierarchy_id'],
                    'is_primary'        => true,
                    'assigned_from'     => '2025-01-01',
                    'assigned_to'       => null,
                    'status'            => 'active',
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]);
            }

            // ─── Create 10 more FA users ──────────────────────────
            $allHqs = DB::table('zrth_hierarchies')->where('level', 'hq')->pluck('id', 'code')->toArray();

            // Get territory-based TSL lookup so we can assign reporting managers
            // For simplicity, all new FAs report to the first TSL
            $newFAs = [
                ['name' => 'Rajesh Patil',       'mobile' => '9823456001', 'email' => 'rajesh.patil@fieldops.in',    'hq' => 'HQ-INDPR'],
                ['name' => 'Sunil Sharma',        'mobile' => '9823456002', 'email' => 'sunil.sharma@fieldops.in',    'hq' => 'HQ-JALNA'],
                ['name' => 'Pradeep Jadhav',      'mobile' => '9823456003', 'email' => 'pradeep.jadhav@fieldops.in',  'hq' => 'HQ-PARBH'],
                ['name' => 'Amit Yadav',          'mobile' => '9823456004', 'email' => 'amit.yadav@fieldops.in',      'hq' => 'HQ-SIKAR'],
                ['name' => 'Vikram Singh',        'mobile' => '9823456005', 'email' => 'vikram.singh@fieldops.in',    'hq' => 'HQ-ALWAR'],
                ['name' => 'Mahesh Verma',        'mobile' => '9823456006', 'email' => 'mahesh.verma@fieldops.in',    'hq' => 'HQ-SEHRE'],
                ['name' => 'Deepak Gowda',        'mobile' => '9823456007', 'email' => 'deepak.gowda@fieldops.in',   'hq' => 'HQ-BAGAL'],
                ['name' => 'Ravi Kulkarni',       'mobile' => '9823456008', 'email' => 'ravi.kulkarni@fieldops.in',   'hq' => 'HQ-DHARW'],
                ['name' => 'Santosh Reddy',       'mobile' => '9823456009', 'email' => 'santosh.reddy@fieldops.in',   'hq' => 'HQ-RNGRD'],
                ['name' => 'Ganesh Naidu',        'mobile' => '9823456010', 'email' => 'ganesh.naidu@fieldops.in',    'hq' => 'HQ-GNTUR'],
            ];

            foreach ($newFAs as $i => $faData) {
                $empCode = 'EMP' . str_pad($i + 6, 3, '0', STR_PAD_LEFT); // EMP006..EMP015

                $newUserId = DB::table('users')->insertGetId([
                    'user_id'              => $empCode,
                    'name'                 => $faData['name'],
                    'mobile'               => $faData['mobile'],
                    'email'                => $faData['email'],
                    'password'             => bcrypt('password123'),
                    'role'                 => 'fa',
                    'designation'          => 'Field Assistant',
                    'fa_type'              => ($i % 3 === 0) ? 'temporary' : 'permanent',
                    'reporting_manager_id' => $tsl->id,
                    'status'               => 'active',
                    'app_access_enabled'   => true,
                    'date_of_joining'      => Carbon::parse('2025-01-01')->addDays($i * 7)->format('Y-m-d'),
                    'address'              => $faData['name'] . ', Field Office',
                    'created_at'           => $now,
                    'updated_at'           => $now,
                ]);

                // Assign to HQ
                $hqId = $allHqs[$faData['hq']] ?? null;
                if ($hqId) {
                    DB::table('user_zrth_assignments')->insert([
                        'user_id'           => $newUserId,
                        'zrth_hierarchy_id' => $hqId,
                        'is_primary'        => true,
                        'assigned_from'     => '2025-01-01',
                        'assigned_to'       => null,
                        'status'            => 'active',
                        'created_at'        => $now,
                        'updated_at'        => $now,
                    ]);
                }
            }
        });

        $this->command->info('UserZrthSeeder: 4 existing users assigned, 10 new FA users created with ZRTH assignments.');
    }
}
