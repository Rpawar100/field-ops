<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class ActivityType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'category',
        'description',
        'requires_farmer',
        'requires_retailer',
        'requires_beat',
        'requires_photo',
        'min_photos',
        'max_photos',
        'requires_gps',
        'counts_for_attendance',
        'points',
        'status',
    ];

    protected $casts = [
        'requires_farmer' => 'boolean',
        'requires_retailer' => 'boolean',
        'requires_beat' => 'boolean',
        'requires_photo' => 'boolean',
        'min_photos' => 'integer',
        'max_photos' => 'integer',
        'requires_gps' => 'boolean',
        'counts_for_attendance' => 'boolean',
        'points' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function activityMasters(): HasMany
    {
        return $this->hasMany(ActivityMaster::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeByCode(Builder $query, string $code): Builder
    {
        return $query->where('code', $code);
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }
}
