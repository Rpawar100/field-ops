<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class ActivityMaster extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'activity_type_id',
        'code',
        'name',
        'description',
        'attributes_schema',
        'validation_rules',
        'applicable_roles',
        'applicable_crop_ids',
        'applicable_product_ids',
        'allowed_start_time',
        'allowed_end_time',
        'allow_backdated',
        'backdate_days_limit',
        'status',
    ];

    protected $casts = [
        'attributes_schema' => 'json',
        'validation_rules' => 'json',
        'applicable_roles' => 'json',
        'applicable_crop_ids' => 'json',
        'applicable_product_ids' => 'json',
        'allowed_start_time' => 'datetime',
        'allowed_end_time' => 'datetime',
        'allow_backdated' => 'boolean',
        'backdate_days_limit' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function activityType(): BelongsTo
    {
        return $this->belongsTo(ActivityType::class);
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
}
