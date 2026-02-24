<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class DemoStagePlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'plan_code',
        'name',
        'crop_id',
        'variety_id',
        'description',
        'total_stages',
        'total_duration_days',
        'status',
    ];

    protected $casts = [
        'total_stages' => 'integer',
        'total_duration_days' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class);
    }

    public function variety(): BelongsTo
    {
        return $this->belongsTo(Variety::class);
    }

    public function planTemplates(): HasMany
    {
        return $this->hasMany(DemoStagePlanTemplate::class, 'stage_plan_id');
    }

    public function demoMasters(): HasMany
    {
        return $this->hasMany(DemoMaster::class, 'stage_plan_id');
    }

    // ==================== SCOPES ====================

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }
}
