<?php

namespace App\Models\Demo;

/**
 * @deprecated DemoExecution no longer exists in the 2024 schema.
 * Use \App\Models\DemoFarmerAssignment + \App\Models\DemoStageActivity instead.
 */
class DemoExecution extends \App\Models\DemoFarmerAssignment
{
    // Backward-compatible alias — maps to demo_farmer_assignments table
}
