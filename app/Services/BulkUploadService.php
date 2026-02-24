<?php

namespace App\Services;

use App\Models\Farmer;
use App\Models\Retailer;
use App\Models\User;
use App\Models\Beat;
use App\Models\SdtvNode;
use App\Models\ZrthNode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;

class BulkUploadService
{
    /**
     * Process farmer bulk upload
     *
     * @param array $rows
     * @param int $uploadedBy
     * @return array
     */
    public function uploadFarmers(array $rows, int $uploadedBy): array
    {
        $results = [
            'total' => count($rows),
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 for header row and 0-index

            try {
                $validator = Validator::make($row, [
                    'name' => 'required|string|max:255',
                    'phone' => 'required|string|max:15|unique:farmers,phone',
                    'village_code' => 'required|exists:sdtv_nodes,code',
                    'total_land_acres' => 'nullable|numeric|min:0',
                    'crops' => 'nullable|string',
                    'farmer_type' => 'nullable|in:demo,pda,user,non_user',
                    'farmer_category' => 'nullable|in:small,influencer',
                ]);

                if ($validator->fails()) {
                    $results['failed']++;
                    $results['errors'][] = [
                        'row' => $rowNumber,
                        'data' => $row,
                        'errors' => $validator->errors()->toArray(),
                    ];
                    continue;
                }

                $sdtvNode = SdtvNode::where('code', $row['village_code'])->first();

                Farmer::create([
                    'name' => $row['name'],
                    'phone' => $row['phone'],
                    'gender' => $row['gender'] ?? 'male',
                    'sdtv_node_id' => $sdtvNode->id,
                    'total_land_acres' => $row['total_land_acres'] ?? null,
                    'crops' => isset($row['crops']) ? array_map('trim', explode(',', $row['crops'])) : [],
                    'farmer_type' => $row['farmer_type'] ?? 'user',
                    'farmer_category' => $row['farmer_category'] ?? 'small',
                    'irrigation_type' => $row['irrigation_type'] ?? null,
                    'address' => $row['address'] ?? null,
                    'registered_by' => $uploadedBy,
                    'status' => 'active',
                ]);

                $results['success']++;
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = [
                    'row' => $rowNumber,
                    'data' => $row,
                    'errors' => ['exception' => $e->getMessage()],
                ];
                Log::error('Farmer bulk upload error', [
                    'row' => $rowNumber,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $results;
    }

    /**
     * Process retailer bulk upload
     *
     * @param array $rows
     * @param int $uploadedBy
     * @return array
     */
    public function uploadRetailers(array $rows, int $uploadedBy): array
    {
        $results = [
            'total' => count($rows),
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            try {
                $validator = Validator::make($row, [
                    'name' => 'required|string|max:255',
                    'shop_name' => 'required|string|max:255',
                    'phone' => 'required|string|max:15|unique:retailers,phone',
                    'village_code' => 'required|exists:sdtv_nodes,code',
                    'gst_number' => 'nullable|string|unique:retailers,gst_number',
                    'pan_number' => 'nullable|string|unique:retailers,pan_number',
                ]);

                if ($validator->fails()) {
                    $results['failed']++;
                    $results['errors'][] = [
                        'row' => $rowNumber,
                        'data' => $row,
                        'errors' => $validator->errors()->toArray(),
                    ];
                    continue;
                }

                $sdtvNode = SdtvNode::where('code', $row['village_code'])->first();

                Retailer::create([
                    'name' => $row['name'],
                    'shop_name' => $row['shop_name'],
                    'phone' => $row['phone'],
                    'sdtv_node_id' => $sdtvNode->id,
                    'business_type' => $row['business_type'] ?? 'proprietary',
                    'outlet_type' => $row['outlet_type'] ?? 'agro_dealer',
                    'annual_business_value' => $row['annual_business_value'] ?? null,
                    'gst_number' => $row['gst_number'] ?? null,
                    'pan_number' => $row['pan_number'] ?? null,
                    'address' => $row['address'] ?? null,
                    'registered_by' => $uploadedBy,
                    'status' => 'active',
                ]);

                $results['success']++;
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = [
                    'row' => $rowNumber,
                    'data' => $row,
                    'errors' => ['exception' => $e->getMessage()],
                ];
                Log::error('Retailer bulk upload error', [
                    'row' => $rowNumber,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $results;
    }

    /**
     * Process SDTV hierarchy bulk upload
     *
     * @param array $rows
     * @return array
     */
    public function uploadSdtvNodes(array $rows): array
    {
        $results = [
            'total' => count($rows),
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        // Process in order: states, districts, talukas, villages
        $levels = ['state', 'district', 'taluka', 'village'];

        foreach ($levels as $level) {
            $levelRows = array_filter($rows, fn($row) => ($row['level'] ?? '') === $level);

            foreach ($levelRows as $index => $row) {
                $rowNumber = $index + 2;

                try {
                    $validator = Validator::make($row, [
                        'code' => 'required|string|max:50|unique:sdtv_nodes,code',
                        'name' => 'required|string|max:255',
                        'level' => 'required|in:state,district,taluka,village',
                        'parent_code' => 'nullable|exists:sdtv_nodes,code',
                    ]);

                    if ($validator->fails()) {
                        $results['failed']++;
                        $results['errors'][] = [
                            'row' => $rowNumber,
                            'data' => $row,
                            'errors' => $validator->errors()->toArray(),
                        ];
                        continue;
                    }

                    $parentId = null;
                    if (!empty($row['parent_code'])) {
                        $parent = SdtvNode::where('code', $row['parent_code'])->first();
                        $parentId = $parent?->id;
                    }

                    SdtvNode::create([
                        'code' => $row['code'],
                        'name' => $row['name'],
                        'level' => $row['level'],
                        'parent_id' => $parentId,
                        'status' => 'active',
                    ]);

                    $results['success']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = [
                        'row' => $rowNumber,
                        'data' => $row,
                        'errors' => ['exception' => $e->getMessage()],
                    ];
                }
            }
        }

        return $results;
    }

    /**
     * Process ZRTH hierarchy bulk upload
     *
     * @param array $rows
     * @return array
     */
    public function uploadZrthNodes(array $rows): array
    {
        $results = [
            'total' => count($rows),
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        // Process in order: zones, regions, territories, headquarters
        $levels = ['zone', 'region', 'territory', 'headquarter'];

        foreach ($levels as $level) {
            $levelRows = array_filter($rows, fn($row) => ($row['level'] ?? '') === $level);

            foreach ($levelRows as $index => $row) {
                $rowNumber = $index + 2;

                try {
                    $validator = Validator::make($row, [
                        'code' => 'required|string|max:50|unique:zrth_nodes,code',
                        'name' => 'required|string|max:255',
                        'level' => 'required|in:zone,region,territory,headquarter',
                        'parent_code' => 'nullable|exists:zrth_nodes,code',
                    ]);

                    if ($validator->fails()) {
                        $results['failed']++;
                        $results['errors'][] = [
                            'row' => $rowNumber,
                            'data' => $row,
                            'errors' => $validator->errors()->toArray(),
                        ];
                        continue;
                    }

                    $parentId = null;
                    if (!empty($row['parent_code'])) {
                        $parent = ZrthNode::where('code', $row['parent_code'])->first();
                        $parentId = $parent?->id;
                    }

                    ZrthNode::create([
                        'code' => $row['code'],
                        'name' => $row['name'],
                        'level' => $row['level'],
                        'parent_id' => $parentId,
                        'status' => 'active',
                    ]);

                    $results['success']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = [
                        'row' => $rowNumber,
                        'data' => $row,
                        'errors' => ['exception' => $e->getMessage()],
                    ];
                }
            }
        }

        return $results;
    }

    /**
     * Parse CSV file to array
     *
     * @param UploadedFile $file
     * @return array
     */
    public function parseCsv(UploadedFile $file): array
    {
        $rows = [];
        $handle = fopen($file->getRealPath(), 'r');

        $headers = fgetcsv($handle);
        $headers = array_map('trim', $headers);
        $headers = array_map('strtolower', $headers);
        $headers = array_map(fn($h) => str_replace(' ', '_', $h), $headers);

        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) === count($headers)) {
                $rows[] = array_combine($headers, array_map('trim', $data));
            }
        }

        fclose($handle);

        return $rows;
    }

    /**
     * Generate sample CSV template
     *
     * @param string $type
     * @return string
     */
    public function getTemplate(string $type): string
    {
        $templates = [
            'farmers' => "name,phone,gender,village_code,total_land_acres,crops,farmer_type,farmer_category,irrigation_type,address\nRamaiah,9876500001,male,VLG1,5.5,\"Corn,Paddy\",demo,influencer,borewell,\"Village Address\"",

            'retailers' => "name,shop_name,phone,village_code,business_type,outlet_type,annual_business_value,gst_number,pan_number,address\nKrishna,Krishna Traders,9876600001,VLG1,proprietary,agro_dealer,2500000,36AABCR1234A1Z5,AABCR1234A,\"Shop Address\"",

            'sdtv' => "code,name,level,parent_code\nTS,Telangana,state,\nRR,Rangareddy,district,TS\nIBS,Ibrahimpatnam,taluka,RR\nMCL,Manchal,village,IBS",

            'zrth' => "code,name,level,parent_code\nZONE-S,South Zone,zone,\nREG-AP,AP Region,region,ZONE-S\nTER-HYD,Hyderabad Territory,territory,REG-AP\nHQ-SEC,Secunderabad HQ,headquarter,TER-HYD",
        ];

        return $templates[$type] ?? '';
    }
}
