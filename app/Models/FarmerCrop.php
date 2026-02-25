<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmerCrop extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'crop_id',
        'area_acres',
        'season',
        'years_cultivating',
        'average_yield_per_acre',
        'yield_unit',
        'is_primary_crop',
        'status',
    ];

    protected $casts = [
        'area_acres' => 'decimal:2',
        'years_cultivating' => 'integer',
        'average_yield_per_acre' => 'decimal:2',
        'is_primary_crop' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class);
    }
}
