<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class DemoMaster extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lot_no',
        'crop_id',
        'variety_id',
        'product_id',
        'product_name',
        'total_quantity',
        'quantity_unit',
        'available_quantity',
        'stage_plan_id',
        'season',
        'year',
        'source_zrth_id',
        'source_location_name',
        'manufacturing_date',
        'expiry_date',
        'batch_number',
        'target_farmers',
        'assigned_farmers',
        'status',
    ];

    protected $casts = [
        'total_quantity' => 'decimal:2',
        'available_quantity' => 'decimal:2',
        'year' => 'integer',
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
        'target_farmers' => 'integer',
        'assigned_farmers' => 'integer',
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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function stagePlan(): BelongsTo
    {
        return $this->belongsTo(DemoStagePlan::class, 'stage_plan_id');
    }

    public function sourceZrth(): BelongsTo
    {
        return $this->belongsTo(ZrthHierarchy::class, 'source_zrth_id');
    }

    public function distributions(): HasMany
    {
        return $this->hasMany(DemoDistribution::class);
    }

    public function farmerAssignments(): HasMany
    {
        return $this->hasMany(DemoFarmerAssignment::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeBySeason(Builder $query, string $season): Builder
    {
        return $query->where('season', $season);
    }
}
