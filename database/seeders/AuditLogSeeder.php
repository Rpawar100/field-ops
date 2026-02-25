<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuditLogSeeder extends Seeder
{
    /**
     * Seed 20 audit_log entries for various entity changes,
     * plus app_settings and app_versions.
     */
    public function run(): void
    {
        if (DB::table('audit_logs')->exists()) {
            $this->command->info('AuditLogSeeder: Data already exists, skipping.');
            return;
        }

        $now = Carbon::now();

        DB::transaction(function () use ($now) {
            $admin   = DB::table('users')->where('role', 'nsm')->first();
            $zm      = DB::table('users')->where('role', 'zm')->first();
            $rm      = DB::table('users')->where('role', 'rm')->first();
            $tsl     = DB::table('users')->where('role', 'tsl')->first();
            $faUsers = DB::table('users')->where('role', 'fa')->limit(5)->pluck('id')->toArray();

            $users = array_filter([$admin?->id, $zm?->id, $rm?->id, $tsl?->id]);
            $users = array_merge($users, $faUsers);

            // ─── AUDIT LOGS (20 entries) ─────────────────────────
            $auditDefs = [
                ['type' => 'App\\Models\\User',      'id' => $faUsers[0] ?? 1, 'event' => 'created', 'old' => null, 'new' => ['name' => 'Rajesh Patil', 'role' => 'fa']],
                ['type' => 'App\\Models\\User',      'id' => $faUsers[0] ?? 1, 'event' => 'updated', 'old' => ['status' => 'pending'], 'new' => ['status' => 'active']],
                ['type' => 'App\\Models\\Farmer',    'id' => 1, 'event' => 'created', 'old' => null, 'new' => ['name' => 'Ramesh Patil', 'farmer_code' => 'FRM-001']],
                ['type' => 'App\\Models\\Farmer',    'id' => 2, 'event' => 'created', 'old' => null, 'new' => ['name' => 'Suresh Jadhav', 'farmer_code' => 'FRM-002']],
                ['type' => 'App\\Models\\Farmer',    'id' => 1, 'event' => 'updated', 'old' => ['verification_status' => 'pending'], 'new' => ['verification_status' => 'verified']],
                ['type' => 'App\\Models\\Retailer',  'id' => 1, 'event' => 'created', 'old' => null, 'new' => ['shop_name' => 'Sri Krishna Agro Traders']],
                ['type' => 'App\\Models\\Retailer',  'id' => 1, 'event' => 'updated', 'old' => ['kyc_status' => 'pending'], 'new' => ['kyc_status' => 'verified']],
                ['type' => 'App\\Models\\Beat',      'id' => 1, 'event' => 'created', 'old' => null, 'new' => ['beat_code' => 'BEAT-001', 'name' => 'Baramati Market Beat']],
                ['type' => 'App\\Models\\Activity',  'id' => 1, 'event' => 'created', 'old' => null, 'new' => ['status' => 'submitted']],
                ['type' => 'App\\Models\\Activity',  'id' => 1, 'event' => 'updated', 'old' => ['status' => 'submitted'], 'new' => ['status' => 'completed']],
                ['type' => 'App\\Models\\Attendance','id' => 1, 'event' => 'created', 'old' => null, 'new' => ['attendance_type' => 'individual_work']],
                ['type' => 'App\\Models\\DemoMaster','id' => 1, 'event' => 'created', 'old' => null, 'new' => ['lot_no' => 'LOT-2025-001']],
                ['type' => 'App\\Models\\DemoMaster','id' => 1, 'event' => 'updated', 'old' => ['status' => 'available'], 'new' => ['status' => 'partially_distributed']],
                ['type' => 'App\\Models\\Product',   'id' => 1, 'event' => 'created', 'old' => null, 'new' => ['sku_code' => 'SKU-S001']],
                ['type' => 'App\\Models\\Product',   'id' => 1, 'event' => 'updated', 'old' => ['mrp' => 800], 'new' => ['mrp' => 850]],
                ['type' => 'App\\Models\\ZrthHierarchy', 'id' => 1, 'event' => 'created', 'old' => null, 'new' => ['code' => 'ZN-NORTH', 'name' => 'North Zone']],
                ['type' => 'App\\Models\\LeaveRequest','id' => 1, 'event' => 'created', 'old' => null, 'new' => ['status' => 'pending']],
                ['type' => 'App\\Models\\LeaveRequest','id' => 1, 'event' => 'updated', 'old' => ['status' => 'pending'], 'new' => ['status' => 'approved']],
                ['type' => 'App\\Models\\User',      'id' => $admin?->id ?? 1, 'event' => 'updated', 'old' => ['last_login_at' => null], 'new' => ['last_login_at' => $now->toDateTimeString()]],
                ['type' => 'App\\Models\\Distributor','id' => 1, 'event' => 'created', 'old' => null, 'new' => ['distributor_code' => 'DIST-001']],
            ];

            $ipAddresses = ['192.168.1.10', '192.168.1.25', '10.0.0.5', '172.16.0.100', '192.168.0.1'];
            $userAgents  = [
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'FieldOps Mobile App/1.2.0 (Android 13)',
                'FieldOps Mobile App/1.2.0 (Android 14)',
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
            ];

            foreach ($auditDefs as $ai => $a) {
                DB::table('audit_logs')->insert([
                    'user_id'        => $users[$ai % count($users)],
                    'auditable_type' => $a['type'],
                    'auditable_id'   => $a['id'],
                    'event'          => $a['event'],
                    'old_values'     => $a['old'] ? json_encode($a['old']) : null,
                    'new_values'     => $a['new'] ? json_encode($a['new']) : null,
                    'ip_address'     => $ipAddresses[$ai % count($ipAddresses)],
                    'user_agent'     => $userAgents[$ai % count($userAgents)],
                    'url'            => '/api/v1/' . strtolower(class_basename($a['type'])) . 's/' . $a['id'],
                    'created_at'     => $now->copy()->subDays(20 - $ai),
                    'updated_at'     => $now->copy()->subDays(20 - $ai),
                ]);
            }

            // ─── APP SETTINGS ────────────────────────────────────
            $settings = [
                ['key' => 'min_daily_activities',   'value' => '3',     'group' => 'attendance', 'type' => 'integer', 'desc' => 'Minimum activities for valid attendance', 'is_public' => true],
                ['key' => 'max_backdate_days',      'value' => '2',     'group' => 'activity',   'type' => 'integer', 'desc' => 'Max days allowed for backdating activities', 'is_public' => true],
                ['key' => 'selfie_required',        'value' => 'true',  'group' => 'attendance', 'type' => 'boolean', 'desc' => 'Whether selfie is required for attendance', 'is_public' => true],
                ['key' => 'gps_accuracy_threshold', 'value' => '50',    'group' => 'gps',        'type' => 'integer', 'desc' => 'Max GPS accuracy in meters',               'is_public' => true],
                ['key' => 'company_name',           'value' => 'FieldOps Agri Pvt Ltd', 'group' => 'general', 'type' => 'string', 'desc' => 'Company name', 'is_public' => true],
                ['key' => 'support_email',          'value' => 'support@fieldops.in', 'group' => 'general', 'type' => 'string', 'desc' => 'Support email', 'is_public' => true],
                ['key' => 'auto_checkout_time',     'value' => '21:00', 'group' => 'attendance', 'type' => 'string',  'desc' => 'Auto checkout time if not checked out', 'is_public' => false],
                ['key' => 'photo_max_size_mb',      'value' => '5',     'group' => 'upload',     'type' => 'integer', 'desc' => 'Maximum photo upload size in MB', 'is_public' => true],
                ['key' => 'sync_interval_minutes',  'value' => '15',    'group' => 'sync',       'type' => 'integer', 'desc' => 'Auto sync interval in minutes', 'is_public' => true],
                ['key' => 'location_tracking_interval', 'value' => '300', 'group' => 'gps',     'type' => 'integer', 'desc' => 'Location trail interval in seconds', 'is_public' => true],
            ];

            foreach ($settings as $s) {
                DB::table('app_settings')->insert([
                    'key'         => $s['key'],
                    'value'       => $s['value'],
                    'group'       => $s['group'],
                    'type'        => $s['type'],
                    'description' => $s['desc'],
                    'is_public'   => $s['is_public'],
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            }

            // ─── APP VERSIONS ────────────────────────────────────
            $versions = [
                ['platform' => 'android', 'version' => '1.0.0', 'build' => '100', 'mandatory' => false, 'latest' => false, 'notes' => 'Initial release', 'status' => 'deprecated'],
                ['platform' => 'android', 'version' => '1.1.0', 'build' => '110', 'mandatory' => false, 'latest' => false, 'notes' => 'Bug fixes and performance improvements', 'status' => 'deprecated'],
                ['platform' => 'android', 'version' => '1.2.0', 'build' => '120', 'mandatory' => true,  'latest' => true,  'notes' => 'Added offline sync, demo module, and improved GPS tracking', 'status' => 'active'],
                ['platform' => 'ios',     'version' => '1.0.0', 'build' => '100', 'mandatory' => false, 'latest' => false, 'notes' => 'Initial iOS release', 'status' => 'deprecated'],
                ['platform' => 'ios',     'version' => '1.2.0', 'build' => '120', 'mandatory' => true,  'latest' => true,  'notes' => 'Parity with Android v1.2.0', 'status' => 'active'],
            ];

            foreach ($versions as $vi => $v) {
                DB::table('app_versions')->insert([
                    'platform'      => $v['platform'],
                    'version'       => $v['version'],
                    'build_number'  => $v['build'],
                    'is_mandatory'  => $v['mandatory'],
                    'is_latest'     => $v['latest'],
                    'release_notes' => $v['notes'],
                    'download_url'  => 'https://releases.fieldops.in/' . $v['platform'] . '/' . $v['version'],
                    'release_date'  => Carbon::parse('2025-06-01')->addMonths($vi)->format('Y-m-d'),
                    'status'        => $v['status'],
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ]);
            }
        });

        $this->command->info('AuditLogSeeder: 20 audit logs, 10 app settings, 5 app versions created.');
    }
}
