<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Beat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'beat_code',
        'name',
        'description',
        'zrth_hierarchy_id',
        'village_id',
        'coverage_village_ids',
        'beat_day',
        'visit_frequency_days',
        'total_farmers',
        'total_retailers',
        'estimated_distance_km',
        'estimated_time_minutes',
        'status',
    ];

    protected $casts = [
        'coverage_village_ids' => 'json',
        'visit_frequency_days' => 'integer',
        'total_farmers' => 'integer',
        'total_retailers' => 'integer',
        'estimated_distance_km' => 'decimal:2',
        'estimated_time_minutes' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Beat day constants
    public const DAY_MON = 'mon';
    public const DAY_TUE = 'tue';
    public const DAY_WED = 'wed';
    public const DAY_THU = 'thu';
    public const DAY_FRI = 'fri';
    public const DAY_SAT = 'sat';
    public const DAY_SUN = 'sun';

    // ==================== RELATIONSHIPS ====================

    public function zrthHierarchy(): BelongsTo
    {
        return $this->belongsTo(ZrthHierarchy::class, 'zrth_hierarchy_id');
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'beat_user_assignments', 'beat_id', 'user_id')
                    ->withPivot('is_primary', 'assigned_from', 'assigned_to', 'status')
                    ->withTimestamps();
    }

    public function userAssignments(): HasMany
    {
        return $this->hasMany(BeatUserAssignment::class);
    }

    public function farmers(): BelongsToMany
    {
        return $this->belongsToMany(Farmer::class, 'beat_farmers', 'beat_id', 'farmer_id')
                    ->withPivot('is_primary', 'status')
                    ->withTimestamps();
    }

    public function beatFarmers(): HasMany
    {
        return $this->hasMany(BeatFarmer::class);
    }

    public function retailers(): BelongsToMany
    {
        return $this->belongsToMany(Retailer::class, 'beat_retailers', 'beat_id', 'retailer_id')
                    ->withPivot('is_primary', 'status')
                    ->withTimestamps();
    }

    public function beatRetailers(): HasMany
    {
        return $this->hasMany(BeatRetailer::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(BeatSchedule::class);
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

    public function scopeByDay(Builder $query, string $day): Builder
    {
        return $query->where('beat_day', $day);
    }
}
