<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class DemoStageActivity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'demo_farmer_assignment_id',
        'stage_template_id',
        'stage_name',
        'stage_order',
        'planned_date',
        'actual_date',
        'days_after_sowing',
        'attributes',
        'measurements',
        'photos',
        'latitude',
        'longitude',
        'recorded_by',
        'status',
        'status_reason',
        'rescheduled_date',
        'crop_condition',
        'observations',
        'recommendations',
        'is_reviewed',
        'reviewed_by',
        'reviewed_at',
        'review_remarks',
        'sync_status',
        'synced_at',
    ];

    protected $casts = [
        'stage_order' => 'integer',
        'planned_date' => 'date',
        'actual_date' => 'date',
        'days_after_sowing' => 'integer',
        'attributes' => 'json',
        'measurements' => 'json',
        'photos' => 'json',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'rescheduled_date' => 'date',
        'is_reviewed' => 'boolean',
        'reviewed_at' => 'datetime',
        'synced_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function farmerAssignment(): BelongsTo
    {
        return $this->belongsTo(DemoFarmerAssignment::class, 'demo_farmer_assignment_id');
    }

    public function stageTemplate(): BelongsTo
    {
        return $this->belongsTo(DemoStageTemplate::class, 'stage_template_id');
    }

    public function recordedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function reviewedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function stagePhotos(): HasMany
    {
        return $this->hasMany(DemoStagePhoto::class);
    }

    public function linkedActivities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    // ==================== SCOPES ====================

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', 'pending')
                     ->whereDate('planned_date', '<', now());
    }
}
