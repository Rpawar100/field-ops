<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CropProductSeeder extends Seeder
{
    /**
     * Seed crops, brands, product categories, varieties, and products.
     */
    public function run(): void
    {
        if (DB::table('crops')->where('code', 'CROP-RICE')->exists()) {
            $this->command->info('CropProductSeeder: Data already exists, skipping.');
            return;
        }

        $now = Carbon::now();

        DB::transaction(function () use ($now) {

            // ─── CROPS (10) ──────────────────────────────────────
            $crops = [
                ['code' => 'CROP-RICE',    'name' => 'Rice',       'local_name' => 'Chawal',    'crop_type' => 'kharif',    'duration' => 120],
                ['code' => 'CROP-WHEAT',   'name' => 'Wheat',      'local_name' => 'Gehu',      'crop_type' => 'rabi',      'duration' => 130],
                ['code' => 'CROP-COTTON',  'name' => 'Cotton',     'local_name' => 'Kapas',     'crop_type' => 'kharif',    'duration' => 180],
                ['code' => 'CROP-SOYBN',   'name' => 'Soybean',    'local_name' => 'Soyabean',  'crop_type' => 'kharif',    'duration' => 100],
                ['code' => 'CROP-CORN',    'name' => 'Corn',       'local_name' => 'Makka',     'crop_type' => 'kharif',    'duration' => 110],
                ['code' => 'CROP-SUGAR',   'name' => 'Sugarcane',  'local_name' => 'Ganna',     'crop_type' => 'perennial', 'duration' => 365],
                ['code' => 'CROP-GNUT',    'name' => 'Groundnut',  'local_name' => 'Moongphali','crop_type' => 'kharif',    'duration' => 120],
                ['code' => 'CROP-CHILLI',  'name' => 'Chilli',     'local_name' => 'Mirchi',    'crop_type' => 'kharif',    'duration' => 150],
                ['code' => 'CROP-ONION',   'name' => 'Onion',      'local_name' => 'Kanda',     'crop_type' => 'rabi',      'duration' => 120],
                ['code' => 'CROP-TOMATO',  'name' => 'Tomato',     'local_name' => 'Tamatar',   'crop_type' => 'zaid',      'duration' => 90],
            ];

            $cropIds = [];
            foreach ($crops as $c) {
                $id = DB::table('crops')->insertGetId([
                    'code'                 => $c['code'],
                    'name'                 => $c['name'],
                    'local_name'           => $c['local_name'],
                    'crop_type'            => $c['crop_type'],
                    'typical_duration_days' => $c['duration'],
                    'description'          => $c['name'] . ' crop for agricultural operations',
                    'status'               => 'active',
                    'created_at'           => $now,
                    'updated_at'           => $now,
                ]);
                $cropIds[$c['code']] = $id;
            }

            // ─── BRANDS (5) ─────────────────────────────────────
            $brands = [
                ['code' => 'BRD-AGRMAX',  'name' => 'AgriMax',     'company' => 'AgriMax Seed Pvt Ltd'],
                ['code' => 'BRD-CRPGRD',  'name' => 'CropGuard',   'company' => 'CropGuard Agrochemicals'],
                ['code' => 'BRD-BIOGRW',  'name' => 'BioGrow',     'company' => 'BioGrow Organics India'],
                ['code' => 'BRD-SEDPRO',  'name' => 'SeedPro',     'company' => 'SeedPro Hybrids Ltd'],
                ['code' => 'BRD-NUTRFD',  'name' => 'NutriField',  'company' => 'NutriField Bio Sciences'],
            ];

            $brandIds = [];
            foreach ($brands as $b) {
                $id = DB::table('brands')->insertGetId([
                    'code'         => $b['code'],
                    'name'         => $b['name'],
                    'company_name' => $b['company'],
                    'status'       => 'active',
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]);
                $brandIds[$b['code']] = $id;
            }

            // ─── PRODUCT CATEGORIES (3) ──────────────────────────
            $categories = [
                ['code' => 'CAT-SEED', 'name' => 'Seeds',         'type' => 'seed'],
                ['code' => 'CAT-PEST', 'name' => 'Pesticides',    'type' => 'pesticide'],
                ['code' => 'CAT-BIO',  'name' => 'Bio Products',  'type' => 'bio_product'],
            ];

            $catIds = [];
            foreach ($categories as $cat) {
                $id = DB::table('product_categories')->insertGetId([
                    'code'        => $cat['code'],
                    'name'        => $cat['name'],
                    'type'        => $cat['type'],
                    'parent_id'   => null,
                    'description' => $cat['name'] . ' product category',
                    'status'      => 'active',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
                $catIds[$cat['code']] = $id;
            }

            // ─── VARIETIES (20) ─────────────────────────────────
            $varieties = [
                ['code' => 'VAR-RICE-HYB1',   'name' => 'AgriMax Rice Hybrid 101',     'crop' => 'CROP-RICE',   'brand' => 'BRD-AGRMAX', 'maturity' => 115],
                ['code' => 'VAR-RICE-HYB2',   'name' => 'SeedPro Paddy Gold',          'crop' => 'CROP-RICE',   'brand' => 'BRD-SEDPRO', 'maturity' => 125],
                ['code' => 'VAR-WHEAT-HD1',   'name' => 'AgriMax Wheat Premium',       'crop' => 'CROP-WHEAT',  'brand' => 'BRD-AGRMAX', 'maturity' => 125],
                ['code' => 'VAR-WHEAT-SJ2',   'name' => 'SeedPro Gehun Super',         'crop' => 'CROP-WHEAT',  'brand' => 'BRD-SEDPRO', 'maturity' => 135],
                ['code' => 'VAR-COTTON-BT1',  'name' => 'AgriMax BT Cotton 9021',      'crop' => 'CROP-COTTON', 'brand' => 'BRD-AGRMAX', 'maturity' => 170],
                ['code' => 'VAR-COTTON-HYB',  'name' => 'SeedPro Cotton Hybrid',       'crop' => 'CROP-COTTON', 'brand' => 'BRD-SEDPRO', 'maturity' => 185],
                ['code' => 'VAR-SOYBN-JS1',   'name' => 'AgriMax Soybean JS-9305',     'crop' => 'CROP-SOYBN',  'brand' => 'BRD-AGRMAX', 'maturity' => 95],
                ['code' => 'VAR-SOYBN-NRC',   'name' => 'NutriField Soya NRC-37',      'crop' => 'CROP-SOYBN',  'brand' => 'BRD-NUTRFD', 'maturity' => 100],
                ['code' => 'VAR-CORN-HYB1',   'name' => 'AgriMax Corn Super 900M',     'crop' => 'CROP-CORN',   'brand' => 'BRD-AGRMAX', 'maturity' => 105],
                ['code' => 'VAR-CORN-SWE',    'name' => 'SeedPro Sweet Corn Gold',     'crop' => 'CROP-CORN',   'brand' => 'BRD-SEDPRO', 'maturity' => 90],
                ['code' => 'VAR-SUGAR-CO1',   'name' => 'AgriMax Sugarcane CO-86032',  'crop' => 'CROP-SUGAR',  'brand' => 'BRD-AGRMAX', 'maturity' => 360],
                ['code' => 'VAR-SUGAR-VS1',   'name' => 'SeedPro Ganna VSI',           'crop' => 'CROP-SUGAR',  'brand' => 'BRD-SEDPRO', 'maturity' => 365],
                ['code' => 'VAR-GNUT-TG1',    'name' => 'AgriMax Groundnut TG-37A',    'crop' => 'CROP-GNUT',   'brand' => 'BRD-AGRMAX', 'maturity' => 110],
                ['code' => 'VAR-GNUT-GG1',    'name' => 'NutriField Groundnut GG-20',  'crop' => 'CROP-GNUT',   'brand' => 'BRD-NUTRFD', 'maturity' => 120],
                ['code' => 'VAR-CHILL-TJ1',   'name' => 'AgriMax Chilli Tejal',        'crop' => 'CROP-CHILLI', 'brand' => 'BRD-AGRMAX', 'maturity' => 140],
                ['code' => 'VAR-CHILL-BWN',   'name' => 'SeedPro Mirchi Bawan',        'crop' => 'CROP-CHILLI', 'brand' => 'BRD-SEDPRO', 'maturity' => 155],
                ['code' => 'VAR-ONION-NSK',   'name' => 'AgriMax Onion Nashik Red',    'crop' => 'CROP-ONION',  'brand' => 'BRD-AGRMAX', 'maturity' => 110],
                ['code' => 'VAR-ONION-AGR',   'name' => 'NutriField Onion Agrifound',  'crop' => 'CROP-ONION',  'brand' => 'BRD-NUTRFD', 'maturity' => 115],
                ['code' => 'VAR-TOMATO-PUS',  'name' => 'AgriMax Tomato Pusa Ruby',    'crop' => 'CROP-TOMATO', 'brand' => 'BRD-AGRMAX', 'maturity' => 85],
                ['code' => 'VAR-TOMATO-ARK',  'name' => 'SeedPro Tomato Arka Vikas',   'crop' => 'CROP-TOMATO', 'brand' => 'BRD-SEDPRO', 'maturity' => 90],
            ];

            $varietyIds = [];
            foreach ($varieties as $v) {
                $id = DB::table('varieties')->insertGetId([
                    'code'                => $v['code'],
                    'name'                => $v['name'],
                    'crop_id'             => $cropIds[$v['crop']],
                    'brand_id'            => $brandIds[$v['brand']],
                    'maturity_days'       => $v['maturity'],
                    'features'            => 'High yield variety suitable for Indian conditions',
                    'suitable_conditions' => 'Well-drained soil with moderate irrigation',
                    'status'              => 'active',
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ]);
                $varietyIds[$v['code']] = $id;
            }

            // ─── PRODUCTS (30) ──────────────────────────────────
            $products = [
                // Seeds (15)
                ['sku' => 'SKU-S001', 'name' => 'AgriMax Rice Hybrid 101 - 4kg',       'display' => 'Rice Hybrid 101',      'cat' => 'CAT-SEED', 'type' => 'seed',        'brand' => 'BRD-AGRMAX', 'variety' => 'VAR-RICE-HYB1',  'crop' => 'CROP-RICE',   'size' => '4 kg',   'unit' => 'kg',  'qty' => 4,   'mrp' => 850.00,  'sell' => 780.00,  'dist' => 680.00, 'hsn' => '1006', 'gst' => '5'],
                ['sku' => 'SKU-S002', 'name' => 'SeedPro Paddy Gold - 5kg',            'display' => 'Paddy Gold',           'cat' => 'CAT-SEED', 'type' => 'seed',        'brand' => 'BRD-SEDPRO', 'variety' => 'VAR-RICE-HYB2',  'crop' => 'CROP-RICE',   'size' => '5 kg',   'unit' => 'kg',  'qty' => 5,   'mrp' => 1050.00, 'sell' => 950.00,  'dist' => 820.00, 'hsn' => '1006', 'gst' => '5'],
                ['sku' => 'SKU-S003', 'name' => 'AgriMax Wheat Premium - 5kg',         'display' => 'Wheat Premium',        'cat' => 'CAT-SEED', 'type' => 'seed',        'brand' => 'BRD-AGRMAX', 'variety' => 'VAR-WHEAT-HD1',  'crop' => 'CROP-WHEAT',  'size' => '5 kg',   'unit' => 'kg',  'qty' => 5,   'mrp' => 650.00,  'sell' => 580.00,  'dist' => 490.00, 'hsn' => '1001', 'gst' => '5'],
                ['sku' => 'SKU-S004', 'name' => 'AgriMax BT Cotton 9021 - 450gm',     'display' => 'BT Cotton 9021',       'cat' => 'CAT-SEED', 'type' => 'seed',        'brand' => 'BRD-AGRMAX', 'variety' => 'VAR-COTTON-BT1', 'crop' => 'CROP-COTTON', 'size' => '450 gm', 'unit' => 'g',   'qty' => 450, 'mrp' => 1600.00, 'sell' => 1500.00, 'dist' => 1300.00,'hsn' => '1207', 'gst' => '5'],
                ['sku' => 'SKU-S005', 'name' => 'SeedPro Cotton Hybrid - 450gm',      'display' => 'Cotton Hybrid',        'cat' => 'CAT-SEED', 'type' => 'seed',        'brand' => 'BRD-SEDPRO', 'variety' => 'VAR-COTTON-HYB', 'crop' => 'CROP-COTTON', 'size' => '450 gm', 'unit' => 'g',   'qty' => 450, 'mrp' => 1450.00, 'sell' => 1350.00, 'dist' => 1180.00,'hsn' => '1207', 'gst' => '5'],
                ['sku' => 'SKU-S006', 'name' => 'AgriMax Soybean JS-9305 - 5kg',      'display' => 'Soybean JS-9305',      'cat' => 'CAT-SEED', 'type' => 'seed',        'brand' => 'BRD-AGRMAX', 'variety' => 'VAR-SOYBN-JS1',  'crop' => 'CROP-SOYBN',  'size' => '5 kg',   'unit' => 'kg',  'qty' => 5,   'mrp' => 550.00,  'sell' => 490.00,  'dist' => 420.00, 'hsn' => '1201', 'gst' => '5'],
                ['sku' => 'SKU-S007', 'name' => 'AgriMax Corn Super 900M - 4kg',      'display' => 'Corn Super 900M',      'cat' => 'CAT-SEED', 'type' => 'seed',        'brand' => 'BRD-AGRMAX', 'variety' => 'VAR-CORN-HYB1',  'crop' => 'CROP-CORN',   'size' => '4 kg',   'unit' => 'kg',  'qty' => 4,   'mrp' => 980.00,  'sell' => 890.00,  'dist' => 780.00, 'hsn' => '1005', 'gst' => '5'],
                ['sku' => 'SKU-S008', 'name' => 'SeedPro Sweet Corn Gold - 1kg',      'display' => 'Sweet Corn Gold',      'cat' => 'CAT-SEED', 'type' => 'seed',        'brand' => 'BRD-SEDPRO', 'variety' => 'VAR-CORN-SWE',   'crop' => 'CROP-CORN',   'size' => '1 kg',   'unit' => 'kg',  'qty' => 1,   'mrp' => 480.00,  'sell' => 430.00,  'dist' => 370.00, 'hsn' => '1005', 'gst' => '5'],
                ['sku' => 'SKU-S009', 'name' => 'AgriMax Groundnut TG-37A - 10kg',    'display' => 'Groundnut TG-37A',     'cat' => 'CAT-SEED', 'type' => 'seed',        'brand' => 'BRD-AGRMAX', 'variety' => 'VAR-GNUT-TG1',   'crop' => 'CROP-GNUT',   'size' => '10 kg',  'unit' => 'kg',  'qty' => 10,  'mrp' => 1200.00, 'sell' => 1080.00, 'dist' => 950.00, 'hsn' => '1202', 'gst' => '5'],
                ['sku' => 'SKU-S010', 'name' => 'AgriMax Chilli Tejal - 100gm',       'display' => 'Chilli Tejal',         'cat' => 'CAT-SEED', 'type' => 'seed',        'brand' => 'BRD-AGRMAX', 'variety' => 'VAR-CHILL-TJ1',  'crop' => 'CROP-CHILLI', 'size' => '100 gm', 'unit' => 'g',   'qty' => 100, 'mrp' => 380.00,  'sell' => 340.00,  'dist' => 290.00, 'hsn' => '0904', 'gst' => '5'],
                ['sku' => 'SKU-S011', 'name' => 'AgriMax Onion Nashik Red - 500gm',   'display' => 'Onion Nashik Red',     'cat' => 'CAT-SEED', 'type' => 'seed',        'brand' => 'BRD-AGRMAX', 'variety' => 'VAR-ONION-NSK',  'crop' => 'CROP-ONION',  'size' => '500 gm', 'unit' => 'g',   'qty' => 500, 'mrp' => 750.00,  'sell' => 680.00,  'dist' => 590.00, 'hsn' => '0703', 'gst' => '5'],
                ['sku' => 'SKU-S012', 'name' => 'AgriMax Tomato Pusa Ruby - 50gm',    'display' => 'Tomato Pusa Ruby',     'cat' => 'CAT-SEED', 'type' => 'seed',        'brand' => 'BRD-AGRMAX', 'variety' => 'VAR-TOMATO-PUS', 'crop' => 'CROP-TOMATO', 'size' => '50 gm',  'unit' => 'g',   'qty' => 50,  'mrp' => 280.00,  'sell' => 250.00,  'dist' => 210.00, 'hsn' => '0702', 'gst' => '5'],
                ['sku' => 'SKU-S013', 'name' => 'NutriField Soya NRC-37 - 5kg',       'display' => 'Soya NRC-37',          'cat' => 'CAT-SEED', 'type' => 'seed',        'brand' => 'BRD-NUTRFD', 'variety' => 'VAR-SOYBN-NRC',  'crop' => 'CROP-SOYBN',  'size' => '5 kg',   'unit' => 'kg',  'qty' => 5,   'mrp' => 600.00,  'sell' => 540.00,  'dist' => 460.00, 'hsn' => '1201', 'gst' => '5'],
                ['sku' => 'SKU-S014', 'name' => 'SeedPro Gehun Super - 10kg',         'display' => 'Gehun Super',          'cat' => 'CAT-SEED', 'type' => 'seed',        'brand' => 'BRD-SEDPRO', 'variety' => 'VAR-WHEAT-SJ2',  'crop' => 'CROP-WHEAT',  'size' => '10 kg',  'unit' => 'kg',  'qty' => 10,  'mrp' => 1100.00, 'sell' => 990.00,  'dist' => 860.00, 'hsn' => '1001', 'gst' => '5'],
                ['sku' => 'SKU-S015', 'name' => 'AgriMax Sugarcane CO-86032 - sets',   'display' => 'Sugarcane CO-86032',   'cat' => 'CAT-SEED', 'type' => 'seed',        'brand' => 'BRD-AGRMAX', 'variety' => 'VAR-SUGAR-CO1',  'crop' => 'CROP-SUGAR',  'size' => '100 sets','unit' => 'pcs','qty' => 100, 'mrp' => 2000.00, 'sell' => 1800.00, 'dist' => 1550.00,'hsn' => '1212', 'gst' => '5'],
                // Pesticides (10)
                ['sku' => 'SKU-P001', 'name' => 'CropGuard Imidacloprid 17.8% SL',    'display' => 'Imidacloprid 17.8%',   'cat' => 'CAT-PEST', 'type' => 'pesticide',   'brand' => 'BRD-CRPGRD', 'variety' => null,              'crop' => null,          'size' => '250 ml', 'unit' => 'ml',  'qty' => 250, 'mrp' => 320.00,  'sell' => 290.00,  'dist' => 245.00, 'hsn' => '3808', 'gst' => '18'],
                ['sku' => 'SKU-P002', 'name' => 'CropGuard Chlorpyrifos 20% EC',      'display' => 'Chlorpyrifos 20%',     'cat' => 'CAT-PEST', 'type' => 'pesticide',   'brand' => 'BRD-CRPGRD', 'variety' => null,              'crop' => null,          'size' => '500 ml', 'unit' => 'ml',  'qty' => 500, 'mrp' => 210.00,  'sell' => 185.00,  'dist' => 155.00, 'hsn' => '3808', 'gst' => '18'],
                ['sku' => 'SKU-P003', 'name' => 'CropGuard Mancozeb 75% WP',          'display' => 'Mancozeb 75% WP',      'cat' => 'CAT-PEST', 'type' => 'pesticide',   'brand' => 'BRD-CRPGRD', 'variety' => null,              'crop' => null,          'size' => '500 gm', 'unit' => 'g',   'qty' => 500, 'mrp' => 280.00,  'sell' => 250.00,  'dist' => 210.00, 'hsn' => '3808', 'gst' => '18'],
                ['sku' => 'SKU-P004', 'name' => 'CropGuard Lambda Cyhalothrin 5% EC',  'display' => 'Lambda Cyhalothrin',   'cat' => 'CAT-PEST', 'type' => 'pesticide',   'brand' => 'BRD-CRPGRD', 'variety' => null,              'crop' => null,          'size' => '500 ml', 'unit' => 'ml',  'qty' => 500, 'mrp' => 420.00,  'sell' => 380.00,  'dist' => 320.00, 'hsn' => '3808', 'gst' => '18'],
                ['sku' => 'SKU-P005', 'name' => 'CropGuard Acephate 75% SP',           'display' => 'Acephate 75% SP',      'cat' => 'CAT-PEST', 'type' => 'pesticide',   'brand' => 'BRD-CRPGRD', 'variety' => null,              'crop' => null,          'size' => '250 gm', 'unit' => 'g',   'qty' => 250, 'mrp' => 180.00,  'sell' => 160.00,  'dist' => 135.00, 'hsn' => '3808', 'gst' => '18'],
                ['sku' => 'SKU-P006', 'name' => 'CropGuard Carbendazim 50% WP',        'display' => 'Carbendazim 50%',      'cat' => 'CAT-PEST', 'type' => 'pesticide',   'brand' => 'BRD-CRPGRD', 'variety' => null,              'crop' => null,          'size' => '500 gm', 'unit' => 'g',   'qty' => 500, 'mrp' => 350.00,  'sell' => 310.00,  'dist' => 265.00, 'hsn' => '3808', 'gst' => '18'],
                ['sku' => 'SKU-P007', 'name' => 'CropGuard Cypermethrin 25% EC',       'display' => 'Cypermethrin 25%',     'cat' => 'CAT-PEST', 'type' => 'pesticide',   'brand' => 'BRD-CRPGRD', 'variety' => null,              'crop' => null,          'size' => '1 L',    'unit' => 'ml',  'qty' => 1000,'mrp' => 480.00,  'sell' => 430.00,  'dist' => 370.00, 'hsn' => '3808', 'gst' => '18'],
                ['sku' => 'SKU-P008', 'name' => 'CropGuard Glyphosate 41% SL',         'display' => 'Glyphosate 41%',       'cat' => 'CAT-PEST', 'type' => 'pesticide',   'brand' => 'BRD-CRPGRD', 'variety' => null,              'crop' => null,          'size' => '1 L',    'unit' => 'ml',  'qty' => 1000,'mrp' => 350.00,  'sell' => 310.00,  'dist' => 260.00, 'hsn' => '3808', 'gst' => '18'],
                ['sku' => 'SKU-P009', 'name' => 'CropGuard Thiamethoxam 25% WG',       'display' => 'Thiamethoxam 25%',     'cat' => 'CAT-PEST', 'type' => 'pesticide',   'brand' => 'BRD-CRPGRD', 'variety' => null,              'crop' => null,          'size' => '100 gm', 'unit' => 'g',   'qty' => 100, 'mrp' => 290.00,  'sell' => 260.00,  'dist' => 220.00, 'hsn' => '3808', 'gst' => '18'],
                ['sku' => 'SKU-P010', 'name' => 'CropGuard Propiconazole 25% EC',      'display' => 'Propiconazole 25%',    'cat' => 'CAT-PEST', 'type' => 'pesticide',   'brand' => 'BRD-CRPGRD', 'variety' => null,              'crop' => null,          'size' => '250 ml', 'unit' => 'ml',  'qty' => 250, 'mrp' => 380.00,  'sell' => 340.00,  'dist' => 290.00, 'hsn' => '3808', 'gst' => '18'],
                // Bio Products (5)
                ['sku' => 'SKU-B001', 'name' => 'BioGrow Trichoderma Viride',          'display' => 'Trichoderma Viride',   'cat' => 'CAT-BIO',  'type' => 'bio_product', 'brand' => 'BRD-BIOGRW', 'variety' => null,              'crop' => null,          'size' => '1 kg',   'unit' => 'kg',  'qty' => 1,   'mrp' => 180.00,  'sell' => 160.00,  'dist' => 130.00, 'hsn' => '3002', 'gst' => '12'],
                ['sku' => 'SKU-B002', 'name' => 'BioGrow Pseudomonas Fluorescens',     'display' => 'Pseudomonas',          'cat' => 'CAT-BIO',  'type' => 'bio_product', 'brand' => 'BRD-BIOGRW', 'variety' => null,              'crop' => null,          'size' => '1 kg',   'unit' => 'kg',  'qty' => 1,   'mrp' => 200.00,  'sell' => 175.00,  'dist' => 145.00, 'hsn' => '3002', 'gst' => '12'],
                ['sku' => 'SKU-B003', 'name' => 'NutriField Humic Acid 98%',           'display' => 'Humic Acid 98%',       'cat' => 'CAT-BIO',  'type' => 'bio_product', 'brand' => 'BRD-NUTRFD', 'variety' => null,              'crop' => null,          'size' => '500 gm', 'unit' => 'g',   'qty' => 500, 'mrp' => 250.00,  'sell' => 220.00,  'dist' => 185.00, 'hsn' => '3101', 'gst' => '12'],
                ['sku' => 'SKU-B004', 'name' => 'BioGrow Neem Oil Emulsion',           'display' => 'Neem Oil Emulsion',    'cat' => 'CAT-BIO',  'type' => 'bio_product', 'brand' => 'BRD-BIOGRW', 'variety' => null,              'crop' => null,          'size' => '500 ml', 'unit' => 'ml',  'qty' => 500, 'mrp' => 150.00,  'sell' => 130.00,  'dist' => 110.00, 'hsn' => '1515', 'gst' => '12'],
                ['sku' => 'SKU-B005', 'name' => 'NutriField Seaweed Extract',          'display' => 'Seaweed Extract',      'cat' => 'CAT-BIO',  'type' => 'bio_product', 'brand' => 'BRD-NUTRFD', 'variety' => null,              'crop' => null,          'size' => '1 L',    'unit' => 'ml',  'qty' => 1000,'mrp' => 300.00,  'sell' => 270.00,  'dist' => 225.00, 'hsn' => '1212', 'gst' => '12'],
            ];

            $adminId = DB::table('users')->where('role', 'nsm')->value('id');

            foreach ($products as $p) {
                $productId = DB::table('products')->insertGetId([
                    'sku_code'               => $p['sku'],
                    'sku_name'               => $p['name'],
                    'display_name'           => $p['display'],
                    'category_id'            => $catIds[$p['cat']],
                    'product_type'           => $p['type'],
                    'brand_id'               => $brandIds[$p['brand']],
                    'variety_id'             => $p['variety'] ? ($varietyIds[$p['variety']] ?? null) : null,
                    'crop_id'                => $p['crop'] ? ($cropIds[$p['crop']] ?? null) : null,
                    'pack_size'              => $p['size'],
                    'pack_unit'              => $p['unit'],
                    'pack_quantity'          => $p['qty'],
                    'packs_per_case'         => 10,
                    'mrp'                    => $p['mrp'],
                    'selling_price'          => $p['sell'],
                    'distributor_price'      => $p['dist'],
                    'retailer_margin_percent' => round((($p['mrp'] - $p['sell']) / $p['mrp']) * 100, 2),
                    'hsn_code'               => $p['hsn'],
                    'gst_rate'               => $p['gst'],
                    'description'            => $p['name'] . ' - agricultural product',
                    'applicable_crop_ids'    => $p['crop'] ? json_encode([$cropIds[$p['crop']]]) : null,
                    'status'                 => 'active',
                    'launch_date'            => '2024-06-01',
                    'created_at'             => $now,
                    'updated_at'             => $now,
                ]);

                // Add price history entry
                DB::table('product_price_history')->insert([
                    'product_id'       => $productId,
                    'mrp'              => $p['mrp'],
                    'selling_price'    => $p['sell'],
                    'distributor_price' => $p['dist'],
                    'effective_from'   => '2024-06-01',
                    'effective_to'     => null,
                    'changed_by'       => $adminId,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ]);
            }
        });

        $this->command->info('CropProductSeeder: 10 Crops, 5 Brands, 3 Categories, 20 Varieties, 30 Products created.');
    }
}
