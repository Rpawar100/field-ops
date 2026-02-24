<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Distributor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'distributor_code',
        'name',
        'contact_person',
        'mobile',
        'email',
        'type',
        'address',
        'village_id',
        'pincode',
        'latitude',
        'longitude',
        'gst_number',
        'pan_number',
        'zrth_hierarchy_id',
        'credit_limit',
        'credit_days',
        'status',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'credit_limit' => 'decimal:2',
        'credit_days' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Type constants
    public const TYPE_SUPER_STOCKIST = 'super_stockist';
    public const TYPE_DISTRIBUTOR = 'distributor';
    public const TYPE_SUB_DISTRIBUTOR = 'sub_distributor';

    // ==================== RELATIONSHIPS ====================

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function zrthHierarchy(): BelongsTo
    {
        return $this->belongsTo(ZrthHierarchy::class, 'zrth_hierarchy_id');
    }

    public function retailers(): BelongsToMany
    {
        return $this->belongsToMany(Retailer::class, 'retailer_distributors', 'distributor_id', 'retailer_id')
                    ->withPivot('is_primary', 'credit_limit', 'credit_days', 'status')
                    ->withTimestamps();
    }

    public function retailerDistributors(): HasMany
    {
        return $this->hasMany(RetailerDistributor::class);
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

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }
}
