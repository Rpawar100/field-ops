<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DemoSeeder extends Seeder
{
    /**
     * Seed demo_stage_templates, demo_stage_plans, demo_masters,
     * demo_distributions, demo_farmer_assignments, demo_stage_activities.
     */
    public function run(): void
    {
        if (DB::table('demo_masters')->where('lot_no', 'LOT-2025-001')->exists()) {
            $this->command->info('DemoSeeder: Data already exists, skipping.');
            return;
        }

        $now = Carbon::now();

        DB::transaction(function () use ($now) {

            $crops     = DB::table('crops')->pluck('id', 'code')->toArray();
            $varieties = DB::table('varieties')->pluck('id', 'code')->toArray();
            $products  = DB::table('products')->pluck('id', 'sku_code')->toArray();
            $farmers   = DB::table('farmers')->where('status', 'active')->pluck('id')->toArray();
            $faUsers   = DB::table('users')->where('role', 'fa')->where('status', 'active')->pluck('id')->toArray();
            $hqs       = DB::table('zrth_hierarchies')->where('level', 'hq')->pluck('id')->toArray();

            if (empty($crops) || empty($faUsers) || empty($farmers)) {
                $this->command->error('DemoSeeder: Required data missing (crops/users/farmers).');
                return;
            }

            // ─── DEMO STAGE TEMPLATES (5 stages per crop for Rice & Cotton) ─
            $stagesDefs = [
                // Rice stages
                ['crop' => 'CROP-RICE', 'variety' => 'VAR-RICE-HYB1', 'stages' => [
                    ['name' => 'Sowing',       'order' => 1, 'days' => 0,   'tol_before' => 0, 'tol_after' => 3],
                    ['name' => 'Germination',   'order' => 2, 'days' => 10,  'tol_before' => 2, 'tol_after' => 3],
                    ['name' => 'Vegetative',    'order' => 3, 'days' => 35,  'tol_before' => 5, 'tol_after' => 5],
                    ['name' => 'Flowering',     'order' => 4, 'days' => 75,  'tol_before' => 5, 'tol_after' => 7],
                    ['name' => 'Harvesting',    'order' => 5, 'days' => 115, 'tol_before' => 7, 'tol_after' => 10],
                ]],
                // Cotton stages
                ['crop' => 'CROP-COTTON', 'variety' => 'VAR-COTTON-BT1', 'stages' => [
                    ['name' => 'Sowing',        'order' => 1, 'days' => 0,   'tol_before' => 0, 'tol_after' => 3],
                    ['name' => 'Germination',    'order' => 2, 'days' => 12,  'tol_before' => 2, 'tol_after' => 3],
                    ['name' => 'Vegetative',     'order' => 3, 'days' => 45,  'tol_before' => 5, 'tol_after' => 7],
                    ['name' => 'Flowering',      'order' => 4, 'days' => 80,  'tol_before' => 5, 'tol_after' => 7],
                    ['name' => 'Harvesting',     'order' => 5, 'days' => 170, 'tol_before' => 10, 'tol_after' => 15],
                ]],
                // Corn stages
                ['crop' => 'CROP-CORN', 'variety' => 'VAR-CORN-HYB1', 'stages' => [
                    ['name' => 'Sowing',        'order' => 1, 'days' => 0,   'tol_before' => 0, 'tol_after' => 3],
                    ['name' => 'Germination',    'order' => 2, 'days' => 8,   'tol_before' => 2, 'tol_after' => 3],
                    ['name' => 'Vegetative',     'order' => 3, 'days' => 30,  'tol_before' => 5, 'tol_after' => 5],
                    ['name' => 'Flowering',      'order' => 4, 'days' => 60,  'tol_before' => 5, 'tol_after' => 7],
                    ['name' => 'Harvesting',     'order' => 5, 'days' => 105, 'tol_before' => 7, 'tol_after' => 10],
                ]],
            ];

            $stageTemplateIds = []; // key: "CROP-RICE_1" => id

            foreach ($stagesDefs as $sd) {
                $cropId    = $crops[$sd['crop']] ?? null;
                $varietyId = $varieties[$sd['variety']] ?? null;
                if (!$cropId) continue;

                foreach ($sd['stages'] as $st) {
                    $stId = DB::table('demo_stage_templates')->insertGetId([
                        'crop_id'                => $cropId,
                        'variety_id'             => $varietyId,
                        'stage_name'             => $st['name'],
                        'stage_order'            => $st['order'],
                        'days_after_sowing'      => $st['days'],
                        'tolerance_days_before'  => $st['tol_before'],
                        'tolerance_days_after'   => $st['tol_after'],
                        'required_attributes'    => json_encode(['crop_height_cm' => 'number', 'leaf_count' => 'number']),
                        'optional_attributes'    => json_encode(['pest_observed' => 'boolean', 'weather_notes' => 'string']),
                        'photo_required'         => true,
                        'min_photos'             => 1,
                        'max_photos'             => 5,
                        'instructions'           => 'Observe and photograph the ' . $st['name'] . ' stage',
                        'observation_guidelines' => 'Record plant height, leaf count, and general health',
                        'status'                 => 'active',
                        'created_at'             => $now,
                        'updated_at'             => $now,
                    ]);

                    $stageTemplateIds[$sd['crop'] . '_' . $st['order']] = $stId;
                }
            }

            // ─── DEMO STAGE PLANS ────────────────────────────────
            $planDefs = [
                ['code' => 'PLAN-RICE-2025',   'name' => 'Rice Demo Plan Kharif 2025',   'crop' => 'CROP-RICE',   'variety' => 'VAR-RICE-HYB1',   'stages' => 5, 'duration' => 120],
                ['code' => 'PLAN-COTTON-2025', 'name' => 'Cotton Demo Plan Kharif 2025', 'crop' => 'CROP-COTTON', 'variety' => 'VAR-COTTON-BT1', 'stages' => 5, 'duration' => 180],
                ['code' => 'PLAN-CORN-2025',   'name' => 'Corn Demo Plan Kharif 2025',   'crop' => 'CROP-CORN',   'variety' => 'VAR-CORN-HYB1',  'stages' => 5, 'duration' => 110],
            ];

            $planIds = [];
            foreach ($planDefs as $pd) {
                $planId = DB::table('demo_stage_plans')->insertGetId([
                    'plan_code'          => $pd['code'],
                    'name'               => $pd['name'],
                    'crop_id'            => $crops[$pd['crop']],
                    'variety_id'         => $varieties[$pd['variety']] ?? null,
                    'description'        => $pd['name'] . ' - comprehensive stage monitoring',
                    'total_stages'       => $pd['stages'],
                    'total_duration_days' => $pd['duration'],
                    'status'             => 'active',
                    'created_at'         => $now,
                    'updated_at'         => $now,
                ]);
                $planIds[$pd['code']] = $planId;

                // Link plan to templates
                for ($s = 1; $s <= 5; $s++) {
                    $stKey = $pd['crop'] . '_' . $s;
                    if (isset($stageTemplateIds[$stKey])) {
                        DB::table('demo_stage_plan_templates')->insert([
                            'stage_plan_id'     => $planId,
                            'stage_template_id' => $stageTemplateIds[$stKey],
                            'stage_order'       => $s,
                            'is_mandatory'      => true,
                            'created_at'        => $now,
                            'updated_at'        => $now,
                        ]);
                    }
                }
            }

            // ─── 5 DEMO MASTERS ──────────────────────────────────
            $demoMasterDefs = [
                ['lot' => 'LOT-2025-001', 'crop' => 'CROP-RICE',   'variety' => 'VAR-RICE-HYB1',   'product' => 'SKU-S001', 'qty' => 200, 'unit' => 'kg', 'plan' => 'PLAN-RICE-2025',   'season' => 'kharif', 'target' => 50],
                ['lot' => 'LOT-2025-002', 'crop' => 'CROP-COTTON', 'variety' => 'VAR-COTTON-BT1', 'product' => 'SKU-S004', 'qty' => 50,  'unit' => 'kg', 'plan' => 'PLAN-COTTON-2025', 'season' => 'kharif', 'target' => 40],
                ['lot' => 'LOT-2025-003', 'crop' => 'CROP-CORN',   'variety' => 'VAR-CORN-HYB1',  'product' => 'SKU-S007', 'qty' => 100, 'unit' => 'kg', 'plan' => 'PLAN-CORN-2025',   'season' => 'kharif', 'target' => 30],
                ['lot' => 'LOT-2025-004', 'crop' => 'CROP-WHEAT',  'variety' => 'VAR-WHEAT-HD1',  'product' => 'SKU-S003', 'qty' => 150, 'unit' => 'kg', 'plan' => null,                'season' => 'rabi',   'target' => 35],
                ['lot' => 'LOT-2025-005', 'crop' => 'CROP-SOYBN',  'variety' => 'VAR-SOYBN-JS1',  'product' => 'SKU-S006', 'qty' => 100, 'unit' => 'kg', 'plan' => null,                'season' => 'kharif', 'target' => 25],
            ];

            $dmIds = [];
            foreach ($demoMasterDefs as $di => $dm) {
                $id = DB::table('demo_masters')->insertGetId([
                    'lot_no'               => $dm['lot'],
                    'crop_id'              => $crops[$dm['crop']],
                    'variety_id'           => $varieties[$dm['variety']] ?? null,
                    'product_id'           => $products[$dm['product']] ?? null,
                    'product_name'         => $dm['product'],
                    'total_quantity'       => $dm['qty'],
                    'quantity_unit'        => $dm['unit'],
                    'available_quantity'   => round($dm['qty'] * 0.6, 2),
                    'stage_plan_id'        => $dm['plan'] ? ($planIds[$dm['plan']] ?? null) : null,
                    'season'               => $dm['season'],
                    'year'                 => '2025-26',
                    'source_zrth_id'       => !empty($hqs) ? $hqs[$di % count($hqs)] : null,
                    'source_location_name' => 'Central Warehouse',
                    'manufacturing_date'   => '2025-05-01',
                    'expiry_date'          => '2027-04-30',
                    'batch_number'         => 'BATCH-' . strtoupper(substr($dm['lot'], -3)),
                    'target_farmers'       => $dm['target'],
                    'assigned_farmers'     => 0,
                    'status'               => ($di < 3) ? 'partially_distributed' : 'available',
                    'created_at'           => $now,
                    'updated_at'           => $now,
                ]);
                $dmIds[$dm['lot']] = $id;
            }

            // ─── 10 DEMO DISTRIBUTIONS ───────────────────────────
            $distDefs = [
                ['master' => 'LOT-2025-001', 'from_type' => 'warehouse', 'to_type' => 'user', 'qty' => 40, 'status' => 'acknowledged'],
                ['master' => 'LOT-2025-001', 'from_type' => 'user',      'to_type' => 'farmer', 'qty' => 4,  'status' => 'delivered'],
                ['master' => 'LOT-2025-001', 'from_type' => 'user',      'to_type' => 'farmer', 'qty' => 4,  'status' => 'delivered'],
                ['master' => 'LOT-2025-002', 'from_type' => 'warehouse', 'to_type' => 'user', 'qty' => 10, 'status' => 'acknowledged'],
                ['master' => 'LOT-2025-002', 'from_type' => 'user',      'to_type' => 'farmer', 'qty' => 0.45, 'status' => 'delivered'],
                ['master' => 'LOT-2025-002', 'from_type' => 'user',      'to_type' => 'farmer', 'qty' => 0.45, 'status' => 'delivered'],
                ['master' => 'LOT-2025-003', 'from_type' => 'warehouse', 'to_type' => 'user', 'qty' => 20, 'status' => 'acknowledged'],
                ['master' => 'LOT-2025-003', 'from_type' => 'user',      'to_type' => 'farmer', 'qty' => 4,  'status' => 'delivered'],
                ['master' => 'LOT-2025-004', 'from_type' => 'warehouse', 'to_type' => 'zrth', 'qty' => 50, 'status' => 'in_transit'],
                ['master' => 'LOT-2025-005', 'from_type' => 'warehouse', 'to_type' => 'zrth', 'qty' => 30, 'status' => 'pending'],
            ];

            $ddIds = [];
            foreach ($distDefs as $ddi => $dd) {
                $faId   = $faUsers[$ddi % count($faUsers)];
                $fmrId  = ($dd['to_type'] === 'farmer') ? $farmers[$ddi % count($farmers)] : null;
                $hqId   = !empty($hqs) ? $hqs[$ddi % count($hqs)] : null;

                $id = DB::table('demo_distributions')->insertGetId([
                    'uuid'                     => Str::uuid()->toString(),
                    'demo_master_id'           => $dmIds[$dd['master']],
                    'from_location_type'       => $dd['from_type'],
                    'from_zrth_id'             => ($dd['from_type'] === 'warehouse') ? null : $hqId,
                    'from_user_id'             => ($dd['from_type'] === 'user') ? $faId : null,
                    'from_location_name'       => ($dd['from_type'] === 'warehouse') ? 'Central Warehouse' : null,
                    'to_location_type'         => $dd['to_type'],
                    'to_zrth_id'              => ($dd['to_type'] === 'zrth') ? $hqId : null,
                    'to_user_id'              => ($dd['to_type'] === 'user') ? $faId : null,
                    'to_farmer_id'            => $fmrId,
                    'to_location_name'        => null,
                    'distribution_date'       => Carbon::today()->subDays(25 - $ddi)->format('Y-m-d'),
                    'quantity'                => $dd['qty'],
                    'quantity_unit'           => 'kg',
                    'receiver_name'           => 'Receiver ' . ($ddi + 1),
                    'receiver_mobile'         => '98765' . str_pad(43210 + $ddi, 5, '0', STR_PAD_LEFT),
                    'receiver_designation'    => ($dd['to_type'] === 'farmer') ? 'Farmer' : 'Field Assistant',
                    'is_acknowledged'         => ($dd['status'] === 'acknowledged'),
                    'acknowledged_at'         => ($dd['status'] === 'acknowledged') ? $now : null,
                    'latitude'                => 18.15 + ($ddi * 0.01),
                    'longitude'               => 74.58 + ($ddi * 0.01),
                    'distributed_by'          => $faId,
                    'challan_number'          => 'CHN-' . str_pad($ddi + 1, 4, '0', STR_PAD_LEFT),
                    'remarks'                 => null,
                    'status'                  => $dd['status'],
                    'sync_status'             => 'synced',
                    'synced_at'               => $now,
                    'created_at'              => $now,
                    'updated_at'              => $now,
                ]);
                $ddIds[] = $id;
            }

            // ─── 20 DEMO FARMER ASSIGNMENTS ──────────────────────
            $assignmentStatuses = ['assigned', 'sowing_done', 'in_progress', 'completed', 'harvested'];
            $dfaIds = [];

            for ($a = 0; $a < 20; $a++) {
                $dmKey     = array_keys($dmIds)[$a % count($dmIds)];
                $dmId      = $dmIds[$dmKey];
                $farmerId  = $farmers[$a % count($farmers)];
                $faId      = $faUsers[$a % count($faUsers)];
                $distId    = ($a < count($ddIds)) ? $ddIds[$a] : $ddIds[$a % count($ddIds)];
                $statusIdx = $a % count($assignmentStatuses);
                $status    = $assignmentStatuses[$statusIdx];

                $sowingDate = Carbon::today()->subDays(60 - ($a * 2));
                $completedStages = min($statusIdx, 5);

                $dfaId = DB::table('demo_farmer_assignments')->insertGetId([
                    'uuid'                  => Str::uuid()->toString(),
                    'demo_master_id'        => $dmId,
                    'demo_distribution_id'  => $distId,
                    'farmer_id'             => $farmerId,
                    'lot_no'                => $dmKey,
                    'quantity_given'         => round(2 + ($a % 5), 2),
                    'quantity_unit'          => 'kg',
                    'assignment_date'        => $sowingDate->copy()->subDays(5)->format('Y-m-d'),
                    'sowing_date'            => ($statusIdx >= 1) ? $sowingDate->format('Y-m-d') : null,
                    'expected_harvest_date'  => ($statusIdx >= 1) ? $sowingDate->copy()->addDays(120)->format('Y-m-d') : null,
                    'plot_size_acres'        => round(0.5 + ($a % 4) * 0.25, 2),
                    'plot_latitude'          => 18.15 + ($a * 0.005),
                    'plot_longitude'         => 74.58 + ($a * 0.005),
                    'plot_address'           => 'Demo plot ' . ($a + 1) . ', Field area',
                    'comparison_variety'     => ($a % 2 === 0) ? 'Local variety' : 'Competitor brand X',
                    'comparison_brand'       => ($a % 2 === 0) ? 'Local' : 'CompetitorCo',
                    'assigned_by'            => $faId,
                    'current_stage'          => $completedStages,
                    'total_stages'           => 5,
                    'completed_stages'       => $completedStages,
                    'status'                 => $status,
                    'status_remarks'         => null,
                    'status_date'            => $now->format('Y-m-d'),
                    'actual_yield'           => ($status === 'harvested') ? round(8 + ($a % 10) * 0.5, 2) : null,
                    'yield_unit'             => ($status === 'harvested') ? 'quintal' : null,
                    'farmer_satisfaction'     => ($status === 'harvested') ? ['excellent', 'good', 'average'][$a % 3] : null,
                    'farmer_feedback'         => ($status === 'harvested') ? 'Good performance observed' : null,
                    'will_repurchase'         => ($status === 'harvested') ? true : null,
                    'sync_status'            => 'synced',
                    'synced_at'              => $now,
                    'created_at'             => $now,
                    'updated_at'             => $now,
                ]);

                $dfaIds[] = $dfaId;

                // Update assigned_farmers count
                DB::table('demo_masters')->where('id', $dmId)->increment('assigned_farmers');
            }

            // ─── 30 DEMO STAGE ACTIVITIES ────────────────────────
            $stageStatuses   = ['pending', 'in_progress', 'completed', 'completed', 'completed'];
            $cropConditions  = ['excellent', 'good', 'average', 'good', 'excellent'];
            $stageNames      = ['Sowing', 'Germination', 'Vegetative', 'Flowering', 'Harvesting'];

            $dsaCount = 0;
            foreach ($dfaIds as $dfaIdx => $dfaId) {
                if ($dsaCount >= 30) break;

                // Create 1-3 stage activities per assignment depending on status
                $assignment = DB::table('demo_farmer_assignments')->find($dfaId);
                $numStages = min($assignment->completed_stages + 1, 5);
                $numStages = min($numStages, 3); // cap at 3 for reasonable volume

                for ($s = 0; $s < $numStages; $s++) {
                    if ($dsaCount >= 30) break;

                    $sowingDate = $assignment->sowing_date ? Carbon::parse($assignment->sowing_date) : Carbon::today()->subDays(60);
                    $daysAfter = [0, 10, 35, 75, 115][$s % 5];
                    $plannedDate = $sowingDate->copy()->addDays($daysAfter);

                    $cropCode = array_search($assignment->demo_master_id, $dmIds);
                    // Determine template id
                    $stKey = null;
                    foreach (['CROP-RICE', 'CROP-COTTON', 'CROP-CORN'] as $cc) {
                        $key = $cc . '_' . ($s + 1);
                        if (isset($stageTemplateIds[$key])) {
                            $stKey = $key;
                            break;
                        }
                    }

                    $faId = $faUsers[$dsaCount % count($faUsers)];

                    DB::table('demo_stage_activities')->insert([
                        'uuid'                        => Str::uuid()->toString(),
                        'demo_farmer_assignment_id'   => $dfaId,
                        'stage_template_id'           => $stKey ? ($stageTemplateIds[$stKey] ?? null) : null,
                        'stage_name'                  => $stageNames[$s % 5],
                        'stage_order'                 => $s + 1,
                        'planned_date'                => $plannedDate->format('Y-m-d'),
                        'actual_date'                 => ($s < $numStages - 1) ? $plannedDate->copy()->addDays($s)->format('Y-m-d') : null,
                        'days_after_sowing'           => $daysAfter,
                        'attributes'                  => json_encode(['crop_height_cm' => 15 + ($s * 20), 'leaf_count' => 4 + $s]),
                        'measurements'                => json_encode(['height' => 15 + ($s * 20), 'stem_diameter_mm' => 5 + $s]),
                        'photos'                      => json_encode(['demos/' . $dfaId . '/stage_' . ($s + 1) . '.jpg']),
                        'latitude'                    => 18.15 + ($dsaCount * 0.003),
                        'longitude'                   => 74.58 + ($dsaCount * 0.003),
                        'recorded_by'                 => $faId,
                        'status'                      => ($s < $numStages - 1) ? 'completed' : 'pending',
                        'status_reason'               => null,
                        'rescheduled_date'            => null,
                        'crop_condition'              => ($s < $numStages - 1) ? $cropConditions[$s % 5] : null,
                        'observations'                => 'Stage ' . ($s + 1) . ' observation: Crop growing well',
                        'recommendations'             => 'Continue regular irrigation and pest monitoring',
                        'is_reviewed'                 => ($s < $numStages - 1),
                        'reviewed_by'                 => ($s < $numStages - 1) ? $faUsers[0] : null,
                        'reviewed_at'                 => ($s < $numStages - 1) ? $now : null,
                        'review_remarks'              => ($s < $numStages - 1) ? 'Looks good' : null,
                        'sync_status'                 => 'synced',
                        'synced_at'                   => $now,
                        'created_at'                  => $now,
                        'updated_at'                  => $now,
                    ]);

                    $dsaCount++;
                }
            }
        });

        $this->command->info('DemoSeeder: Stage templates, plans, 5 masters, 10 distributions, 20 assignments, 30 stage activities.');
    }
}
