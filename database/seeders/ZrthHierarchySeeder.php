<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ZrthHierarchySeeder extends Seeder
{
    /**
     * Seed ZRTH hierarchy: 2 Zones > 4 Regions > 8 Territories > 16 HQs.
     * Uses real Indian geography names.
     */
    public function run(): void
    {
        if (DB::table('zrth_hierarchies')->where('level', 'zone')->exists()) {
            $this->command->info('ZrthHierarchySeeder: Data already exists, skipping.');
            return;
        }

        $now = Carbon::now();

        DB::transaction(function () use ($now) {
            // ─── ZONES ───────────────────────────────────────────────
            $zoneNorthId = DB::table('zrth_hierarchies')->insertGetId([
                'code'        => 'ZN-NORTH',
                'name'        => 'North Zone',
                'level'       => 'zone',
                'parent_id'   => null,
                'status'      => 'active',
                'description' => 'Covers Maharashtra, Gujarat, Rajasthan, Madhya Pradesh',
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            $zoneSouthId = DB::table('zrth_hierarchies')->insertGetId([
                'code'        => 'ZN-SOUTH',
                'name'        => 'South Zone',
                'level'       => 'zone',
                'parent_id'   => null,
                'status'      => 'active',
                'description' => 'Covers Karnataka, Telangana, Andhra Pradesh',
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            // ─── REGIONS (2 per zone) ─────────────────────────────
            $regionMHGJ = DB::table('zrth_hierarchies')->insertGetId([
                'code'        => 'RG-MHGJ',
                'name'        => 'Maharashtra-Gujarat Region',
                'level'       => 'region',
                'parent_id'   => $zoneNorthId,
                'status'      => 'active',
                'description' => 'Covers Maharashtra and Gujarat states',
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            $regionRJMP = DB::table('zrth_hierarchies')->insertGetId([
                'code'        => 'RG-RJMP',
                'name'        => 'Rajasthan-MP Region',
                'level'       => 'region',
                'parent_id'   => $zoneNorthId,
                'status'      => 'active',
                'description' => 'Covers Rajasthan and Madhya Pradesh states',
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            $regionKA = DB::table('zrth_hierarchies')->insertGetId([
                'code'        => 'RG-KA',
                'name'        => 'Karnataka Region',
                'level'       => 'region',
                'parent_id'   => $zoneSouthId,
                'status'      => 'active',
                'description' => 'Covers North and South Karnataka',
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            $regionTSAP = DB::table('zrth_hierarchies')->insertGetId([
                'code'        => 'RG-TSAP',
                'name'        => 'Telangana-AP Region',
                'level'       => 'region',
                'parent_id'   => $zoneSouthId,
                'status'      => 'active',
                'description' => 'Covers Telangana and Andhra Pradesh',
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            // ─── TERRITORIES (2 per region) ──────────────────────
            $territories = [
                // Maharashtra-Gujarat Region
                ['code' => 'TR-PUNE',  'name' => 'Pune Territory',       'parent_id' => $regionMHGJ],
                ['code' => 'TR-AURA',  'name' => 'Aurangabad Territory', 'parent_id' => $regionMHGJ],
                // Rajasthan-MP Region
                ['code' => 'TR-JAIPR', 'name' => 'Jaipur Territory',     'parent_id' => $regionRJMP],
                ['code' => 'TR-BHOPL', 'name' => 'Bhopal Territory',     'parent_id' => $regionRJMP],
                // Karnataka Region
                ['code' => 'TR-BLGM',  'name' => 'Belgaum Territory',    'parent_id' => $regionKA],
                ['code' => 'TR-DRVD',  'name' => 'Davanagere Territory', 'parent_id' => $regionKA],
                // Telangana-AP Region
                ['code' => 'TR-HYDR',  'name' => 'Hyderabad Territory',  'parent_id' => $regionTSAP],
                ['code' => 'TR-GUNTR', 'name' => 'Guntur Territory',     'parent_id' => $regionTSAP],
            ];

            $territoryIds = [];
            foreach ($territories as $t) {
                $id = DB::table('zrth_hierarchies')->insertGetId([
                    'code'       => $t['code'],
                    'name'       => $t['name'],
                    'level'      => 'territory',
                    'parent_id'  => $t['parent_id'],
                    'status'     => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $territoryIds[$t['code']] = $id;
            }

            // ─── HQs (2 per territory) ──────────────────────────
            $hqs = [
                // Pune Territory
                ['code' => 'HQ-BRMTI', 'name' => 'Baramati HQ',     'parent' => 'TR-PUNE'],
                ['code' => 'HQ-INDPR', 'name' => 'Indapur HQ',      'parent' => 'TR-PUNE'],
                // Aurangabad Territory
                ['code' => 'HQ-JALNA', 'name' => 'Jalna HQ',        'parent' => 'TR-AURA'],
                ['code' => 'HQ-PARBH', 'name' => 'Parbhani HQ',     'parent' => 'TR-AURA'],
                // Jaipur Territory
                ['code' => 'HQ-SIKAR', 'name' => 'Sikar HQ',        'parent' => 'TR-JAIPR'],
                ['code' => 'HQ-ALWAR', 'name' => 'Alwar HQ',        'parent' => 'TR-JAIPR'],
                // Bhopal Territory
                ['code' => 'HQ-SEHRE', 'name' => 'Sehore HQ',       'parent' => 'TR-BHOPL'],
                ['code' => 'HQ-VIDSH', 'name' => 'Vidisha HQ',      'parent' => 'TR-BHOPL'],
                // Belgaum Territory
                ['code' => 'HQ-BAGAL', 'name' => 'Bagalkot HQ',     'parent' => 'TR-BLGM'],
                ['code' => 'HQ-DHARW', 'name' => 'Dharwad HQ',      'parent' => 'TR-BLGM'],
                // Davanagere Territory
                ['code' => 'HQ-DVNGR', 'name' => 'Davanagere HQ',   'parent' => 'TR-DRVD'],
                ['code' => 'HQ-HAVRI', 'name' => 'Haveri HQ',       'parent' => 'TR-DRVD'],
                // Hyderabad Territory
                ['code' => 'HQ-RNGRD', 'name' => 'Rangareddy HQ',   'parent' => 'TR-HYDR'],
                ['code' => 'HQ-MEDAK', 'name' => 'Medak HQ',        'parent' => 'TR-HYDR'],
                // Guntur Territory
                ['code' => 'HQ-GNTUR', 'name' => 'Guntur HQ',       'parent' => 'TR-GUNTR'],
                ['code' => 'HQ-PRAKS', 'name' => 'Prakasam HQ',     'parent' => 'TR-GUNTR'],
            ];

            foreach ($hqs as $hq) {
                DB::table('zrth_hierarchies')->insert([
                    'code'       => $hq['code'],
                    'name'       => $hq['name'],
                    'level'      => 'hq',
                    'parent_id'  => $territoryIds[$hq['parent']],
                    'status'     => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        });

        $this->command->info('ZrthHierarchySeeder: 2 Zones, 4 Regions, 8 Territories, 16 HQs created.');
    }
}
