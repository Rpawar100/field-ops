<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RetailerDistributor extends Model
{
    use HasFactory;

    protected $fillable = [
        'retailer_id',
        'distributor_id',
        'is_primary',
        'credit_limit',
        'credit_days',
        'status',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'credit_limit' => 'decimal:2',
        'credit_days' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function retailer(): BelongsTo
    {
        return $this->belongsTo(Retailer::class);
    }

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }
}
