<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Crop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'local_name',
        'crop_type',
        'typical_duration_days',
        'description',
        'image_path',
        'status',
    ];

    protected $casts = [
        'typical_duration_days' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Crop type constants
    public const TYPE_KHARIF = 'kharif';
    public const TYPE_RABI = 'rabi';
    public const TYPE_ZAID = 'zaid';
    public const TYPE_PERENNIAL = 'perennial';

    // ==================== RELATIONSHIPS ====================

    public function varieties(): HasMany
    {
        return $this->hasMany(Variety::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function farmerCrops(): HasMany
    {
        return $this->hasMany(FarmerCrop::class);
    }

    public function demoMasters(): HasMany
    {
        return $this->hasMany(DemoMaster::class);
    }

    public function demoStageTemplates(): HasMany
    {
        return $this->hasMany(DemoStageTemplate::class);
    }

    public function demoStagePlans(): HasMany
    {
        return $this->hasMany(DemoStagePlan::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('crop_type', $type);
    }
}
