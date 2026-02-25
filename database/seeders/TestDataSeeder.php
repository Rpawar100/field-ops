<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SdtvNode;
use App\Models\ZrthNode;
use App\Models\User;
use App\Models\Farmer;
use App\Models\Retailer;
use App\Models\Beat;

class TestDataSeeder extends Seeder
{
    /**
     * Seed test data for development.
     */
    public function run(): void
    {
        $this->seedFarmers();
        $this->seedRetailers();
        $this->seedBeats();

        $this->command->info('Test Data Seeded:');
        $this->command->info('- 10 Farmers');
        $this->command->info('- 5 Retailers');
        $this->command->info('- 3 Beats with Farmer/Retailer mappings');
    }

    private function seedFarmers(): void
    {
        $village = SdtvNode::where('level', 'village')->first();
        $fa = User::where('role', 'fa')->first();

        $farmers = [
            [
                'name' => 'Ramaiah Goud',
                'phone' => '9876500001',
                'gender' => 'male',
                'crops' => ['Corn', 'Paddy'],
                'total_land_acres' => 5.5,
                'irrigation_type' => 'borewell',
                'farmer_type' => 'demo',
                'farmer_category' => 'influencer',
            ],
            [
                'name' => 'Lakshmi Devi',
                'phone' => '9876500002',
                'gender' => 'female',
                'crops' => ['Cotton', 'Sunflower'],
                'total_land_acres' => 3.0,
                'irrigation_type' => 'canal',
                'farmer_type' => 'user',
                'farmer_category' => 'small',
            ],
            [
                'name' => 'Venkateshwarlu',
                'phone' => '9876500003',
                'gender' => 'male',
                'crops' => ['Paddy'],
                'total_land_acres' => 8.0,
                'irrigation_type' => 'borewell',
                'farmer_type' => 'pda',
                'farmer_category' => 'influencer',
            ],
            [
                'name' => 'Satyanarayana',
                'phone' => '9876500004',
                'gender' => 'male',
                'crops' => ['Corn', 'Cotton'],
                'total_land_acres' => 4.5,
                'irrigation_type' => 'rainfed',
                'farmer_type' => 'demo',
                'farmer_category' => 'small',
            ],
            [
                'name' => 'Anjamma',
                'phone' => '9876500005',
                'gender' => 'female',
                'crops' => ['Sunflower', 'Groundnut'],
                'total_land_acres' => 2.5,
                'irrigation_type' => 'canal',
                'farmer_type' => 'user',
                'farmer_category' => 'small',
            ],
            [
                'name' => 'Narasimha Reddy',
                'phone' => '9876500006',
                'gender' => 'male',
                'crops' => ['Paddy', 'Cotton'],
                'total_land_acres' => 12.0,
                'irrigation_type' => 'borewell',
                'farmer_type' => 'demo',
                'farmer_category' => 'influencer',
            ],
            [
                'name' => 'Suresh Kumar',
                'phone' => '9876500007',
                'gender' => 'male',
                'crops' => ['Corn'],
                'total_land_acres' => 6.0,
                'irrigation_type' => 'drip',
                'farmer_type' => 'pda',
                'farmer_category' => 'small',
            ],
            [
                'name' => 'Padmavathi',
                'phone' => '9876500008',
                'gender' => 'female',
                'crops' => ['Cotton', 'Chilli'],
                'total_land_acres' => 3.5,
                'irrigation_type' => 'canal',
                'farmer_type' => 'user',
                'farmer_category' => 'small',
            ],
            [
                'name' => 'Balakrishna',
                'phone' => '9876500009',
                'gender' => 'male',
                'crops' => ['Paddy', 'Corn'],
                'total_land_acres' => 7.0,
                'irrigation_type' => 'borewell',
                'farmer_type' => 'non_user',
                'farmer_category' => 'small',
            ],
            [
                'name' => 'Srinivas Rao',
                'phone' => '9876500010',
                'gender' => 'male',
                'crops' => ['Sunflower', 'Paddy'],
                'total_land_acres' => 15.0,
                'irrigation_type' => 'canal',
                'farmer_type' => 'demo',
                'farmer_category' => 'influencer',
            ],
        ];

        foreach ($farmers as $index => $farmerData) {
            Farmer::create([
                ...$farmerData,
                'sdtv_node_id' => $village?->id,
                'registered_by' => $fa?->id,
                'latitude' => 17.385 + ($index * 0.01),
                'longitude' => 78.486 + ($index * 0.01),
                'address' => $farmerData['name'] . ' House, ' . ($village?->name ?? 'Village'),
                'status' => 'active',
            ]);
        }
    }

    private function seedRetailers(): void
    {
        $village = SdtvNode::where('level', 'village')->first();
        $fa = User::where('role', 'fa')->first();

        $retailers = [
            [
                'name' => 'Krishna Murthy',
                'shop_name' => 'Sri Krishna Agro Traders',
                'phone' => '9876600001',
                'business_type' => 'proprietary',
                'outlet_type' => 'agro_dealer',
                'annual_business_value' => 2500000,
            ],
            [
                'name' => 'Ravi Shankar',
                'shop_name' => 'Shankar Seeds & Fertilizers',
                'phone' => '9876600002',
                'business_type' => 'partnership',
                'outlet_type' => 'agro_dealer',
                'annual_business_value' => 1800000,
            ],
            [
                'name' => 'Prasad Reddy',
                'shop_name' => 'Reddy Agri Inputs',
                'phone' => '9876600003',
                'business_type' => 'proprietary',
                'outlet_type' => 'seed_dealer',
                'annual_business_value' => 3500000,
                'gst_number' => '36AABCR1234A1Z5',
            ],
            [
                'name' => 'Laxman Rao',
                'shop_name' => 'Laxman Krishi Kendra',
                'phone' => '9876600004',
                'business_type' => 'proprietary',
                'outlet_type' => 'agro_dealer',
                'annual_business_value' => 1200000,
            ],
            [
                'name' => 'Ganesh Kumar',
                'shop_name' => 'Ganesh Farm Solutions',
                'phone' => '9876600005',
                'business_type' => 'llp',
                'outlet_type' => 'multi_brand',
                'annual_business_value' => 5000000,
                'gst_number' => '36AABCG5678B2Y6',
                'pan_number' => 'AABCG5678B',
            ],
        ];

        foreach ($retailers as $index => $retailerData) {
            Retailer::create([
                ...$retailerData,
                'sdtv_node_id' => $village?->id,
                'registered_by' => $fa?->id,
                'latitude' => 17.390 + ($index * 0.005),
                'longitude' => 78.490 + ($index * 0.005),
                'address' => $retailerData['shop_name'] . ', Main Road, ' . ($village?->name ?? 'Village'),
                'status' => 'active',
            ]);
        }
    }

    private function seedBeats(): void
    {
        $hq = ZrthNode::where('level', 'headquarter')->first();
        $village = SdtvNode::where('level', 'village')->first();
        $fa = User::where('role', 'fa')->first();

        $farmers = Farmer::all();
        $retailers = Retailer::all();

        // Beat 1 - Monday/Thursday
        $beat1 = Beat::create([
            'code' => 'BEAT-001',
            'name' => 'Manchal Market Beat',
            'user_id' => $fa?->id,
            'zrth_node_id' => $hq?->id,
            'sdtv_node_id' => $village?->id,
            'scheduled_days' => ['monday', 'thursday'],
            'status' => 'active',
        ]);
        $beat1->farmers()->attach($farmers->take(4)->pluck('id'));
        $beat1->retailers()->attach($retailers->take(2)->pluck('id'));

        // Beat 2 - Tuesday/Friday
        $beat2 = Beat::create([
            'code' => 'BEAT-002',
            'name' => 'Peddemul Village Beat',
            'user_id' => $fa?->id,
            'zrth_node_id' => $hq?->id,
            'sdtv_node_id' => $village?->id,
            'scheduled_days' => ['tuesday', 'friday'],
            'status' => 'active',
        ]);
        $beat2->farmers()->attach($farmers->skip(4)->take(3)->pluck('id'));
        $beat2->retailers()->attach($retailers->skip(2)->take(2)->pluck('id'));

        // Beat 3 - Wednesday/Saturday
        $beat3 = Beat::create([
            'code' => 'BEAT-003',
            'name' => 'Kothur Area Beat',
            'user_id' => $fa?->id,
            'zrth_node_id' => $hq?->id,
            'sdtv_node_id' => $village?->id,
            'scheduled_days' => ['wednesday', 'saturday'],
            'status' => 'active',
        ]);
        $beat3->farmers()->attach($farmers->skip(7)->take(3)->pluck('id'));
        $beat3->retailers()->attach($retailers->skip(4)->take(1)->pluck('id'));
    }
}
