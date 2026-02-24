<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sku_code',
        'sku_name',
        'display_name',
        'category_id',
        'product_type',
        'brand_id',
        'variety_id',
        'crop_id',
        'pack_size',
        'pack_unit',
        'pack_quantity',
        'packs_per_case',
        'mrp',
        'selling_price',
        'distributor_price',
        'retailer_margin_percent',
        'hsn_code',
        'gst_rate',
        'description',
        'usage_instructions',
        'safety_instructions',
        'image_path',
        'applicable_crop_ids',
        'status',
        'launch_date',
        'discontinue_date',
    ];

    protected $casts = [
        'pack_quantity' => 'integer',
        'packs_per_case' => 'integer',
        'mrp' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'distributor_price' => 'decimal:2',
        'retailer_margin_percent' => 'decimal:2',
        'gst_rate' => 'decimal:2',
        'applicable_crop_ids' => 'json',
        'launch_date' => 'date',
        'discontinue_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function variety(): BelongsTo
    {
        return $this->belongsTo(Variety::class);
    }

    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class);
    }

    public function priceHistory(): HasMany
    {
        return $this->hasMany(ProductPriceHistory::class);
    }

    public function activityProducts(): HasMany
    {
        return $this->hasMany(ActivityProduct::class);
    }

    public function demoMasters(): HasMany
    {
        return $this->hasMany(DemoMaster::class);
    }

    public function farmerProductUsages(): HasMany
    {
        return $this->hasMany(FarmerProductUsage::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeByCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }
}
