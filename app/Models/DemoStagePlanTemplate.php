<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemoStagePlanTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_plan_id',
        'stage_template_id',
        'stage_order',
        'is_mandatory',
    ];

    protected $casts = [
        'stage_order' => 'integer',
        'is_mandatory' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function stagePlan(): BelongsTo
    {
        return $this->belongsTo(DemoStagePlan::class, 'stage_plan_id');
    }

    public function stageTemplate(): BelongsTo
    {
        return $this->belongsTo(DemoStageTemplate::class, 'stage_template_id');
    }
}
