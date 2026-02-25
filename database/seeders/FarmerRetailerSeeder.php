<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FarmerRetailerSeeder extends Seeder
{
    /**
     * Seed 50 farmers, 20 retailers, 10 distributors,
     * plus farmer_crops, farmer_retailers, beat_farmers, beat_retailers.
     */
    public function run(): void
    {
        if (DB::table('farmers')->where('farmer_code', 'FRM-001')->exists()) {
            $this->command->info('FarmerRetailerSeeder: Data already exists, skipping.');
            return;
        }

        $now = Carbon::now();

        DB::transaction(function () use ($now) {

            $villages   = DB::table('villages')->where('status', 'active')->get();
            $beats      = DB::table('beats')->where('status', 'active')->get();
            $hqs        = DB::table('zrth_hierarchies')->where('level', 'hq')->pluck('id')->toArray();
            $cropIds    = DB::table('crops')->pluck('id', 'code')->toArray();
            $faUsers    = DB::table('users')->where('role', 'fa')->where('status', 'active')->pluck('id')->toArray();

            // ─── 50 FARMERS ──────────────────────────────────────
            $farmerNames = [
                ['name' => 'Ramesh Patil',         'father' => 'Shankar Patil',       'gender' => 'male',   'lang' => 'Marathi'],
                ['name' => 'Suresh Jadhav',        'father' => 'Dattatray Jadhav',    'gender' => 'male',   'lang' => 'Marathi'],
                ['name' => 'Laxmi Deshmukh',       'father' => 'Baburao Deshmukh',    'gender' => 'female', 'lang' => 'Marathi'],
                ['name' => 'Dnyaneshwar Shinde',   'father' => 'Vitthal Shinde',      'gender' => 'male',   'lang' => 'Marathi'],
                ['name' => 'Savitri Kale',         'father' => 'Tukaram Kale',        'gender' => 'female', 'lang' => 'Marathi'],
                ['name' => 'Arun Gaikwad',         'father' => 'Pandurang Gaikwad',   'gender' => 'male',   'lang' => 'Marathi'],
                ['name' => 'Bhimrao More',         'father' => 'Namdev More',         'gender' => 'male',   'lang' => 'Marathi'],
                ['name' => 'Mangala Pawar',        'father' => 'Govind Pawar',        'gender' => 'female', 'lang' => 'Marathi'],
                ['name' => 'Prakash Nikam',        'father' => 'Kashinath Nikam',     'gender' => 'male',   'lang' => 'Marathi'],
                ['name' => 'Jayram Bhosale',       'father' => 'Mahadeo Bhosale',     'gender' => 'male',   'lang' => 'Marathi'],
                ['name' => 'Haribhai Patel',       'father' => 'Manilal Patel',       'gender' => 'male',   'lang' => 'Gujarati'],
                ['name' => 'Ramaben Shah',         'father' => 'Keshavlal Shah',      'gender' => 'female', 'lang' => 'Gujarati'],
                ['name' => 'Dinesh Parmar',        'father' => 'Bhagwan Parmar',      'gender' => 'male',   'lang' => 'Gujarati'],
                ['name' => 'Kantilal Desai',       'father' => 'Lallubhai Desai',     'gender' => 'male',   'lang' => 'Gujarati'],
                ['name' => 'Jashodaben Thakor',    'father' => 'Ramjibhai Thakor',    'gender' => 'female', 'lang' => 'Gujarati'],
                ['name' => 'Mukesh Chaudhary',     'father' => 'Gordhanbhai Chaudhary','gender' => 'male',  'lang' => 'Gujarati'],
                ['name' => 'Nirmala Solanki',      'father' => 'Pravinbhai Solanki',  'gender' => 'female', 'lang' => 'Gujarati'],
                ['name' => 'Ashok Raval',          'father' => 'Dahyabhai Raval',     'gender' => 'male',   'lang' => 'Gujarati'],
                ['name' => 'Ramji Meena',          'father' => 'Bhagirath Meena',     'gender' => 'male',   'lang' => 'Hindi'],
                ['name' => 'Kailash Gurjar',       'father' => 'Hanuman Gurjar',      'gender' => 'male',   'lang' => 'Hindi'],
                ['name' => 'Om Prakash Yadav',     'father' => 'Ramdhan Yadav',       'gender' => 'male',   'lang' => 'Hindi'],
                ['name' => 'Sita Devi',            'father' => 'Laxman Singh',        'gender' => 'female', 'lang' => 'Hindi'],
                ['name' => 'Bhanwar Singh',        'father' => 'Fateh Singh',         'gender' => 'male',   'lang' => 'Hindi'],
                ['name' => 'Gomti Bai',            'father' => 'Shyam Lal',           'gender' => 'female', 'lang' => 'Hindi'],
                ['name' => 'Pappu Lal Jat',        'father' => 'Mohan Lal Jat',       'gender' => 'male',   'lang' => 'Hindi'],
                ['name' => 'Ratan Singh Rajput',   'father' => 'Bhagwan Singh Rajput','gender' => 'male',   'lang' => 'Hindi'],
                ['name' => 'Hari Prasad Tiwari',   'father' => 'Raghunath Tiwari',    'gender' => 'male',   'lang' => 'Hindi'],
                ['name' => 'Shanti Devi Malviya',  'father' => 'Balram Malviya',      'gender' => 'female', 'lang' => 'Hindi'],
                ['name' => 'Govind Lodhi',         'father' => 'Tulsiram Lodhi',      'gender' => 'male',   'lang' => 'Hindi'],
                ['name' => 'Kamlesh Prajapati',    'father' => 'Munna Lal Prajapati', 'gender' => 'male',   'lang' => 'Hindi'],
                ['name' => 'Basavaraj Patil',      'father' => 'Mallappa Patil',      'gender' => 'male',   'lang' => 'Kannada'],
                ['name' => 'Shivakumar Gowda',     'father' => 'Hanumanthappa Gowda', 'gender' => 'male',   'lang' => 'Kannada'],
                ['name' => 'Lakshmi Devi Hegde',   'father' => 'Ramachandra Hegde',   'gender' => 'female', 'lang' => 'Kannada'],
                ['name' => 'Manjunath Nayak',      'father' => 'Ganapati Nayak',      'gender' => 'male',   'lang' => 'Kannada'],
                ['name' => 'Gangamma Hosamani',    'father' => 'Irappa Hosamani',     'gender' => 'female', 'lang' => 'Kannada'],
                ['name' => 'Yallappa Kambali',     'father' => 'Siddalingappa Kambali','gender' => 'male',  'lang' => 'Kannada'],
                ['name' => 'Mahadev Kulkarni',     'father' => 'Veerabhadrappa K',    'gender' => 'male',   'lang' => 'Kannada'],
                ['name' => 'Renuka Meti',          'father' => 'Chandrashekar Meti',  'gender' => 'female', 'lang' => 'Kannada'],
                ['name' => 'Sangamesh Hiremath',   'father' => 'Basappa Hiremath',    'gender' => 'male',   'lang' => 'Kannada'],
                ['name' => 'Parashuram Biradar',   'father' => 'Kallappa Biradar',    'gender' => 'male',   'lang' => 'Kannada'],
                ['name' => 'Venkatesh Reddy',      'father' => 'Ranga Reddy',         'gender' => 'male',   'lang' => 'Telugu'],
                ['name' => 'Lakshmi Narayana',     'father' => 'Rama Rao',            'gender' => 'male',   'lang' => 'Telugu'],
                ['name' => 'Padmavathi Devi',      'father' => 'Krishnamurthy',       'gender' => 'female', 'lang' => 'Telugu'],
                ['name' => 'Srinivasa Rao',        'father' => 'Subrahmanyam',        'gender' => 'male',   'lang' => 'Telugu'],
                ['name' => 'Nagamma Naidu',        'father' => 'Apparao Naidu',       'gender' => 'female', 'lang' => 'Telugu'],
                ['name' => 'Ramakrishna Prasad',   'father' => 'Venkateswarlu',       'gender' => 'male',   'lang' => 'Telugu'],
                ['name' => 'Satyanarayana Goud',   'father' => 'Mallesh Goud',        'gender' => 'male',   'lang' => 'Telugu'],
                ['name' => 'Anjali Devi Reddy',    'father' => 'Narsingh Reddy',      'gender' => 'female', 'lang' => 'Telugu'],
                ['name' => 'Hanumantha Rao',       'father' => 'Narasimha Rao',       'gender' => 'male',   'lang' => 'Telugu'],
                ['name' => 'Balakrishna Naidu',    'father' => 'Gopala Naidu',        'gender' => 'male',   'lang' => 'Telugu'],
            ];

            $farmerTypes    = ['pda', 'demo', 'user', 'non_user'];
            $farmerCats     = ['small', 'influencer', 'non_user', 'large', 'progressive'];
            $irrigTypes     = ['rainfed', 'canal', 'tubewell', 'borewell', 'drip', 'sprinkler', 'mixed'];
            $landTypes      = ['owned', 'leased', 'mixed'];

            $farmerIds = [];
            $villageArr = $villages->toArray();
            $beatArr    = $beats->toArray();

            foreach ($farmerNames as $i => $f) {
                $vIdx      = $i % count($villageArr);
                $village   = $villageArr[$vIdx];
                $bIdx      = $i % count($beatArr);
                $beat      = $beatArr[$bIdx];
                $hqIdx     = $i % count($hqs);
                $faIdx     = $i % count($faUsers);

                $farmerId = DB::table('farmers')->insertGetId([
                    'uuid'                  => Str::uuid()->toString(),
                    'farmer_code'           => 'FRM-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                    'name'                  => $f['name'],
                    'father_name'           => $f['father'],
                    'mobile'                => '9' . str_pad(($i + 1) * 1111 + 800000000, 9, '0', STR_PAD_LEFT),
                    'alternate_mobile'      => null,
                    'whatsapp_number'       => null,
                    'email'                 => null,
                    'gender'                => $f['gender'],
                    'date_of_birth'         => Carbon::parse('1970-01-01')->addDays($i * 200)->format('Y-m-d'),
                    'address'               => $f['name'] . ' House, ' . $village->name . ' Village',
                    'village_id'            => $village->id,
                    'pincode'               => $village->pincode,
                    'latitude'              => $village->latitude + ($i * 0.001),
                    'longitude'             => $village->longitude + ($i * 0.001),
                    'landmark'              => 'Near ' . $village->name . ' Main Road',
                    'zrth_hierarchy_id'     => $hqs[$hqIdx],
                    'beat_id'               => $beat->id,
                    'total_landholding_acres' => round(2 + ($i % 15), 1),
                    'cultivable_land_acres'   => round(1.5 + ($i % 12), 1),
                    'landholding_type'      => $landTypes[$i % count($landTypes)],
                    'irrigation_type'       => $irrigTypes[$i % count($irrigTypes)],
                    'irrigation_sources'    => json_encode([$irrigTypes[$i % count($irrigTypes)]]),
                    'irrigated_land_acres'  => round(1 + ($i % 10), 1),
                    'farmer_type'           => $farmerTypes[$i % count($farmerTypes)],
                    'farmer_category'       => $farmerCats[$i % count($farmerCats)],
                    'is_progressive_farmer' => ($i % 5 === 0),
                    'is_kol'                => ($i % 10 === 0),
                    'preferred_language'    => $f['lang'],
                    'sms_consent'           => true,
                    'whatsapp_consent'      => ($i % 3 !== 0),
                    'registered_by'         => $faUsers[$faIdx],
                    'status'                => 'active',
                    'verification_status'   => ($i % 4 === 0) ? 'pending' : 'verified',
                    'sync_status'           => 'synced',
                    'synced_at'             => $now,
                    'created_at'            => $now,
                    'updated_at'            => $now,
                ]);

                $farmerIds[] = $farmerId;

                // ── beat_farmers link ──
                DB::table('beat_farmers')->insert([
                    'beat_id'    => $beat->id,
                    'farmer_id'  => $farmerId,
                    'is_primary' => ($i % 3 === 0),
                    'status'     => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // ─── FARMER CROPS (3-4 per farmer) ──────────────────
            $allCropIds = array_values($cropIds);
            $seasons    = ['kharif', 'rabi', 'zaid'];
            $statuses   = ['current', 'past', 'planned'];

            $farmerCropRows = [];
            foreach ($farmerIds as $idx => $fId) {
                $numCrops = 3 + ($idx % 2); // 3 or 4
                for ($c = 0; $c < $numCrops; $c++) {
                    $cropId = $allCropIds[($idx + $c) % count($allCropIds)];
                    $farmerCropRows[] = [
                        'farmer_id'            => $fId,
                        'crop_id'              => $cropId,
                        'area_acres'           => round(0.5 + ($c * 0.8), 2),
                        'season'               => $seasons[$c % count($seasons)],
                        'years_cultivating'    => 3 + ($idx % 10),
                        'average_yield_per_acre' => round(8 + ($idx % 12) * 0.5, 2),
                        'yield_unit'           => 'quintal',
                        'is_primary_crop'      => ($c === 0),
                        'status'               => $statuses[$c % count($statuses)],
                        'created_at'           => $now,
                        'updated_at'           => $now,
                    ];
                }
            }
            foreach (array_chunk($farmerCropRows, 50) as $chunk) {
                DB::table('farmer_crops')->insert($chunk);
            }

            // ─── 10 DISTRIBUTORS ─────────────────────────────────
            $distDefs = [
                ['code' => 'DIST-001', 'name' => 'Agri Trade Enterprises',   'contact' => 'Rajan Mehta',     'mobile' => '9812340001', 'type' => 'super_stockist', 'credit' => 5000000],
                ['code' => 'DIST-002', 'name' => 'Maharashtra Seeds Co.',     'contact' => 'Nitin Kulkarni',  'mobile' => '9812340002', 'type' => 'distributor',    'credit' => 3000000],
                ['code' => 'DIST-003', 'name' => 'Gujarat Agri Supplies',     'contact' => 'Ashwin Patel',    'mobile' => '9812340003', 'type' => 'distributor',    'credit' => 2500000],
                ['code' => 'DIST-004', 'name' => 'Rajasthan Krishi Bhandar',  'contact' => 'Devendra Singh',  'mobile' => '9812340004', 'type' => 'distributor',    'credit' => 2000000],
                ['code' => 'DIST-005', 'name' => 'MP Agro Traders',           'contact' => 'Rahul Mishra',    'mobile' => '9812340005', 'type' => 'sub_distributor','credit' => 1500000],
                ['code' => 'DIST-006', 'name' => 'Karnataka Seeds Depot',     'contact' => 'Manjunath B',     'mobile' => '9812340006', 'type' => 'distributor',    'credit' => 3500000],
                ['code' => 'DIST-007', 'name' => 'Dharwad Agri Hub',          'contact' => 'Basavaraj S',     'mobile' => '9812340007', 'type' => 'sub_distributor','credit' => 1200000],
                ['code' => 'DIST-008', 'name' => 'Telangana Crop Center',     'contact' => 'Srinivas K',      'mobile' => '9812340008', 'type' => 'distributor',    'credit' => 2800000],
                ['code' => 'DIST-009', 'name' => 'AP Farm Solutions',          'contact' => 'Narasimha R',     'mobile' => '9812340009', 'type' => 'distributor',    'credit' => 2200000],
                ['code' => 'DIST-010', 'name' => 'National Agri Logistics',   'contact' => 'Vinod Kumar',     'mobile' => '9812340010', 'type' => 'super_stockist', 'credit' => 8000000],
            ];

            $distIds = [];
            foreach ($distDefs as $di => $d) {
                $vIdx = $di % count($villageArr);
                $hqIdx = $di % count($hqs);
                $id = DB::table('distributors')->insertGetId([
                    'distributor_code'  => $d['code'],
                    'name'              => $d['name'],
                    'contact_person'    => $d['contact'],
                    'mobile'            => $d['mobile'],
                    'email'             => strtolower(str_replace(' ', '.', $d['contact'])) . '@dist.fieldops.in',
                    'type'              => $d['type'],
                    'address'           => $d['name'] . ', Industrial Area',
                    'village_id'        => $villageArr[$vIdx]->id,
                    'pincode'           => $villageArr[$vIdx]->pincode,
                    'latitude'          => $villageArr[$vIdx]->latitude + 0.01,
                    'longitude'         => $villageArr[$vIdx]->longitude + 0.01,
                    'gst_number'        => '27AABCD' . str_pad($di + 1, 4, '0', STR_PAD_LEFT) . 'E1Z' . ($di + 1),
                    'pan_number'        => 'AABCD' . str_pad($di + 1, 4, '0', STR_PAD_LEFT) . 'E',
                    'zrth_hierarchy_id' => $hqs[$hqIdx],
                    'credit_limit'      => $d['credit'],
                    'credit_days'       => 30 + ($di * 5),
                    'status'            => 'active',
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]);
                $distIds[] = $id;
            }

            // ─── 20 RETAILERS ────────────────────────────────────
            $retailerDefs = [
                ['shop' => 'Sri Krishna Agro Traders',       'owner' => 'Krishna Murthy',       'btype' => 'proprietary', 'grade' => 'A', 'annual' => 3500000],
                ['shop' => 'Shankar Seeds & Fertilizers',    'owner' => 'Ravi Shankar',         'btype' => 'partnership', 'grade' => 'A', 'annual' => 2800000],
                ['shop' => 'Patil Agri Inputs',              'owner' => 'Prasad Patil',         'btype' => 'proprietary', 'grade' => 'B', 'annual' => 1500000],
                ['shop' => 'Laxman Krishi Kendra',           'owner' => 'Laxman Rao',           'btype' => 'proprietary', 'grade' => 'B', 'annual' => 1200000],
                ['shop' => 'Ganesh Farm Solutions',          'owner' => 'Ganesh Kumar',         'btype' => 'llp',         'grade' => 'A', 'annual' => 5000000],
                ['shop' => 'Gujarat Khetiwadi Dukaan',       'owner' => 'Jayesh Patel',         'btype' => 'proprietary', 'grade' => 'B', 'annual' => 1800000],
                ['shop' => 'Rajasthan Beej Bhandar',         'owner' => 'Mohan Lal Sharma',     'btype' => 'proprietary', 'grade' => 'C', 'annual' => 900000],
                ['shop' => 'Kisan Seva Kendra',              'owner' => 'Ram Prasad Yadav',     'btype' => 'proprietary', 'grade' => 'B', 'annual' => 1400000],
                ['shop' => 'Sehore Agri Store',              'owner' => 'Deepak Tiwari',        'btype' => 'proprietary', 'grade' => 'C', 'annual' => 750000],
                ['shop' => 'MP Krishi Bhandar',              'owner' => 'Suresh Lodhi',         'btype' => 'partnership', 'grade' => 'B', 'annual' => 1600000],
                ['shop' => 'Hubli Seeds Center',             'owner' => 'Basavaraj Patil',      'btype' => 'proprietary', 'grade' => 'A', 'annual' => 4200000],
                ['shop' => 'Navalgund Agro Point',           'owner' => 'Shivakumar Gowda',     'btype' => 'proprietary', 'grade' => 'C', 'annual' => 850000],
                ['shop' => 'Dharwad Krushi Santhe',          'owner' => 'Raghavendra Hegde',    'btype' => 'proprietary', 'grade' => 'B', 'annual' => 2000000],
                ['shop' => 'Bagalkot Beeja Mahal',           'owner' => 'Iranna Hosamani',      'btype' => 'company',     'grade' => 'A', 'annual' => 3800000],
                ['shop' => 'Davanagere Farm Centre',         'owner' => 'Channappa H',          'btype' => 'proprietary', 'grade' => 'B', 'annual' => 1100000],
                ['shop' => 'Hyderabad Agri Mart',            'owner' => 'Venkatesh Reddy',      'btype' => 'partnership', 'grade' => 'A', 'annual' => 4500000],
                ['shop' => 'Medak Seeds Corner',             'owner' => 'Srinivas Goud',        'btype' => 'proprietary', 'grade' => 'C', 'annual' => 700000],
                ['shop' => 'Guntur Agro Center',             'owner' => 'Narasimha Rao',        'btype' => 'proprietary', 'grade' => 'B', 'annual' => 2100000],
                ['shop' => 'Prakasam Rythu Bazaar',          'owner' => 'Balakrishna Naidu',    'btype' => 'cooperative', 'grade' => 'B', 'annual' => 1900000],
                ['shop' => 'AP Seeds & Pesticides',          'owner' => 'Ramakrishna Prasad',   'btype' => 'proprietary', 'grade' => 'C', 'annual' => 650000],
            ];

            $retailerIds = [];
            foreach ($retailerDefs as $ri => $r) {
                $vIdx = $ri % count($villageArr);
                $village = $villageArr[$vIdx];
                $bIdx = $ri % count($beatArr);
                $beat = $beatArr[$bIdx];
                $hqIdx = $ri % count($hqs);
                $faIdx = $ri % count($faUsers);

                $retailerId = DB::table('retailers')->insertGetId([
                    'uuid'                     => Str::uuid()->toString(),
                    'retailer_code'            => 'RTL-' . str_pad($ri + 1, 3, '0', STR_PAD_LEFT),
                    'shop_name'                => $r['shop'],
                    'owner_name'               => $r['owner'],
                    'contact_person'           => $r['owner'],
                    'mobile'                   => '98' . str_pad(76540000 + $ri + 1, 8, '0', STR_PAD_LEFT),
                    'alternate_mobile'         => null,
                    'whatsapp_number'          => null,
                    'email'                    => strtolower(str_replace([' ', '&'], ['.', ''], $r['owner'])) . '@shop.in',
                    'business_type'            => $r['btype'],
                    'firm_name'                => ($r['btype'] !== 'proprietary') ? $r['shop'] : null,
                    'address'                  => $r['shop'] . ', Main Market, ' . $village->name,
                    'village_id'               => $village->id,
                    'pincode'                  => $village->pincode,
                    'latitude'                 => $village->latitude + 0.002,
                    'longitude'                => $village->longitude + 0.002,
                    'landmark'                 => 'Near ' . $village->name . ' Bus Stand',
                    'zrth_hierarchy_id'        => $hqs[$hqIdx],
                    'beat_id'                  => $beat->id,
                    'gst_number'               => ($ri % 3 === 0) ? '27AABCR' . str_pad($ri + 1, 4, '0', STR_PAD_LEFT) . 'A1Z' . ($ri % 10) : null,
                    'pan_number'               => ($ri % 2 === 0) ? 'AABCR' . str_pad($ri + 1, 4, '0', STR_PAD_LEFT) . 'A' : null,
                    'annual_business_value'    => $r['annual'],
                    'monthly_potential'        => round($r['annual'] / 12, 2),
                    'retailer_grade'           => $r['grade'],
                    'product_categories_dealt' => json_encode(['seed', 'pesticide']),
                    'kyc_status'               => ($ri % 3 === 0) ? 'verified' : 'pending',
                    'registered_by'            => $faUsers[$faIdx],
                    'status'                   => 'active',
                    'sync_status'              => 'synced',
                    'synced_at'                => $now,
                    'created_at'               => $now,
                    'updated_at'               => $now,
                ]);

                $retailerIds[] = $retailerId;

                // ── beat_retailers link ──
                DB::table('beat_retailers')->insert([
                    'beat_id'     => $beat->id,
                    'retailer_id' => $retailerId,
                    'is_primary'  => ($ri % 4 === 0),
                    'status'      => 'active',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);

                // ── retailer_distributors link (1-2 per retailer) ──
                $primaryDistIdx = $ri % count($distIds);
                DB::table('retailer_distributors')->insert([
                    'retailer_id'    => $retailerId,
                    'distributor_id' => $distIds[$primaryDistIdx],
                    'is_primary'     => true,
                    'credit_limit'   => round($r['annual'] * 0.1, 2),
                    'credit_days'    => 30,
                    'status'         => 'active',
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ]);
            }

            // ─── FARMER-RETAILER links ──────────────────────────
            $frLinks = [];
            foreach ($farmerIds as $fi => $fId) {
                $rIdx = $fi % count($retailerIds);
                $frLinks[] = [
                    'farmer_id'             => $fId,
                    'retailer_id'           => $retailerIds[$rIdx],
                    'is_primary'            => true,
                    'annual_purchase_value' => round(5000 + ($fi * 500), 2),
                    'created_at'            => $now,
                    'updated_at'            => $now,
                ];
            }
            foreach (array_chunk($frLinks, 50) as $chunk) {
                DB::table('farmer_retailers')->insert($chunk);
            }

            // ── Update beat totals ──
            $beatFarmerCounts   = DB::table('beat_farmers')->selectRaw('beat_id, COUNT(*) as cnt')->groupBy('beat_id')->pluck('cnt', 'beat_id');
            $beatRetailerCounts = DB::table('beat_retailers')->selectRaw('beat_id, COUNT(*) as cnt')->groupBy('beat_id')->pluck('cnt', 'beat_id');

            foreach ($beatFarmerCounts as $beatId => $cnt) {
                DB::table('beats')->where('id', $beatId)->update(['total_farmers' => $cnt]);
            }
            foreach ($beatRetailerCounts as $beatId => $cnt) {
                DB::table('beats')->where('id', $beatId)->update(['total_retailers' => $cnt]);
            }
        });

        $this->command->info('FarmerRetailerSeeder: 50 farmers, 20 retailers, 10 distributors created with associations.');
    }
}
