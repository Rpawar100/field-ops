<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmerProductUsage extends Model
{
    use HasFactory;

    protected $table = 'farmer_product_usage';

    protected $fillable = [
        'farmer_id',
        'product_id',
        'crop_id',
        'usage_frequency',
        'quantity_per_season',
        'quantity_unit',
        'satisfaction',
        'feedback',
    ];

    protected $casts = [
        'quantity_per_season' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class);
    }
}
