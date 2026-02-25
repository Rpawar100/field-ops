<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SdtvSeeder extends Seeder
{
    /**
     * Seed SDTV hierarchy: States > Districts > Talukas > Villages
     * and sdtv_zrth_mappings linking villages to HQs.
     * Uses real Indian geography names.
     */
    public function run(): void
    {
        if (DB::table('states')->where('code', 'MH')->exists()) {
            $this->command->info('SdtvSeeder: Data already exists, skipping.');
            return;
        }

        $now = Carbon::now();

        DB::transaction(function () use ($now) {

            // ─── Full SDTV data keyed by State ────────────────────
            $sdtvData = [
                'MH' => [
                    'name' => 'Maharashtra',
                    'districts' => [
                        'MH-PUNE' => [
                            'name' => 'Pune',
                            'talukas' => [
                                'MH-PUNE-BRMTI' => [
                                    'name' => 'Baramati',
                                    'villages' => [
                                        ['code' => 'MH-BRMTI-KTJ', 'name' => 'Katewadi', 'pincode' => '413102', 'lat' => 18.1500, 'lng' => 74.5800],
                                        ['code' => 'MH-BRMTI-SPA', 'name' => 'Supane', 'pincode' => '413102', 'lat' => 18.1600, 'lng' => 74.5700],
                                        ['code' => 'MH-BRMTI-MAL', 'name' => 'Malinagar', 'pincode' => '413115', 'lat' => 18.1400, 'lng' => 74.6000],
                                    ],
                                ],
                                'MH-PUNE-INDPR' => [
                                    'name' => 'Indapur',
                                    'villages' => [
                                        ['code' => 'MH-INDPR-NIM', 'name' => 'Nimgaon Ketki', 'pincode' => '413106', 'lat' => 18.1100, 'lng' => 75.0200],
                                        ['code' => 'MH-INDPR-BHG', 'name' => 'Bhigwan', 'pincode' => '413130', 'lat' => 18.2900, 'lng' => 74.7600],
                                        ['code' => 'MH-INDPR-LKD', 'name' => 'Lakdi', 'pincode' => '413106', 'lat' => 18.1200, 'lng' => 75.0400],
                                    ],
                                ],
                            ],
                        ],
                        'MH-AURA' => [
                            'name' => 'Aurangabad',
                            'talukas' => [
                                'MH-AURA-JLN' => [
                                    'name' => 'Jalna',
                                    'villages' => [
                                        ['code' => 'MH-JLN-AMB', 'name' => 'Ambad', 'pincode' => '431204', 'lat' => 19.6100, 'lng' => 75.9500],
                                        ['code' => 'MH-JLN-BDN', 'name' => 'Badnapur', 'pincode' => '431202', 'lat' => 19.8700, 'lng' => 75.7300],
                                        ['code' => 'MH-JLN-PRL', 'name' => 'Partur', 'pincode' => '431501', 'lat' => 19.5900, 'lng' => 76.2200],
                                    ],
                                ],
                                'MH-AURA-PRB' => [
                                    'name' => 'Parbhani',
                                    'villages' => [
                                        ['code' => 'MH-PRB-GAG', 'name' => 'Gangakhed', 'pincode' => '431514', 'lat' => 18.9700, 'lng' => 76.7400],
                                        ['code' => 'MH-PRB-PLP', 'name' => 'Palam', 'pincode' => '431720', 'lat' => 19.2700, 'lng' => 76.7800],
                                        ['code' => 'MH-PRB-SNL', 'name' => 'Sonpeth', 'pincode' => '431516', 'lat' => 18.8700, 'lng' => 76.6600],
                                    ],
                                ],
                            ],
                        ],
                        'MH-SOLAPUR' => [
                            'name' => 'Solapur',
                            'talukas' => [
                                'MH-SLP-PND' => [
                                    'name' => 'Pandharpur',
                                    'villages' => [
                                        ['code' => 'MH-PND-MNG', 'name' => 'Mangalvedha', 'pincode' => '413305', 'lat' => 17.5200, 'lng' => 75.4600],
                                        ['code' => 'MH-PND-TKR', 'name' => 'Takarwan', 'pincode' => '413304', 'lat' => 17.6800, 'lng' => 75.3200],
                                        ['code' => 'MH-PND-WDG', 'name' => 'Wadgaon', 'pincode' => '413304', 'lat' => 17.7000, 'lng' => 75.3400],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'GJ' => [
                    'name' => 'Gujarat',
                    'districts' => [
                        'GJ-AHMD' => [
                            'name' => 'Ahmedabad',
                            'talukas' => [
                                'GJ-AHMD-SNR' => [
                                    'name' => 'Sanand',
                                    'villages' => [
                                        ['code' => 'GJ-SNR-GDB', 'name' => 'Godhavi', 'pincode' => '382110', 'lat' => 22.9900, 'lng' => 72.3700],
                                        ['code' => 'GJ-SNR-KOB', 'name' => 'Koba', 'pincode' => '382426', 'lat' => 23.1200, 'lng' => 72.6500],
                                        ['code' => 'GJ-SNR-JTL', 'name' => 'Jetalpur', 'pincode' => '382427', 'lat' => 22.9600, 'lng' => 72.6800],
                                    ],
                                ],
                                'GJ-AHMD-DHK' => [
                                    'name' => 'Dholka',
                                    'villages' => [
                                        ['code' => 'GJ-DHK-BVL', 'name' => 'Bavla', 'pincode' => '382220', 'lat' => 22.8300, 'lng' => 72.3700],
                                        ['code' => 'GJ-DHK-DND', 'name' => 'Dhandhuka', 'pincode' => '382460', 'lat' => 22.3800, 'lng' => 71.9800],
                                        ['code' => 'GJ-DHK-LIM', 'name' => 'Limbdi', 'pincode' => '382230', 'lat' => 22.7100, 'lng' => 72.2300],
                                    ],
                                ],
                            ],
                        ],
                        'GJ-RAJK' => [
                            'name' => 'Rajkot',
                            'talukas' => [
                                'GJ-RAJK-GDL' => [
                                    'name' => 'Gondal',
                                    'villages' => [
                                        ['code' => 'GJ-GDL-JTR', 'name' => 'Jetpur', 'pincode' => '360370', 'lat' => 21.7500, 'lng' => 70.6200],
                                        ['code' => 'GJ-GDL-DRK', 'name' => 'Dhoraji', 'pincode' => '360410', 'lat' => 21.7300, 'lng' => 70.4500],
                                        ['code' => 'GJ-GDL-UPT', 'name' => 'Upleta', 'pincode' => '360490', 'lat' => 21.7400, 'lng' => 70.2800],
                                    ],
                                ],
                            ],
                        ],
                        'GJ-SURAT' => [
                            'name' => 'Surat',
                            'talukas' => [
                                'GJ-SURT-BRD' => [
                                    'name' => 'Bardoli',
                                    'villages' => [
                                        ['code' => 'GJ-BRD-VLT', 'name' => 'Valod', 'pincode' => '394640', 'lat' => 21.0900, 'lng' => 73.2300],
                                        ['code' => 'GJ-BRD-VYR', 'name' => 'Vyara', 'pincode' => '394650', 'lat' => 21.1100, 'lng' => 73.3900],
                                        ['code' => 'GJ-BRD-MND', 'name' => 'Mandvi', 'pincode' => '394160', 'lat' => 21.2500, 'lng' => 73.3000],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'RJ' => [
                    'name' => 'Rajasthan',
                    'districts' => [
                        'RJ-SIKR' => [
                            'name' => 'Sikar',
                            'talukas' => [
                                'RJ-SIKR-LKS' => [
                                    'name' => 'Laxmangarh',
                                    'villages' => [
                                        ['code' => 'RJ-LKS-MHN', 'name' => 'Mohanpura', 'pincode' => '332311', 'lat' => 27.8100, 'lng' => 75.0400],
                                        ['code' => 'RJ-LKS-GND', 'name' => 'Ganeshwar', 'pincode' => '332312', 'lat' => 27.8400, 'lng' => 75.1100],
                                        ['code' => 'RJ-LKS-KHL', 'name' => 'Khalsa', 'pincode' => '332312', 'lat' => 27.8600, 'lng' => 75.0700],
                                    ],
                                ],
                                'RJ-SIKR-NMG' => [
                                    'name' => 'Neem Ka Thana',
                                    'villages' => [
                                        ['code' => 'RJ-NMG-SRD', 'name' => 'Srimadhopur', 'pincode' => '332715', 'lat' => 27.4700, 'lng' => 75.3400],
                                        ['code' => 'RJ-NMG-KHT', 'name' => 'Khandela', 'pincode' => '332709', 'lat' => 27.5900, 'lng' => 75.5000],
                                        ['code' => 'RJ-NMG-PRJ', 'name' => 'Patan', 'pincode' => '332713', 'lat' => 27.4300, 'lng' => 75.2800],
                                    ],
                                ],
                            ],
                        ],
                        'RJ-ALWR' => [
                            'name' => 'Alwar',
                            'talukas' => [
                                'RJ-ALWR-BSS' => [
                                    'name' => 'Behror',
                                    'villages' => [
                                        ['code' => 'RJ-BSS-NEE', 'name' => 'Neemrana', 'pincode' => '301705', 'lat' => 27.9900, 'lng' => 76.3800],
                                        ['code' => 'RJ-BSS-KSG', 'name' => 'Kesroli', 'pincode' => '301701', 'lat' => 27.5600, 'lng' => 76.6200],
                                        ['code' => 'RJ-BSS-TJR', 'name' => 'Tijara', 'pincode' => '301411', 'lat' => 27.9300, 'lng' => 76.8500],
                                    ],
                                ],
                            ],
                        ],
                        'RJ-JAIPR' => [
                            'name' => 'Jaipur',
                            'talukas' => [
                                'RJ-JPR-SKR' => [
                                    'name' => 'Sanganer',
                                    'villages' => [
                                        ['code' => 'RJ-SKR-BGR', 'name' => 'Bagru', 'pincode' => '303007', 'lat' => 26.8000, 'lng' => 75.5600],
                                        ['code' => 'RJ-SKR-CPR', 'name' => 'Chomu', 'pincode' => '303702', 'lat' => 27.1700, 'lng' => 75.7200],
                                        ['code' => 'RJ-SKR-PHP', 'name' => 'Phagi', 'pincode' => '303005', 'lat' => 26.5800, 'lng' => 75.5200],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'MP' => [
                    'name' => 'Madhya Pradesh',
                    'districts' => [
                        'MP-SEHR' => [
                            'name' => 'Sehore',
                            'talukas' => [
                                'MP-SEHR-AST' => [
                                    'name' => 'Ashta',
                                    'villages' => [
                                        ['code' => 'MP-AST-ICH', 'name' => 'Ichhawar', 'pincode' => '466115', 'lat' => 23.0100, 'lng' => 77.0900],
                                        ['code' => 'MP-AST-NSD', 'name' => 'Nasrullaganj', 'pincode' => '466331', 'lat' => 22.6400, 'lng' => 77.2700],
                                        ['code' => 'MP-AST-RHT', 'name' => 'Rehti', 'pincode' => '466446', 'lat' => 22.7400, 'lng' => 77.1600],
                                    ],
                                ],
                                'MP-SEHR-BPL' => [
                                    'name' => 'Budni',
                                    'villages' => [
                                        ['code' => 'MP-BPL-HSD', 'name' => 'Hoshangabad Road', 'pincode' => '461001', 'lat' => 23.1800, 'lng' => 77.4000],
                                        ['code' => 'MP-BPL-MND', 'name' => 'Mandideep', 'pincode' => '462046', 'lat' => 23.1000, 'lng' => 77.5100],
                                        ['code' => 'MP-BPL-OBD', 'name' => 'Obedullaganj', 'pincode' => '461110', 'lat' => 23.4000, 'lng' => 77.5900],
                                    ],
                                ],
                            ],
                        ],
                        'MP-VIDS' => [
                            'name' => 'Vidisha',
                            'talukas' => [
                                'MP-VIDS-GJN' => [
                                    'name' => 'Ganjbasoda',
                                    'villages' => [
                                        ['code' => 'MP-GJN-LAT', 'name' => 'Lateri', 'pincode' => '464770', 'lat' => 23.7900, 'lng' => 77.9600],
                                        ['code' => 'MP-GJN-NTV', 'name' => 'Nateran', 'pincode' => '464221', 'lat' => 23.8400, 'lng' => 77.4300],
                                        ['code' => 'MP-GJN-SRN', 'name' => 'Sironj', 'pincode' => '464228', 'lat' => 24.1000, 'lng' => 77.6900],
                                    ],
                                ],
                            ],
                        ],
                        'MP-BHOPL' => [
                            'name' => 'Bhopal',
                            'talukas' => [
                                'MP-BPL-HSG' => [
                                    'name' => 'Huzur',
                                    'villages' => [
                                        ['code' => 'MP-HSG-RTP', 'name' => 'Ratibad', 'pincode' => '462044', 'lat' => 23.1100, 'lng' => 77.3200],
                                        ['code' => 'MP-HSG-MIS', 'name' => 'Misrod', 'pincode' => '462026', 'lat' => 23.1700, 'lng' => 77.4800],
                                        ['code' => 'MP-HSG-BRL', 'name' => 'Bairagarh', 'pincode' => '462030', 'lat' => 23.2700, 'lng' => 77.3600],
                                        ['code' => 'MP-HSG-KLR', 'name' => 'Kolar', 'pincode' => '462042', 'lat' => 23.1500, 'lng' => 77.4200],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'KA' => [
                    'name' => 'Karnataka',
                    'districts' => [
                        'KA-BAGK' => [
                            'name' => 'Bagalkot',
                            'talukas' => [
                                'KA-BAGK-JMK' => [
                                    'name' => 'Jamkhandi',
                                    'villages' => [
                                        ['code' => 'KA-JMK-TRV', 'name' => 'Tervad', 'pincode' => '587301', 'lat' => 16.5100, 'lng' => 75.2900],
                                        ['code' => 'KA-JMK-HPN', 'name' => 'Hippargi', 'pincode' => '587312', 'lat' => 16.4700, 'lng' => 75.3600],
                                        ['code' => 'KA-JMK-BDM', 'name' => 'Badami', 'pincode' => '587201', 'lat' => 15.9200, 'lng' => 75.6800],
                                    ],
                                ],
                                'KA-BAGK-MLK' => [
                                    'name' => 'Mudhol',
                                    'villages' => [
                                        ['code' => 'KA-MLK-LKN', 'name' => 'Lokapur', 'pincode' => '587122', 'lat' => 16.2000, 'lng' => 75.4000],
                                        ['code' => 'KA-MLK-GVR', 'name' => 'Guledgudda', 'pincode' => '587203', 'lat' => 16.0500, 'lng' => 75.7900],
                                        ['code' => 'KA-MLK-HNN', 'name' => 'Hunagund', 'pincode' => '587118', 'lat' => 16.0600, 'lng' => 75.5700],
                                    ],
                                ],
                            ],
                        ],
                        'KA-DHARW' => [
                            'name' => 'Dharwad',
                            'talukas' => [
                                'KA-DHARW-HBL' => [
                                    'name' => 'Hubli',
                                    'villages' => [
                                        ['code' => 'KA-HBL-NAV', 'name' => 'Navalgund', 'pincode' => '582208', 'lat' => 15.5500, 'lng' => 75.3800],
                                        ['code' => 'KA-HBL-KND', 'name' => 'Kundgol', 'pincode' => '580023', 'lat' => 15.2600, 'lng' => 75.2400],
                                        ['code' => 'KA-HBL-AMP', 'name' => 'Amminbhavi', 'pincode' => '580024', 'lat' => 15.4400, 'lng' => 75.1200],
                                    ],
                                ],
                            ],
                        ],
                        'KA-DVGR' => [
                            'name' => 'Davanagere',
                            'talukas' => [
                                'KA-DVGR-HRP' => [
                                    'name' => 'Harihar',
                                    'villages' => [
                                        ['code' => 'KA-HRP-JGR', 'name' => 'Jagalur', 'pincode' => '577528', 'lat' => 14.5200, 'lng' => 76.3400],
                                        ['code' => 'KA-HRP-HNG', 'name' => 'Honnali', 'pincode' => '577217', 'lat' => 14.2400, 'lng' => 75.6500],
                                        ['code' => 'KA-HRP-CHN', 'name' => 'Channagiri', 'pincode' => '577213', 'lat' => 14.0200, 'lng' => 75.9300],
                                    ],
                                ],
                                'KA-DVGR-HVR' => [
                                    'name' => 'Haveri',
                                    'villages' => [
                                        ['code' => 'KA-HVR-RNB', 'name' => 'Ranebennur', 'pincode' => '581115', 'lat' => 14.6200, 'lng' => 75.6300],
                                        ['code' => 'KA-HVR-SVN', 'name' => 'Savanur', 'pincode' => '581118', 'lat' => 14.9700, 'lng' => 75.3300],
                                        ['code' => 'KA-HVR-BYD', 'name' => 'Byadagi', 'pincode' => '581106', 'lat' => 14.6800, 'lng' => 75.4900],
                                        ['code' => 'KA-HVR-HGR', 'name' => 'Hangal', 'pincode' => '581104', 'lat' => 14.7700, 'lng' => 75.1200],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ];

            // ─── HQ-to-Taluka mapping for sdtv_zrth_mappings ─────
            // We map each taluka's villages to the corresponding HQ
            $talukaToHq = [
                'MH-PUNE-BRMTI' => 'HQ-BRMTI',
                'MH-PUNE-INDPR' => 'HQ-INDPR',
                'MH-AURA-JLN'   => 'HQ-JALNA',
                'MH-AURA-PRB'   => 'HQ-PARBH',
                'MH-SLP-PND'    => 'HQ-BRMTI',  // Nearby coverage
                'GJ-AHMD-SNR'   => 'HQ-BRMTI',  // North zone coverage
                'GJ-AHMD-DHK'   => 'HQ-INDPR',
                'GJ-RAJK-GDL'   => 'HQ-INDPR',
                'GJ-SURT-BRD'   => 'HQ-JALNA',
                'RJ-SIKR-LKS'   => 'HQ-SIKAR',
                'RJ-SIKR-NMG'   => 'HQ-SIKAR',
                'RJ-ALWR-BSS'   => 'HQ-ALWAR',
                'RJ-JPR-SKR'    => 'HQ-ALWAR',
                'MP-SEHR-AST'   => 'HQ-SEHRE',
                'MP-SEHR-BPL'   => 'HQ-SEHRE',
                'MP-VIDS-GJN'   => 'HQ-VIDSH',
                'MP-BPL-HSG'    => 'HQ-VIDSH',
                'KA-BAGK-JMK'   => 'HQ-BAGAL',
                'KA-BAGK-MLK'   => 'HQ-BAGAL',
                'KA-DHARW-HBL'  => 'HQ-DHARW',
                'KA-DVGR-HRP'   => 'HQ-DVNGR',
                'KA-DVGR-HVR'   => 'HQ-HAVRI',
            ];

            // Look up HQ ids by code
            $hqIds = DB::table('zrth_hierarchies')
                ->where('level', 'hq')
                ->pluck('id', 'code')
                ->toArray();

            // Insert SDTV + mappings
            foreach ($sdtvData as $stateCode => $stateInfo) {
                $stateId = DB::table('states')->insertGetId([
                    'code'       => $stateCode,
                    'name'       => $stateInfo['name'],
                    'status'     => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                foreach ($stateInfo['districts'] as $distCode => $distInfo) {
                    $distId = DB::table('districts')->insertGetId([
                        'code'       => $distCode,
                        'name'       => $distInfo['name'],
                        'state_id'   => $stateId,
                        'status'     => 'active',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    foreach ($distInfo['talukas'] as $talCode => $talInfo) {
                        $talId = DB::table('talukas')->insertGetId([
                            'code'        => $talCode,
                            'name'        => $talInfo['name'],
                            'district_id' => $distId,
                            'status'      => 'active',
                            'created_at'  => $now,
                            'updated_at'  => $now,
                        ]);

                        foreach ($talInfo['villages'] as $v) {
                            $villageId = DB::table('villages')->insertGetId([
                                'code'       => $v['code'],
                                'name'       => $v['name'],
                                'taluka_id'  => $talId,
                                'pincode'    => $v['pincode'],
                                'latitude'   => $v['lat'],
                                'longitude'  => $v['lng'],
                                'status'     => 'active',
                                'created_at' => $now,
                                'updated_at' => $now,
                            ]);

                            // Create SDTV-ZRTH mapping
                            $hqCode = $talukaToHq[$talCode] ?? null;
                            if ($hqCode && isset($hqIds[$hqCode])) {
                                DB::table('sdtv_zrth_mappings')->insert([
                                    'village_id'        => $villageId,
                                    'zrth_hierarchy_id' => $hqIds[$hqCode],
                                    'status'            => 'active',
                                    'created_at'        => $now,
                                    'updated_at'        => $now,
                                ]);
                            }
                        }
                    }
                }
            }
        });

        $counts = [
            'states'   => DB::table('states')->count(),
            'districts' => DB::table('districts')->count(),
            'talukas'  => DB::table('talukas')->count(),
            'villages' => DB::table('villages')->count(),
            'mappings' => DB::table('sdtv_zrth_mappings')->count(),
        ];

        $this->command->info("SdtvSeeder: {$counts['states']} States, {$counts['districts']} Districts, {$counts['talukas']} Talukas, {$counts['villages']} Villages, {$counts['mappings']} ZRTH mappings.");
    }
}
