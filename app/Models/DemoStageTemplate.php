<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class DemoStageTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'crop_id',
        'variety_id',
        'stage_name',
        'stage_order',
        'days_after_sowing',
        'tolerance_days_before',
        'tolerance_days_after',
        'required_attributes',
        'optional_attributes',
        'photo_required',
        'min_photos',
        'max_photos',
        'instructions',
        'observation_guidelines',
        'status',
    ];

    protected $casts = [
        'stage_order' => 'integer',
        'days_after_sowing' => 'integer',
        'tolerance_days_before' => 'integer',
        'tolerance_days_after' => 'integer',
        'required_attributes' => 'json',
        'optional_attributes' => 'json',
        'photo_required' => 'boolean',
        'min_photos' => 'integer',
        'max_photos' => 'integer',
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

    public function stageActivities(): HasMany
    {
        return $this->hasMany(DemoStageActivity::class, 'stage_template_id');
    }

    public function planTemplates(): HasMany
    {
        return $this->hasMany(DemoStagePlanTemplate::class, 'stage_template_id');
    }

    // ==================== SCOPES ====================

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('stage_order');
    }
}
