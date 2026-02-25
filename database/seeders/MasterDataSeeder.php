<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SdtvNode;
use App\Models\Product;
use App\Models\ActivityType;
use App\Models\DemoMaster;
use App\Models\DemoStageTemplate;

class MasterDataSeeder extends Seeder
{
    /**
     * Seed master data for MVP testing.
     */
    public function run(): void
    {
        $this->seedSdtvHierarchy();
        $this->seedProducts();
        $this->seedActivityTypes();
        $this->seedDemoMasters();

        $this->command->info('Master Data Seeded:');
        $this->command->info('- SDTV Hierarchy (State > District > Taluka > Villages)');
        $this->command->info('- Products (Seeds & Pesticides)');
        $this->command->info('- Activity Types (PSA, PDA, Demo, Others)');
        $this->command->info('- Demo Masters with Stage Templates');
    }

    private function seedSdtvHierarchy(): void
    {
        // State
        $state = SdtvNode::create([
            'code' => 'TS',
            'name' => 'Telangana',
            'level' => 'state',
            'parent_id' => null,
            'status' => 'active',
        ]);

        // District
        $district = SdtvNode::create([
            'code' => 'RR',
            'name' => 'Rangareddy',
            'level' => 'district',
            'parent_id' => $state->id,
            'status' => 'active',
        ]);

        // Taluka
        $taluka = SdtvNode::create([
            'code' => 'IBS',
            'name' => 'Ibrahimpatnam',
            'level' => 'taluka',
            'parent_id' => $district->id,
            'status' => 'active',
        ]);

        // Villages
        $villages = ['Manchal', 'Peddemul', 'Kothur', 'Yacharam', 'Amangal'];
        foreach ($villages as $index => $village) {
            SdtvNode::create([
                'code' => 'VLG' . ($index + 1),
                'name' => $village,
                'level' => 'village',
                'parent_id' => $taluka->id,
                'status' => 'active',
            ]);
        }
    }

    private function seedProducts(): void
    {
        $products = [
            // Seeds
            [
                'code' => 'SKU001',
                'name' => 'Kaveri Super Corn',
                'category' => 'seeds',
                'crop' => 'Corn',
                'variety' => 'Super',
                'pack_size' => '4 kg',
                'is_demo_eligible' => true,
            ],
            [
                'code' => 'SKU002',
                'name' => 'Kaveri Paddy Gold',
                'category' => 'seeds',
                'crop' => 'Paddy',
                'variety' => 'Gold',
                'pack_size' => '5 kg',
                'is_demo_eligible' => true,
            ],
            [
                'code' => 'SKU003',
                'name' => 'Kaveri Cotton Premium',
                'category' => 'seeds',
                'crop' => 'Cotton',
                'variety' => 'Premium',
                'pack_size' => '450 gm',
                'is_demo_eligible' => true,
            ],
            [
                'code' => 'SKU004',
                'name' => 'Kaveri Sunflower Hybrid',
                'category' => 'seeds',
                'crop' => 'Sunflower',
                'variety' => 'Hybrid',
                'pack_size' => '1 kg',
                'is_demo_eligible' => true,
            ],
            // Pesticides
            [
                'code' => 'SKU010',
                'name' => 'CropGuard Insecticide',
                'category' => 'pesticides',
                'crop' => 'All',
                'variety' => null,
                'pack_size' => '500 ml',
                'is_demo_eligible' => false,
            ],
            [
                'code' => 'SKU011',
                'name' => 'FungiFree Fungicide',
                'category' => 'pesticides',
                'crop' => 'All',
                'variety' => null,
                'pack_size' => '250 gm',
                'is_demo_eligible' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::create([
                ...$product,
                'status' => 'active',
            ]);
        }
    }

    private function seedActivityTypes(): void
    {
        $activityTypes = [
            // PSA - Pre-Seasonal Activities
            ['code' => 'PSA001', 'name' => 'Farmer Meeting', 'category' => 'psa', 'points' => 5],
            ['code' => 'PSA002', 'name' => 'Retailer Meeting', 'category' => 'psa', 'points' => 5],
            ['code' => 'PSA003', 'name' => 'Village Promotion', 'category' => 'psa', 'points' => 10],
            ['code' => 'PSA004', 'name' => 'Krishi Mela', 'category' => 'psa', 'points' => 20],

            // PDA - Product Differentiation Activities
            ['code' => 'PDA001', 'name' => 'Product Demo', 'category' => 'pda', 'points' => 10],
            ['code' => 'PDA002', 'name' => 'Field Day', 'category' => 'pda', 'points' => 25],
            ['code' => 'PDA003', 'name' => 'Comparison Trial', 'category' => 'pda', 'points' => 15],

            // Demo Distribution
            ['code' => 'DEMO001', 'name' => 'Demo Distribution', 'category' => 'demo', 'points' => 10],
            ['code' => 'DEMO002', 'name' => 'Demo Stage Visit', 'category' => 'demo', 'points' => 5],
            ['code' => 'DEMO003', 'name' => 'Demo Harvest', 'category' => 'demo', 'points' => 15],

            // Others
            ['code' => 'OTH001', 'name' => 'Farmer Registration', 'category' => 'other', 'points' => 3],
            ['code' => 'OTH002', 'name' => 'Retailer Registration', 'category' => 'other', 'points' => 3],
            ['code' => 'OTH003', 'name' => 'Collection Follow-up', 'category' => 'other', 'points' => 2],
            ['code' => 'OTH004', 'name' => 'Complaint Resolution', 'category' => 'other', 'points' => 5],
        ];

        foreach ($activityTypes as $type) {
            ActivityType::create([
                ...$type,
                'status' => 'active',
                'requires_photo' => true,
                'requires_location' => true,
            ]);
        }
    }

    private function seedDemoMasters(): void
    {
        $cornProduct = Product::where('code', 'SKU001')->first();
        $paddyProduct = Product::where('code', 'SKU002')->first();
        $cottonProduct = Product::where('code', 'SKU003')->first();

        // Corn Demo Master
        $cornDemo = DemoMaster::create([
            'code' => 'DEMO-CORN-2024',
            'name' => 'Corn Super Demo 2024',
            'product_id' => $cornProduct?->id,
            'crop' => 'Corn',
            'variety' => 'Super',
            'season' => 'Kharif',
            'duration_days' => 120,
            'status' => 'active',
        ]);

        // Corn stage templates
        $cornStages = [
            ['stage_name' => 'Sowing', 'days_from_sowing' => 0, 'sequence' => 1],
            ['stage_name' => 'Germination Check', 'days_from_sowing' => 7, 'sequence' => 2],
            ['stage_name' => 'Vegetative Stage', 'days_from_sowing' => 30, 'sequence' => 3],
            ['stage_name' => 'Tasseling', 'days_from_sowing' => 55, 'sequence' => 4],
            ['stage_name' => 'Silking', 'days_from_sowing' => 60, 'sequence' => 5],
            ['stage_name' => 'Grain Filling', 'days_from_sowing' => 80, 'sequence' => 6],
            ['stage_name' => 'Maturity Check', 'days_from_sowing' => 100, 'sequence' => 7],
            ['stage_name' => 'Harvest', 'days_from_sowing' => 120, 'sequence' => 8],
        ];

        foreach ($cornStages as $stage) {
            DemoStageTemplate::create([
                'demo_master_id' => $cornDemo->id,
                ...$stage,
                'is_mandatory' => true,
                'requires_photo' => true,
            ]);
        }

        // Paddy Demo Master
        $paddyDemo = DemoMaster::create([
            'code' => 'DEMO-PADDY-2024',
            'name' => 'Paddy Gold Demo 2024',
            'product_id' => $paddyProduct?->id,
            'crop' => 'Paddy',
            'variety' => 'Gold',
            'season' => 'Kharif',
            'duration_days' => 135,
            'status' => 'active',
        ]);

        // Paddy stage templates
        $paddyStages = [
            ['stage_name' => 'Transplanting', 'days_from_sowing' => 0, 'sequence' => 1],
            ['stage_name' => 'Tillering', 'days_from_sowing' => 25, 'sequence' => 2],
            ['stage_name' => 'Panicle Initiation', 'days_from_sowing' => 55, 'sequence' => 3],
            ['stage_name' => 'Booting', 'days_from_sowing' => 75, 'sequence' => 4],
            ['stage_name' => 'Heading', 'days_from_sowing' => 85, 'sequence' => 5],
            ['stage_name' => 'Flowering', 'days_from_sowing' => 90, 'sequence' => 6],
            ['stage_name' => 'Grain Filling', 'days_from_sowing' => 110, 'sequence' => 7],
            ['stage_name' => 'Harvest', 'days_from_sowing' => 135, 'sequence' => 8],
        ];

        foreach ($paddyStages as $stage) {
            DemoStageTemplate::create([
                'demo_master_id' => $paddyDemo->id,
                ...$stage,
                'is_mandatory' => true,
                'requires_photo' => true,
            ]);
        }

        // Cotton Demo Master
        $cottonDemo = DemoMaster::create([
            'code' => 'DEMO-COTTON-2024',
            'name' => 'Cotton Premium Demo 2024',
            'product_id' => $cottonProduct?->id,
            'crop' => 'Cotton',
            'variety' => 'Premium',
            'season' => 'Kharif',
            'duration_days' => 180,
            'status' => 'active',
        ]);

        // Cotton stage templates
        $cottonStages = [
            ['stage_name' => 'Sowing', 'days_from_sowing' => 0, 'sequence' => 1],
            ['stage_name' => 'Germination', 'days_from_sowing' => 10, 'sequence' => 2],
            ['stage_name' => 'Squaring', 'days_from_sowing' => 45, 'sequence' => 3],
            ['stage_name' => 'Flowering', 'days_from_sowing' => 60, 'sequence' => 4],
            ['stage_name' => 'Boll Formation', 'days_from_sowing' => 90, 'sequence' => 5],
            ['stage_name' => 'Boll Opening', 'days_from_sowing' => 130, 'sequence' => 6],
            ['stage_name' => 'First Picking', 'days_from_sowing' => 150, 'sequence' => 7],
            ['stage_name' => 'Final Harvest', 'days_from_sowing' => 180, 'sequence' => 8],
        ];

        foreach ($cottonStages as $stage) {
            DemoStageTemplate::create([
                'demo_master_id' => $cottonDemo->id,
                ...$stage,
                'is_mandatory' => true,
                'requires_photo' => true,
            ]);
        }
    }
}
