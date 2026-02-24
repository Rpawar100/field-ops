<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Village extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'taluka_id',
        'pincode',
        'latitude',
        'longitude',
        'status',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function taluka(): BelongsTo
    {
        return $this->belongsTo(Taluka::class);
    }

    public function farmers(): HasMany
    {
        return $this->hasMany(Farmer::class);
    }

    public function retailers(): HasMany
    {
        return $this->hasMany(Retailer::class);
    }

    public function beats(): HasMany
    {
        return $this->hasMany(Beat::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function distributors(): HasMany
    {
        return $this->hasMany(Distributor::class);
    }

    /**
     * ZRTH hierarchies mapped via sdtv_zrth_mappings
     */
    public function zrthHierarchies(): BelongsToMany
    {
        return $this->belongsToMany(ZrthHierarchy::class, 'sdtv_zrth_mappings', 'village_id', 'zrth_hierarchy_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function sdtvMappings(): HasMany
    {
        return $this->hasMany(SdtvZrthMapping::class, 'village_id');
    }

    // ==================== SCOPES ====================

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }
}
