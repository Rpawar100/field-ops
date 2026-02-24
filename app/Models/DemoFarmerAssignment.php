<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class DemoFarmerAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'demo_master_id',
        'demo_distribution_id',
        'farmer_id',
        'lot_no',
        'quantity_given',
        'quantity_unit',
        'assignment_date',
        'sowing_date',
        'expected_harvest_date',
        'plot_size_acres',
        'plot_latitude',
        'plot_longitude',
        'plot_address',
        'comparison_variety',
        'comparison_brand',
        'assigned_by',
        'current_stage',
        'total_stages',
        'completed_stages',
        'status',
        'status_remarks',
        'status_date',
        'actual_yield',
        'yield_unit',
        'farmer_satisfaction',
        'farmer_feedback',
        'will_repurchase',
        'sync_status',
        'synced_at',
    ];

    protected $casts = [
        'quantity_given' => 'decimal:2',
        'assignment_date' => 'date',
        'sowing_date' => 'date',
        'expected_harvest_date' => 'date',
        'plot_size_acres' => 'decimal:2',
        'plot_latitude' => 'decimal:8',
        'plot_longitude' => 'decimal:8',
        'current_stage' => 'integer',
        'total_stages' => 'integer',
        'completed_stages' => 'integer',
        'status_date' => 'date',
        'actual_yield' => 'decimal:2',
        'will_repurchase' => 'boolean',
        'synced_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function demoMaster(): BelongsTo
    {
        return $this->belongsTo(DemoMaster::class);
    }

    public function demoDistribution(): BelongsTo
    {
        return $this->belongsTo(DemoDistribution::class);
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function assignedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function stageActivities(): HasMany
    {
        return $this->hasMany(DemoStageActivity::class)->orderBy('stage_order');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeByFarmer(Builder $query, int $farmerId): Builder
    {
        return $query->where('farmer_id', $farmerId);
    }
}
