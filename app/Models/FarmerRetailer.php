<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmerRetailer extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'retailer_id',
        'is_primary',
        'annual_purchase_value',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'annual_purchase_value' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function retailer(): BelongsTo
    {
        return $this->belongsTo(Retailer::class);
    }
}
