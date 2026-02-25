<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Execution order respects foreign key dependencies:
     *   1. ZrthHierarchySeeder  — zones/regions/territories/HQs (no FK deps)
     *   2. SdtvSeeder           — states/districts/talukas/villages + sdtv_zrth_mappings (needs ZRTH)
     *   3. UserZrthSeeder       — user_zrth_assignments + new FA users (needs users + ZRTH)
     *   4. BeatSeeder           — beats/beat_user_assignments/beat_schedules (needs ZRTH + SDTV + users)
     *   5. CropProductSeeder    — crops/brands/categories/varieties/products (no entity deps)
     *   6. FarmerRetailerSeeder — farmers/retailers/distributors + pivots (needs SDTV + beats + products)
     *   7. AttendanceSeeder     — attendances + location_trails (needs users)
     *   8. ActivitySeeder       — activity_types/masters/activities + photos/products/comments (needs all above)
     *   9. DemoSeeder           — demo_stage_templates/plans/masters/distributions/assignments/stage_activities (needs crops + farmers + users)
     *  10. HrSeeder             — leave_types/holidays/leave_requests (needs users)
     *  11. AuditLogSeeder       — audit_logs + app_settings + app_versions (needs users)
     *
     * IMPORTANT: The 5 base users (NSM, ZM, RM, TSL, FA) must already exist
     * from the DB-RESTORE agent before running these seeders.
     *
     * @return void
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('=== Field Operations Database Seeder ===');
        $this->command->info('Requires: 5 base users (NSM, ZM, RM, TSL, FA) already in DB.');
        $this->command->info('');

        $this->call([
            ZrthHierarchySeeder::class,
            SdtvSeeder::class,
            UserZrthSeeder::class,
            BeatSeeder::class,
            CropProductSeeder::class,
            FarmerRetailerSeeder::class,
            AttendanceSeeder::class,
            ActivitySeeder::class,
            DemoSeeder::class,
            HrSeeder::class,
            AuditLogSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('=== All seeders completed successfully ===');
        $this->command->info('');
    }
}
